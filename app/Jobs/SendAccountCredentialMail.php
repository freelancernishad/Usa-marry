<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\AccountCredentialMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAccountCredentialMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function handle()
    {
        try {
            Mail::to($this->user->email)
                ->send(new AccountCredentialMail($this->user, $this->password));



        } catch (\Exception $e) {
            // Mail failed, log the error
            Log::error("Account mail failed", [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'temporary_password' => $this->password,
                'error' => $e->getMessage(),
                'time' => now()->toDateTimeString(),
            ]);
        }
    }
}
