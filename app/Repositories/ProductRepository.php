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
        return $this->model->byCategory($categoryId)->with('images', 'category')->get();
    }
      public function search(string $searchTerm)
    {
        return $this->model->search($searchTerm)->with('images', 'category')->get();
    } 
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


}
