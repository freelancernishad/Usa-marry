<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginLogViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Dashboard Metrics
        $totalLoginsCount = LoginLog::count();
        $totalUniqueUsersCount = LoginLog::distinct('user_id')->count('user_id');
        
        $mostActiveUserLog = LoginLog::select('user_id')
            ->selectRaw('count(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->first();
            
        $mostActiveUser = $mostActiveUserLog 
            ? User::find($mostActiveUserLog->user_id) 
            : null;
            
        $mostActiveUserCount = $mostActiveUserLog ? $mostActiveUserLog->count : 0;
        
        $lastLoginLog = LoginLog::with('user')->orderByDesc('logged_in_at')->first();

        // 2. User List Query
        $query = User::select('id', 'name', 'profile_id', 'email', 'phone', 'whatsapps', 'gender')
            ->whereHas('loginLogs')
            ->withCount('loginLogs as login_count')
            ->withMax('loginLogs as last_login_at', 'logged_in_at');

        // Apply search if requested
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('profile_id', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapps', 'LIKE', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->input('sort', 'login_count');
        $sortOrder = $request->input('order', 'desc');

        if ($sortBy === 'login_count') {
            $query->orderBy('login_count', $sortOrder);
        } else {
            $query->orderBy('last_login_at', $sortOrder);
        }

        $users = $query->paginate($request->input('per_page', 15))
            ->withQueryString();

        return view('admin.login-logs', compact(
            'users', 
            'totalLoginsCount', 
            'totalUniqueUsersCount', 
            'mostActiveUser', 
            'mostActiveUserCount', 
            'lastLoginLog'
        ));
    }

    /**
     * Get detailed login logs for a specific user (AJAX).
     */
    public function details($id)
    {
        $user = User::select('id', 'name', 'profile_id', 'email', 'gender')->findOrFail($id);
        
        $logs = LoginLog::where('user_id', $id)
            ->orderBy('logged_in_at', 'desc')
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'ip_address' => $log->ip_address ?? 'N/A',
                    'device' => $log->device ?? 'Unknown Device',
                    'location' => $log->location ?? 'Unknown Location',
                    'logged_in_at' => $log->logged_in_at ? $log->logged_in_at->format('M d, Y h:i A') : 'N/A',
                    'relative_time' => $log->logged_in_at ? $log->logged_in_at->diffForHumans() : 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'user' => $user,
            'logs' => $logs
        ]);
    }
}
