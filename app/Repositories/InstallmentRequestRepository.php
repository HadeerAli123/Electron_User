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
    }



