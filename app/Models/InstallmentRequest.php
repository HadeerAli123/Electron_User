<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentRequest extends Model
{
    use HasFactory;

    protected $table = 'installment_request';

    protected $fillable = [
        'order_id',
        'installment_plan_id',
        'status',
        'national_id',
        'job_title',
        'phone',
        'referral_code_id',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function referralReward()
    {
        return $this->hasOne(ReferralReward::class);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}