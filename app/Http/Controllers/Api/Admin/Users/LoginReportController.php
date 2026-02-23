<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginReportController extends Controller
{
    /**
     * Get a list of users who have logged in, including their login count, first login, and last login.
     */
    public function loggedInUsers(Request $request)
    {
        $users = User::select('id', 'name', 'profile_id', 'email', 'phone', 'whatsapps')
            // Using DB raw isn't strictly necessary with Eloquent, but this keeps it clean.
            ->whereHas('loginLogs')
            ->withCount('loginLogs as login_count')
            ->withMin('loginLogs as first_login', 'logged_in_at')
            ->withMax('loginLogs as last_login', 'logged_in_at')
            ->orderByDesc('last_login')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get a list of users who have never logged in.
     */
    public function notLoggedInUsers(Request $request)
    {
        $users = User::select('id', 'name', 'profile_id', 'email', 'phone', 'whatsapps')
            ->doesntHave('loginLogs')
            // Or alternatively: ->whereDoesntHave('loginLogs')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
