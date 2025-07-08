<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpVerifiedConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject("Welcome to Usa Marry, {$this->user->name}!")
                    ->view('emails.otp_verified')
                      ->with([
                        'name' => $this->user->name,
                    ]);
    }
}
