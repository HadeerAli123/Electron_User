<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInstallment extends Model
{
    use HasFactory;

    protected $table = 'product_installment';

    protected $fillable = [
        'product_id',
        'installment_plan_id',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }
}