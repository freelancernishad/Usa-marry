<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{
    public static function sendUserNotification($user, $message, $subject = 'Notification', $relatedModel = null, $relatedModelId = null)
    {
        // ✅ Save to database
        Notification::create([
            'user_id' => $user->id,
            'type' => 'custom',
            'message' => $message,
            'related_model' => $relatedModel,
            'related_model_id' => $relatedModelId,
            'is_read' => false,
        ]);

        // ✅ Send email
        Mail::to($user->email)->send(new class($subject, $message) extends \Illuminate\Mail\Mailable {
            public $subjectLine;
            public $content;

            public function __construct($subjectLine, $content)
            {
                $this->subjectLine = $subjectLine;
                $this->content = $content;
            }

            public function build()
            {
                return $this->view('emails.notification.connection') // Simple view file
                    ->with(['content' => $this->content])
                    ->subject($this->subjectLine);
            }
        });
    }
}
