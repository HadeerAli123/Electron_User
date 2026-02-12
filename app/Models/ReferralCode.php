<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'usage_limit',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installmentRequests()
    {
        return $this->hasMany(InstallmentRequest::class);
    }

    public function rewards()
    {
        return $this->hasMany(ReferralReward::class);
    }

    // Helper methods
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsed()
    {
        return $this->isValid();
    }
}