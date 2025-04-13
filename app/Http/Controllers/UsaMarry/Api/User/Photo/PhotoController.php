<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Photo;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Auth::user()->photos;
        return response()->json($photos);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'is_primary' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Upload to S3 using the helper function
        $filePath = uploadFileToS3($request->file('photo'), 'profiles');


        $photo = Auth::user()->photos()->create([
            'path' => $filePath, // This will now be the S3 URL
            'is_primary' => $request->is_primary ?? false
        ]);

        // If this is set as primary, unset others
        if ($photo->is_primary) {
            Auth::user()->photos()
                ->where('id', '!=', $photo->id)
                ->update(['is_primary' => false]);
        }

        // Update profile completion
        $user = Auth::user();
        updateProfileCompletion($user, 'photos');

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'photo' => $photo,
            'profile_completion' => $user->profile_completion
        ]);
    }

    public function setPrimary(Photo $photo)
    {
        if ($photo->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Auth::user()->photos()->update(['is_primary' => false]);
        $photo->update(['is_primary' => true]);

        return response()->json(['message' => 'Primary photo set successfully']);
    }

    public function destroy(Photo $photo)
    {
        if ($photo->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete from S3
        try {
            $path = str_replace(config('AWS_FILE_LOAD_BASE'), '', $photo->path);
            Storage::disk('s3')->delete($path);
        } catch (\Exception $e) {
            \Log::error('Error deleting file from S3: ' . $e->getMessage());
        }

        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully']);
    }


}
