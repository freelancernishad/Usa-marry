<?php

namespace App\Http\Controllers\UsaMarry\Api\User\PartnerPreference;

use App\Models\PartnerPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PartnerPreferenceController extends Controller
{
    public function show()
    {
        $preference = Auth::user()->partnerPreference;
        return response()->json($preference);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'age_min' => 'nullable|integer|min:18|max:99',
            'age_max' => 'nullable|integer|min:18|max:99|gte:age_min',
            'height_min' => 'nullable|numeric|between:100,250',
            'height_max' => 'nullable|numeric|between:100,250|gte:height_min',
            'marital_status' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $preference = $user->partnerPreference()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        updateProfileCompletion($user, 'partner_preference');

        return response()->json([
            'message' => 'Partner preference updated successfully',
            'preference' => $preference,
            'profile_completion' => $user->profile_completion
        ]);
    }
}
