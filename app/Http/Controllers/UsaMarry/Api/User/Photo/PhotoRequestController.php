<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Photo;

use App\Models\User;
use App\Models\PhotoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompactUserResource;

class PhotoRequestController extends Controller
{
    // Send a photo request
    public function sendRequest(Request $request, $receiverId)
    {
        $sender = Auth::user();

        if ($sender->id == $receiverId) {
            return response()->json(['error' => 'Cannot send request to yourself'], 403);
        }

        $existing = PhotoRequest::where('sender_id', $sender->id)
            ->where('receiver_id', $receiverId)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Request already sent or exists', 'status' => $existing->status], 200);
        }

        $photoRequest = PhotoRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Photo request sent successfully', 'data' => $photoRequest], 201);
    }

    // Accept a photo request
    public function acceptRequest($requestId)
    {
        $user = Auth::user();

        $request = PhotoRequest::where('id', $requestId)
            ->where('receiver_id', $user->id)
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        $request->status = 'accepted';
        $request->save();

        return response()->json(['message' => 'Photo request accepted']);
    }

    // Reject a photo request
    public function rejectRequest($requestId)
    {
        $user = Auth::user();

        $request = PhotoRequest::where('id', $requestId)
            ->where('receiver_id', $user->id)
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        $request->status = 'rejected';
        $request->save();

        return response()->json(['message' => 'Photo request rejected']);
    }

    // List received requests
public function receivedRequests()
{
    $user = Auth::user();

    $requests = PhotoRequest::with('sender.profile')
        ->where('receiver_id', $user->id)
        ->where('status', 'pending')
        ->get()
        ->map(function ($request) {
            $request->sender_user = new CompactUserResource($request->sender);
            return $request;
        });

    return response()->json($requests);
}


    // List sent requests
   public function sentRequests()
{
    $user = Auth::user();

    $requests = PhotoRequest::with('receiver.profile')
        ->where('sender_id', $user->id)
        ->get()
        ->map(function ($request) {
            $request->receiver_user = new CompactUserResource($request->receiver);
            return $request;

        });

    return response()->json($requests);
}

}
