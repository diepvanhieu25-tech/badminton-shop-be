<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable; // Class này bắt buộc để fix lỗi P1006
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable 
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Mã xác thực OTP')
                    ->html("<h1>Mã OTP của bạn là: {$this->otp}</h1>");
    }
}