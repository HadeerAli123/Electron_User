<?php

namespace App\Mail;


use App\Models\User;
use App\Models\ReferralCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class ReferralCodeGenerated extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $referralCode;

    public function __construct(User $user, ReferralCode $referralCode)
    {
        $this->user = $user;
        $this->referralCode = $referralCode;
    }

    public function build()
    {
        return $this->subject('كود الإحالة الخاص بك - ' . config('app.name'))
            ->view('emails.referral-code')
            ->with([
                'userName' => $this->user->name,
                'code' => $this->referralCode->code,
                'usageLimit' => $this->referralCode->usage_limit,
                'usageCount' => $this->referralCode->usage_count,
            ]);
    }
}








