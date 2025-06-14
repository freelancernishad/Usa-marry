<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileVisit;
use Carbon\Carbon;

class ProfileVisitController extends Controller
{
    public function visitors(Request $request)
    {
        $user = Auth::user();

        $visits = ProfileVisit::with(['visitor.profile']) // visitor er profile load korchi
            ->where('visited_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // transform result with hours ago
        $visits->getCollection()->transform(function ($visit) {
            return [
                'id'            => $visit->visitor->id ?? null,
                'name'          => $visit->visitor->name ?? null,
                'profile_picture'=> $visit->visitor->profile_picture ?? null,
                'visited_hours_ago' => Carbon::parse($visit->created_at)->diffInHours(now()) . ' hours ago',
            ];
        });

        return response()->json($visits);
    }
}
