<?php

namespace App\Models;
use App\Services\ReferralCodeService; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class InstallmentRequest extends Model
{
    use HasFactory;

    protected $table = 'installment_request';

    protected $fillable = [
        'order_id',
        'installment_plan_id',
        'user_id',
        'monthly_salary',
        'status',
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




     public function approve(): void
    {
        $this->status = 'approved';
        $this->save();

        // Update order status
        $this->order->updateStatus('paid');

        // Create referral reward if applicable
        if ($this->referral_code_id) {
            $this->createReferralReward();
        }

        // ✅ Generate referral code for this user and send email
        $this->generateReferralCodeForUser();
    }

    public function reject(): void
    {
        $this->status = 'rejected';
        $this->save();

        // Optionally update order status
        $this->order->updateStatus('cancelled');
    } 


        protected function createReferralReward(): void
    {
        if (!$this->referralCode) {
            return;
        }

        ReferralReward::create([
            'referrer_user_id' => $this->referralCode->user_id,
            'referred_user_id' => $this->order->user_id,
            'installment_request_id' => $this->id,
        ]);

        // Increment usage count
        $this->referralCode->increment('usage_count');
    }

       protected function generateReferralCodeForUser(): void
    {
        $referralCodeService = app(ReferralCodeService::class);
        $referralCodeService->generateCodeForInstallment($this);
    }
}