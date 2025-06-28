<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\Subscription;
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

    /**
     * Send notification for plan purchase event
     *
     * @param  $user
     * @param  $planName
     * @param  $amount
     * @param  $relatedModel (optional)
     * @param  $relatedModelId (optional)
     * @return void
     */
  public static function sendPlanPurchaseNotification($user, $planName, $amount, $relatedModel = null, $relatedModelId = null)
    {
        $subject = 'Plan Purchase Confirmation';

        Notification::create([
            'user_id' => $user->id,
            'type' => 'plan_purchase',
            'message' => "You have purchased the {$planName} plan for {$amount} USD.",
            'related_model' => $relatedModel,
            'related_model_id' => $relatedModelId,
            'is_read' => false,
        ]);

        Mail::to($user->email)->send(new class($subject, $user, $planName, $amount, $relatedModelId) extends \Illuminate\Mail\Mailable {
            public $subjectLine;
            public $user;
            public $planName;
            public $amount;
            public $relatedModelId;

            public function __construct($subjectLine, $user, $planName, $amount, $relatedModelId)
            {
                $this->subjectLine = $subjectLine;
                $this->user = $user;
                $this->planName = $planName;
                $this->amount = $amount;
                $this->relatedModelId = $relatedModelId;
            }

            public function build()
            {
                // Subscription ডেটা এখান থেকে লোড করবো
                $subscription = Subscription::find($this->relatedModelId);

                return $this->view('emails.notification.plan_purchase')
                    ->with([
                        'user' => $this->user,
                        'planName' => $this->planName,
                        'amount' => $this->amount,
                        'subscription' => $subscription,
                    ])
                    ->subject($this->subjectLine);
            }
        });
    }

}
