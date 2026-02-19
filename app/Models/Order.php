<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'order_number',
        'total_price',
        'payment_type',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function installmentRequest()
    {
        return $this->hasOne(InstallmentRequest::class);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isInstallment()
    {
        return $this->payment_type === 'installment';
    }
    public function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    /**
     * Calculate total from order items
     */
    public function calculateTotal(): float
    {
        return $this->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by payment type
     */
    public function scopeByPaymentType($query, string $paymentType)
    {
        return $query->where('payment_type', $paymentType);
    }

    /**
     * Scope to get user orders
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get recent orders
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}