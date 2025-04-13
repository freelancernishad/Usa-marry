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

        // Start with opposite gender
        $oppositeGender = $user->gender === 'Male' ? 'Female' : 'Male';

        $query = User::where('gender', $oppositeGender)
            ->where('account_status', 'Active')
            ->where('id', '!=', $user->id)
            ->with(['profile', 'primaryPhoto']);

        // Apply filters
        if ($request->has('age_min') || $request->has('age_max')) {
            $minAge = $request->age_min ?? 18;
            $maxAge = $request->age_max ?? 99;
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
        }

        if ($request->has('height_min') || $request->has('height_max')) {
            $minHeight = $request->height_min ?? 100;
            $maxHeight = $request->height_max ?? 250;
            $query->whereBetween('height', [$minHeight, $maxHeight]);
        }

        if ($request->religion) {
            $query->where('religion', $request->religion);
            if ($request->caste) {
                $query->where('caste', $request->caste);
            }
        }

        if ($request->marital_status) {
            $query->where('marital_status', $request->marital_status);
        }

        if ($request->education) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('highest_degree', $request->education);
            });
        }

        if ($request->occupation) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('occupation', $request->occupation);
            });
        }

        if ($request->country) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('country', $request->country);
            });
        }

        // Lifestyle filters
        if ($request->diet) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('diet', $request->diet);
            });
        }

        if ($request->drink) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('drink', $request->drink);
            });
        }

        if ($request->smoke) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('smoke', $request->smoke);
            });
        }

        // Sort results
        if ($request->sort_by === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort_by === 'match') {
            // This would require calculating match % for each result
            $query->orderBy('profile_completion', 'desc');
        } else {
            $query->inRandomOrder();
        }

        $results = $query->paginate($perPage);

        return response()->json($results);
    }
}
