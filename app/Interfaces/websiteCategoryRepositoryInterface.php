<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get parent categories only (no parent_id)
     */
    public function getParentCategories();

    /**
     * Get child categories of a specific parent
     */
    public function getChildCategories(int $parentId);

    /**
     * Get category with products
     */
    public function getCategoryWithProducts(int $categoryId);

    /**
     * Get category with children
     */
    public function getCategoryWithChildren(int $categoryId);

    /**
     * Get category tree (hierarchical structure)
     */
    public function getCategoryTree();

    /**
     * Check if category has products
     */

}