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
     public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}