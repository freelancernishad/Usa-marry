<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SubscriptionHelper;
use App\Models\User;

class ContactController extends Controller
{
    public function showContact($contactId)
    {
        $user = Auth::user();

        $result = SubscriptionHelper::canViewContact($user, $contactId);

        if (!$result['allowed']) {
            return response()->json(['message' => $result['message']], 403);
        }

        $contact = User::with('profile')->find($contactId);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        return response()->json([
            'message' => $result['message'],
            'contact' => [
                'name'             => $contact->name,
                'email'            => $contact->email,
                'phone'            => $contact->phone,
                'family_location'  => $contact->family_location,
                'country'          => $contact->profile->country ?? null,
                'state'            => $contact->profile->state ?? null,
                'city'             => $contact->profile->city ?? null,
            ]
        ]);
    }
}
