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
}
