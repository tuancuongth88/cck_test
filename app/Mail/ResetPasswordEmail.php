<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->to[0]['address'] ?: null;
        return $this->subject(__('messages.mes.subject_forget_password'))
            ->view('email.reset-password')
            ->with([
                'url' => URL::to('/').'/api/auth/password-reset?email=' . $email . '&token='.$this->token
            ]);
    }
}
