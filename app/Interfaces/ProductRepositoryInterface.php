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
    public function getInStock();

    /**
     * Get product with images and category
     */
    public function getProductWithRelations(int $productId);

    /**
     * Get product with installment plans
     */
    public function getProductWithInstallments(int $productId);

    /**
     * Get featured/latest products
     */
    public function getLatest(int $limit = 10);

    /**
     * Decrease product stock
     */
    public function decreaseStock(int $productId, int $quantity): bool;

    /**
     * Increase product stock
     */
    public function increaseStock(int $productId, int $quantity): bool;

    /**
     * Add images to product
     */
    public function addImages(int $productId, array $imagePaths): void;

    /**
     * Attach installment plans to product
     */
    public function attachInstallmentPlans(int $productId, array $planIds): void;

    /**
     * Get products with filters (category, price, search, etc.)
     */
    public function getFilteredProducts(array $filters);
}