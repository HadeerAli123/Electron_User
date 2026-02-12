<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_user_id',
        'referred_user_id',
        'installment_request_id',
    ];

    // Relationships
    public function referrerUser()
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function installmentRequest()
    {
        return $this->belongsTo(InstallmentRequest::class);
    }
}