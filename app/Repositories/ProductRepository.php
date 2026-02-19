<?php

namespace App\Repositories;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Product $model)
    {
       parent::__construct($model); 
    }


      public function getByCategory( $categoryId)
    {
        return $this->model->byCategory($categoryId)->with('images', 'category','installmentPlans')->get();
    }


      public function search(string $searchTerm)
    {
        return $this->model->search($searchTerm)->with('images', 'category')->paginate(10);
    } 


/////فاضلتيست الفانكشن دي 
     public function filterByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->model->priceBetween($minPrice, $maxPrice)->with('images', 'category')->get();
    }

    
       public function getProductWithInstallments(int $productId)
    {
        return $this->model->with('images', 'category', 'installmentPlans')->findOrFail($productId);
    }

       public function attachInstallmentPlans(int $productId, array $planIds): void
    {
        $product = $this->find($productId);
$product->installmentPlans()->syncWithoutDetaching($planIds);
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

}
