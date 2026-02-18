<?php

namespace App\Interfaces;


interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId);

    /**
     * Search products
     */
    public function search(string $searchTerm);

    /**
     * Filter products by price range
     */
    public function filterByPriceRange(float $minPrice, float $maxPrice);

    /**
     * Get in-stock products only
     */

    /**
     * Get product with images and category
     */

    /**
     * Get product with installment plans
     */
    public function getProductWithInstallments(int $productId);

    /**
     * Get featured/latest products
     */
  
    /**
     * Add images to product
     */
    

    /**
     * Attach installment plans to product
     */
    public function attachInstallmentPlans(int $productId, array $planIds): void;

    /**
     * Get products with filters (category, price, search, etc.)
     */
}