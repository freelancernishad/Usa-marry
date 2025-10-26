<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Search;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserPaginationResource;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->per_page ?? 10;

        if ($user) {
            $oppositeGender = $user->gender === 'Male' ? 'Female' : 'Male';
        } else {
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

        // Helper function to handle comma-separated or array input
        $parseMultiValue = function ($value) {
            if (is_array($value)) return $value;
            return array_map('trim', explode(',', $value));
        };

        // Religion & Caste
        if ($request->filled('religion')) {
            $religions = $parseMultiValue($request->religion);
            $query->whereIn('religion', $religions);
        }

        if ($request->filled('caste')) {
            $castes = $parseMultiValue($request->caste);
            $query->whereIn('caste', $castes);
        }

        // Marital status
        if ($request->filled('marital_status')) {
            $statuses = $parseMultiValue($request->marital_status);
            $query->whereIn('marital_status', $statuses);
        }

        // Education
        if ($request->filled('education')) {
            $educations = $parseMultiValue($request->education);
            $query->whereHas('profile', function ($q) use ($educations) {
                $q->whereIn('highest_degree', $educations);
            });
        }

        // Occupation
        if ($request->filled('occupation')) {
            $occupations = $parseMultiValue($request->occupation);
            $query->whereHas('profile', function ($q) use ($occupations) {
                $q->whereIn('occupation', $occupations);
            });
        }

        // Country
        if ($request->filled('country')) {
            $countries = $parseMultiValue($request->country);
            $query->whereHas('profile', function ($q) use ($countries) {
                $q->whereIn('country', $countries);
            });
        }

        // Diet
        if ($request->filled('diet')) {
            $diets = $parseMultiValue($request->diet);
            $query->whereHas('profile', function ($q) use ($diets) {
                $q->whereIn('diet', $diets);
            });
        }

        // Drink
        if ($request->filled('drink')) {
            $drinks = $parseMultiValue($request->drink);
            $query->whereHas('profile', function ($q) use ($drinks) {
                $q->whereIn('drink', $drinks);
            });
        }

        // Smoke
        if ($request->filled('smoke')) {
            $smokes = $parseMultiValue($request->smoke);
            $query->whereHas('profile', function ($q) use ($smokes) {
                $q->whereIn('smoke', $smokes);
            });
        }

        // =================================================================
        // এখানে নতুন লাইনটি যোগ করা হয়েছে
        // এটি প্রথমে ছবির সংখ্যা অনুযায়ী সাজাবে (যাদের বেশি ছবি তারা আগে)
        // =================================================================
        $query->withCount('photos')->orderBy('photos_count', 'desc');

        // Sort results (এটি এখন দ্বিতীয় স্তরের সাজানোর নিয়ম হিসেবে কাজ করবে)
        if ($request->sort_by === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort_by === 'match') {
            $query->orderBy('profile_completion', 'desc');
        } else {
            // inRandomOrder() এর সাথে orderBy একসাথে ভালোভাবে কাজ করে না, তাই এক্ষেত্রে আমরা শুধু photos_count দিয়েই সাজাবো
            // অথবা আপনি চাইলে এটাকেও একটা অর্ডার দিতে পারেন, যেমন: ->orderBy('id', 'desc')
        }

        // Paginate
        $results = $query->paginate($perPage);
        $results = new UserPaginationResource($results);

        return response()->json($results);
    }

}
