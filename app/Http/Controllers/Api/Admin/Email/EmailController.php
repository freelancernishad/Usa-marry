<?php

namespace App\Http\Controllers\Api\Admin\Email;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Get paginated Email logs with filters and search
     */
    public function logs(Request $request)
    {
        $logsQuery = EmailLog::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $logsQuery->where('status', $request->status);
        }

        // Search by email, subject, user name, or message
        if ($request->filled('search')) {
            $search = $request->search;
            $logsQuery->where(function ($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
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
            $log->total_sent_count = EmailLog::where('email', $log->email)->count();
            return $log;
        });

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Send bulk Email using filters or specific user IDs
     */
    public function send(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:specific,filtered',
            'user_ids' => 'required_if:target_type,specific|array',
            'user_ids.*' => 'exists:users,id',
            'subject_template' => 'required|string',
            'message_template' => 'required|string',
        ]);

        $subjectTemplate = $request->subject_template;
        $messageTemplate = $request->message_template;
        $usersQuery = User::query();

        if ($request->target_type === 'specific') {
            $usersQuery->whereIn('id', $request->user_ids);
        } else {
            // Apply standard system filters
            $usersQuery = applyFilters($usersQuery, $request);
        }

        // Only get users who have an email address
        $users = $usersQuery->whereNotNull('email')->where('email', '!=', '')->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found matching the criteria or none have email addresses.'
            ], 400);
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            // Interpolate dynamic template variables in Subject
            $subject = str_replace(
                ['{name}', '{profile_id}', '{phone}', '{email}', '{gender}'],
                [$user->name, $user->profile_id, $user->phone, $user->email, $user->gender],
                $subjectTemplate
            );

            // Interpolate dynamic template variables in Body
            $message = str_replace(
                ['{name}', '{profile_id}', '{phone}', '{email}', '{gender}'],
                [$user->name, $user->profile_id, $user->phone, $user->email, $user->gender],
                $messageTemplate
            );

            try {
                // Send HTML email
                Mail::html($message, function ($mail) use ($user, $subject) {
                    $mail->to($user->email)
                         ->subject($subject);
                });

                $successCount++;
                EmailLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'subject' => $subject,
                    'message' => $message,
                    'status' => 'success',
                ]);
            } catch (\Exception $e) {
                $failedCount++;
                EmailLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'subject' => $subject,
                    'message' => $message,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Email sending complete. Success: {$successCount}, Failed: {$failedCount}.",
            'details' => [
                'total' => $users->count(),
                'success' => $successCount,
                'failed' => $failedCount,
            ]
        ]);
    }
}
