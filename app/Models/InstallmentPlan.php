<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use HasFactory;

    protected $table = 'installment_plan';

    protected $fillable = [
        'title',
        'months',
        'interest_rate',
        'down_payment_percentage',
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'down_payment_percentage' => 'decimal:2',
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_installment', 'installment_plan_id', 'product_id')
            ->withTimestamps();
    }

    public function installmentRequests()
    {
        return $this->hasMany(InstallmentRequest::class);
    }

    // Helper methods
    public function calculateMonthlyPayment($productPrice)
    {
        $downPayment = $productPrice * ($this->down_payment_percentage / 100);
        $remainingAmount = $productPrice - $downPayment;
        $totalWithInterest = $remainingAmount * (1 + ($this->interest_rate / 100));
        return $totalWithInterest / $this->months;
    }

    public function calculateDownPayment($productPrice)
    {
        return $productPrice * ($this->down_payment_percentage / 100);
    }

    
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    // ========================================
    // Scopes
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}