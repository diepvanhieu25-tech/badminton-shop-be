<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        // Link trỏ về Frontend Next.js
        $url = env('FRONTEND_URL') . '/reset-password?token=' . $this->token . '&email=' . $this->email;

        return $this->subject('Đặt lại mật khẩu của bạn')
            ->markdown('emails.reset-password', [
                'url' => $url
            ]);
    }
}
