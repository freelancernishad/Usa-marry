<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password; // temporary password
    }

    public function build()
    {
        return $this->subject('🎉 Welcome! Your FREE Essential Plan is ready')
                    ->view('emails.bulk.account_credentials');
    }
}
