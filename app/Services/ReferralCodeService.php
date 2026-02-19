<?php

namespace App\Services;
use App\Models\ReferralCode;
use App\Models\InstallmentRequest;
use App\Mail\ReferralCodeGenerated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class ReferralCodeService
{
    /**
     * Create a new class instance.
     */
      public function generateCodeForInstallment(InstallmentRequest $installmentRequest)
    {
        $user = $installmentRequest->order->user;

        // Check if user already has an active code
        $existingCode = ReferralCode::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($existingCode) {
            // User already has a code, just send email reminder
            $this->sendCodeEmail($user, $existingCode);
            return $existingCode;
        }

        // Generate new referral code
        $referralCode = $this->createReferralCode($user->id);

        // Send email with the code
        $this->sendCodeEmail($user, $referralCode);

        return $referralCode;
    }

    /**
     * Create a new referral code
     */
    protected function createReferralCode(int $userId, ?int $usageLimit = 10)
    {
        return ReferralCode::create([
            'user_id' => $userId,
            'code' => $this->generateUniqueCode(),
            'usage_limit' => $usageLimit,
            'usage_count' => 0,
            'is_active' => true,
        ]);
    }

    /**
     * Generate unique code
     */
    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (ReferralCode::where('code', $code)->exists());

        return $code;
    }

    /**
     * Send referral code via email
     */
    protected function sendCodeEmail($user, $referralCode)
    {
        try {
            Mail::to($user->email)->send(new ReferralCodeGenerated($user, $referralCode));
        } catch (\Exception $e) {
            Log::error('Failed to send referral code email: ' . $e->getMessage());
        }
    }
}