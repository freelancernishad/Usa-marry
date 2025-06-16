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
            $userData = optional($visit->visitor);

            return [
                'id' => $visit->visitor->id ?? null,
                'name' => $visit->visitor->name ?? '',
                'profile_picture' => $visit->visitor->profile_picture ?? '',
                'visited_hours_ago' => \Carbon\Carbon::parse($visit->created_at)->diffInHours(now()) . ' hours ago',

                // Additional profile info
                'age' => $userData->age ?? '',
                'height' => $userData->height ?? '',
                'caste' => $userData->caste ?? '',
                'religion' => $userData->religion ?? '',
                'highest_degree' => $userData->profile->highest_degree ?? '',
                'occupation' => $userData->profile->occupation ?? '',
            ];
        });

        return response()->json($visits);
    }
}
