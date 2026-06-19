<?php

namespace App\Http\Controllers\Api\Admin\Sms;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use App\Services\Twilio\TwilioService;

class SmsController extends Controller
{
    /**
     * Get paginated SMS logs with filters and search
     */
    public function logs(Request $request)
    {
        $logsQuery = SmsLog::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $logsQuery->where('status', $request->status);
        }

        // Search by phone, message, user name, or user email
        if ($request->filled('search')) {
            $search = $request->search;
            $logsQuery->where(function ($q) use ($search) {
                $q->where('phone', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $logs = $logsQuery->latest()->paginate($request->input('per_page', 15));

        // Append total sent count to each log item for frequency tracking
        $logs->getCollection()->transform(function ($log) {
            $log->total_sent_count = SmsLog::where('phone', $log->phone)->count();
            return $log;
        });

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Send bulk SMS using filters or specific user IDs
     */
    public function send(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:specific,filtered',
            'user_ids' => 'required_if:target_type,specific|array',
            'user_ids.*' => 'exists:users,id',
            'message_template' => 'required|string',
        ]);

        $messageTemplate = $request->message_template;
        $usersQuery = User::query();

        if ($request->target_type === 'specific') {
            $usersQuery->whereIn('id', $request->user_ids);
        } else {
            // Apply standard system filters
            $usersQuery = applyFilters($usersQuery, $request);
        }

        // Only get users who have a phone number
        $users = $usersQuery->whereNotNull('phone')->where('phone', '!=', '')->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found matching the criteria or none have phone numbers.'
            ], 400);
        }

        $twilioService = app(TwilioService::class);
        $successCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            // Format phone number to clean it
            $phone = preg_replace('/[^0-9+]/', '', $user->phone);

            // Interpolate dynamic template variables
            $message = str_replace(
                ['{name}', '{profile_id}', '{phone}', '{email}', '{gender}'],
                [$user->name, $user->profile_id, $user->phone, $user->email, $user->gender],
                $messageTemplate
            );

            // Send SMS
            $success = $twilioService->sendSMS($phone, $message);

            if ($success) {
                $successCount++;
                SmsLog::create([
                    'user_id' => $user->id,
                    'phone' => $phone,
                    'message' => $message,
                    'status' => 'success',
                ]);
            } else {
                $failedCount++;
                SmsLog::create([
                    'user_id' => $user->id,
                    'phone' => $phone,
                    'message' => $message,
                    'status' => 'failed',
                    'error_message' => 'Failed to send via gateway',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "SMS sending complete. Success: {$successCount}, Failed: {$failedCount}.",
            'details' => [
                'total' => $users->count(),
                'success' => $successCount,
                'failed' => $failedCount,
            ]
        ]);
    }
}
