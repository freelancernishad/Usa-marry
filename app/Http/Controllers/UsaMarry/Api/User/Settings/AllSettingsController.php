<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Settings;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AllSettingsController extends Controller
{


    public function updatePhotoSettings(Request $request)
    {
        $user = User::find(Auth::id());

        $validator = Validator::make($request->all(), [
            'photo_privacy' => 'required|in:all,premium,accepted',
            'photo_visibility' => 'required|in:all,profile_only,hidden',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }


        $user->update($validator->validated());


        return response()->json([
            'message' => 'Photo settings updated successfully',
            'data' => $user->only(['photo_privacy', 'photo_visibility']),
        ]);
    }


}
