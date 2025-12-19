<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\SendAccountCredentialMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class AccountMailController extends Controller
{
    /**
     * Send account credentials to all users in batches respecting daily limit.
     */
    public function sendCredentials()
    {
        $dailyLimit = 400;

        // Get all users with id > 774
        $users = User::where('id', '>', 774)->get();

        // Chunk users based on daily limit
        $chunks = $users->chunk($dailyLimit);

        foreach ($chunks as $dayIndex => $chunk) {
            foreach ($chunk as $user) {
                // Generate temporary password if not exists
                if (!$user->temporary_password) {
                    $tempPassword = Str::upper(Str::random(3)) . '-' . Str::random(3) . '-' . rand(100, 999);
                    $user->update(['temporary_password' => $tempPassword]);
                } else {
                    $tempPassword = $user->temporary_password;
                }

                // Dispatch mail job with delay based on batch
                SendAccountCredentialMail::dispatch($user, $tempPassword)
                    ->delay(now()->addDays($dayIndex)); // 1st batch today, next batch tomorrow, etc.
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Account credentials jobs dispatched successfully. Emails will be sent respecting daily limit.',
        ]);
    }



    /**
     * Run the queue worker manually via route.
     * ⚠ Only for testing / dev. Not recommended in production.
     */
    public function runQueue()
    {
        // Run queue worker once and return output
        // Using Artisan::call for manual execution
        $exitCode = Artisan::call('queue:work', [
            '--once' => true,   // process one job then stop
            '--tries' => 3,     // retry 3 times if failed
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Queue worker executed one job.',
            'exit_code' => $exitCode,
            'output' => Artisan::output(),
        ]);
    }

}
