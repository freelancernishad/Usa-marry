<?php
namespace App\Http\Controllers\UsaMarry\Api\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // userId দিয়ে ঐ user কে করা report গুলো দেখাবে
    public function reportsByUser($userId)
    {
        $reports = Report::with(['reporter', 'reportedUser'])
                    ->where('reported_user_id', $userId)
                    ->latest()
                    ->paginate(20);

        return response()->json($reports);
    }

    // admin জন্য সব রিপোর্ট দেখতে চাইলে index() রাখা যাবে (ঐচ্ছিক)
    public function index()
    {
        $reports = Report::with(['reporter', 'reportedUser'])
                    ->latest()
                    ->paginate(20);

        return response()->json($reports);
    }
}
