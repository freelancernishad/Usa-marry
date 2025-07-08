<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Search;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
public function search(Request $request)
{
    $user = Auth::user();
    $perPage = $request->per_page ?? 10;

    if ($user) {
        // Start with opposite gender for authenticated user
        $oppositeGender = $user->gender === 'Male' ? 'Female' : 'Male';
    } else {
        // Use gender from request for guest user, default to 'Female' if not provided
        $oppositeGender = $request->gender ?? 'Female';
        $user = (object) ['id' => 0];
    }

    $query = User::where('gender', $oppositeGender)
        ->where('account_status', 'Active')
        ->where('id', '!=', $user->id)
        ->with(['profile', 'primaryPhoto']);

    // Age filter
    if ($request->filled('age_min') || $request->filled('age_max')) {
        $minAge = $request->age_min ?? 18;
        $maxAge = $request->age_max ?? 99;
        $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
    }

    // Height filter
    if ($request->filled('height_min') || $request->filled('height_max')) {
        $minHeight = $request->height_min ?? 100;
        $maxHeight = $request->height_max ?? 250;
        $query->whereBetween('height', [$minHeight, $maxHeight]);
    }

    // Religion and caste
    if ($request->filled('religion')) {
        $query->where('religion', $request->religion);
        if ($request->filled('caste')) {
            $query->where('caste', $request->caste);
        }
    }

    // Marital status
    if ($request->filled('marital_status')) {
        $query->where('marital_status', $request->marital_status);
    }

    // Education
    if ($request->filled('education')) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('highest_degree', $request->education);
        });
    }

    // Occupation
    if ($request->filled('occupation')) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('occupation', $request->occupation);
        });
    }

    // Country
    if ($request->filled('country')) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('country', $request->country);
        });
    }

    // Lifestyle: Diet
    if ($request->filled('diet')) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('diet', $request->diet);
        });
    }

    // Lifestyle: Drink
    if ($request->filled('drink')) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('drink', $request->drink);
        });
    }

    // Lifestyle: Smoke
    if ($request->filled('smoke')) {
        $query->whereHas('profile', function($q) use ($request) {
            $q->where('smoke', $request->smoke);
        });
    }

    // Sort results
    if ($request->sort_by === 'newest') {
        $query->orderBy('created_at', 'desc');
    } elseif ($request->sort_by === 'match') {
        $query->orderBy('profile_completion', 'desc');
    } else {
        $query->inRandomOrder();
    }

    // Paginate and return
    $results = $query->paginate($perPage);
    $results = new \App\Http\Resources\UserPaginationResource($results);

    return response()->json($results);
}

}
