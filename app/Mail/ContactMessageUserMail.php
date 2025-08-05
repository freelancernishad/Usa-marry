<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;

    public function __construct(ContactMessage $message)
    {
        $this->contactMessage = $message;
    }

    public function build()
    {
        return $this->subject('Thanks for contacting Usa Marry')
                    ->view('emails.contact_user_mail');
    }
}
