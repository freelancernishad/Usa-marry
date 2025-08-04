<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContactMessage;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class RetryFailedContactEmails extends Command
{
    protected $signature = 'contact:retry-emails';
    protected $description = 'Retry sending failed contact form emails';

    public function handle()
    {
        $messages = ContactMessage::where('email_sent', false)->get();

        foreach ($messages as $message) {
            try {
                Mail::to('support@yourdomain.com')->send(new ContactMessageMail($message));
                $message->update(['email_sent' => true]);
                $this->info("Email sent for message ID: {$message->id}");
            } catch (\Exception $e) {
                \Log::error("Retry failed for message ID: {$message->id} - " . $e->getMessage());
            }
        }
    }
}

// * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
