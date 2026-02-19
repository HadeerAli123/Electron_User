<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock',
        'cover_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function installmentPlans()
    {
        return $this->belongsToMany(InstallmentPlan::class, 'product_installment', 'product_id', 'installment_plan_id')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper methods
    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function hasInstallmentPlans()
    {
        return $this->installmentPlans()->count() > 0;
    }
     public function availableVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }


    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Decrease product stock
     */
    public function decreaseStock(int $quantity): bool
    {
        if (!$this->hasStock($quantity)) {
            return false;
        }

        $this->stock -= $quantity;
        $this->save();

        return true;
    }

    /**
     * Increase product stock
     */
    public function increaseStock(int $quantity): void
    {
        $this->stock += $quantity;
        $this->save();
    }



    /**
     * Get product with discount if applicable
     */
    public function getPriceAfterDiscount(?float $discount = null): float
    {
        if (!$discount) {
            return $this->price;
        }

        return $this->price - ($this->price * $discount / 100);
    }

    /**
     * Scope to filter products by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to get only in-stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope to search products
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%");
    }

    /**
     * Scope to filter by price range
     */
    public function scopePriceBetween($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }


    public function getGroupedAvailableVariants()
{
    $grouped = [];

    foreach ($this->availableVariants as $variant) {
        foreach ($variant->variantValues as $variantValue) {
            $attributeName = $variantValue->attributeValue->attribute->name;
            $attributeValue = $variantValue->attributeValue->value;

            // استخدم مصفوفة كـ "set" لتفادي in_array
            $grouped[$attributeName][$attributeValue] = true;
        }
    }

    // فقط إرجاع القيم الفعلية
    foreach ($grouped as $attributeName => $values) {
        $grouped[$attributeName] = array_keys($values);
    }

    return $grouped;
}
     
}