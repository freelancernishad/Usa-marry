<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use Carbon\Carbon;
use App\Models\ProfileVisit;
use Illuminate\Http\Request;
use App\Models\UserConnection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $visits->getCollection()->transform(function ($visit,$user) {
            $userData = optional($visit->visitor);


                    // Check if authenticated user has sent a connection request
        $connectionRequestStatus = null;
     
            $connection = UserConnection::where('user_id', $user->id)
            ->where('connected_user_id', $visit->visitor->id)
            ->first();
            $connectionRequestStatus = $connection ? $connection->status : null;
   


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
                'connection_request_Status' => $connectionRequestStatus,
            ];
        });

        return response()->json($visits);
    }
}
