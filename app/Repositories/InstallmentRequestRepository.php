<?php

namespace App\Repositories;
use App\Models\InstallmentRequest;
use App\Models\InstallmentPlan;
use App\Models\Product;
use App\Models\ReferralCode;
use App\Interfaces\InstallmentRequestRepositoryInterface;
class InstallmentRequestRepository extends BaseRepository implements InstallmentRequestRepositoryInterface
{
    /**
     * Create a new class instance.
     */
  protected $installmentRequestModel;

    public function __construct(InstallmentPlan $model)
    {
        parent::__construct($model);
        $this->installmentRequestModel = new InstallmentRequest();
    }
//////get all instalmentplans

    public function getAllPlans()
    {
        return $this->model->all();
    }

//// بديله بلان اي دي سعرضلي البرودكتس الي ليها البلان اي دي ده
   public function getPlanWithProducts(int $planId)
    {
        return $this->model->with('products')->findOrFail($planId);
    }

    // بديله برودكت اي دي يردلي كل البلانات الي ليها البرودكت ده
    public function getProductPlans(int $productId)
    {
        $product = Product::with('installmentPlans')->findOrFail($productId);
        return $product->installmentPlans;
    }
// بديله ريكويست اي دي يردلي كل الديتا بتاع الريكويست ده
   public function getRequest(int $requestId)
    {
        return $this->installmentRequestModel
            ->with('order.orderItems.product', 'installmentPlan', 'referralCode')
            ->findOrFail($requestId);
    }
public function verifyReferralCode(string $code)
    {
        $referralCode = ReferralCode::where('code', $code)->first();

        if (!$referralCode || !$referralCode->isValid()) {
            return null;
        }

        return $referralCode;
    }

    //بيكريت طلب تقسيط 
    public function createRequest(array $data)
    {
        // Verify referral code if provided
        if (isset($data['referral_code'])) {
            $referralCode = $this->verifyReferralCode($data['referral_code']);
            
            if ($referralCode) {
                $data['referral_code_id'] = $referralCode->id;
            }
            
            unset($data['referral_code']);
        }

        return $this->installmentRequestModel->create($data);
    }





  public function getRequestsByStatus(string $status)
    {
        return $this->installmentRequestModel
            ->byStatus($status)
            ->with('order.user', 'installmentPlan')
            ->latest()
            ->get();
    }

    /**
     * Approve installment request
     */
    public function approveRequest(int $requestId): bool
    {
        $request = $this->installmentRequestModel->findOrFail($requestId);
        $request->approve();
        return true;
    }

    /**
     * Reject installment request
     */
    public function rejectRequest(int $requestId): bool
    {
        $request = $this->installmentRequestModel->findOrFail($requestId);
        $request->reject();
        return true;
    }

    /**
     * Get user installment requests
     */
    public function getUserRequests(int $userId)
    {
        return $this->installmentRequestModel
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('order.orderItems.product', 'installmentPlan')
            ->latest()
            ->get();
    }

    /**
     * Calculate installment details
     */
    public function calculateInstallment(int $planId, float $productPrice): array
    {
        $plan = $this->find($planId);
        return $plan->getInstallmentDetails($productPrice);
    }

    /**
     * Get pending requests
     */
    public function getPendingRequests()
    {
        return $this->getRequestsByStatus('pending');
    }


    public function getProductWithAvailableVariants(int $productId)
    {
        return $this->model->with([
            'images',
            'category',
            'installmentPlans',
            'availableVariants' => function ($query) {
                $query->with([
                    'variantValues.attributeValue.attribute'
                ]);
            }
        ])->findOrFail($productId);
    }


    public function getProductVariantsGrouped(int $productId)
{
    $product = $this->getProductWithAvailableVariants($productId);
    
    $groupedVariants = [];
    
    foreach ($product->availableVariants as $variant) {
        $variantData = [
            'variant_id' => $variant->id,
            'stock' => $variant->stock,
            'attributes' => []
        ];
        
        foreach ($variant->variantValues as $variantValue) {
            $attributeValue = $variantValue->attributeValue;
            $attribute = $attributeValue->attribute;
            
            $variantData['attributes'][$attribute->name] = [
                'attribute_id' => $attribute->id,
                'attribute_value_id' => $attributeValue->id,
                'value' => $attributeValue->value,
            ];
        }
        
        $groupedVariants[] = $variantData;
    }
    
    return [
        'product' => $product,
        'variants' => $groupedVariants,
        'grouped_attributes' => $this->groupAttributesByType($product),
    ];
}


private function groupAttributesByType($product)
{
    $grouped = [];
    
    foreach ($product->availableVariants as $variant) {
        foreach ($variant->variantValues as $variantValue) {
            $attributeValue = $variantValue->attributeValue;
            $attribute = $attributeValue->attribute;
            
            if (!isset($grouped[$attribute->name])) {
                $grouped[$attribute->name] = [
                    'attribute_id' => $attribute->id,
                    'values' => []
                ];
            }
            
            // تجنب التكرار
            $valueExists = false;
            foreach ($grouped[$attribute->name]['values'] as $existingValue) {
                if ($existingValue['id'] === $attributeValue->id) {
                    $valueExists = true;
                    break;
                }
            }
            
            if (!$valueExists) {
                $grouped[$attribute->name]['values'][] = [
                    'id' => $attributeValue->id,
                    'value' => $attributeValue->value,
                ];
            }
        }
    }
    
    return $grouped;
}

}






    



