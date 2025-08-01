<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use App\Models\User;
use App\Models\ContactView;
use Illuminate\Http\Request;
use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{


public function showContact($contactId)
{
    $user = Auth::user();

    // Check if user has permission to view the contact
    $result = SubscriptionHelper::canViewContact($user, $contactId);

    if (!$result['allowed']) {
        return response()->json(['message' => $result['message']], 403);
    }

    // Fetch the contact
    $contact = User::with('profile')->find($contactId);

    if (!$contact) {
        return response()->json(['message' => 'Contact not found'], 404);
    }

    // Send a connection request if not already connected
    $connectionResponse = connectWithUser($contactId);

    return response()->json([
        'message' => $result['message'],
        'connection_status' => $connectionResponse->getData(true)['message'] ?? null,
        'remaining_views' => $user->contact_view_balance, // ✅ use accessor here
        'contact' => [
            'name'             => $contact->name,
            'email'            => $contact->email,
            'phone'            => $contact->phone,
            'whatsapps'        => $contact->whatsapps,
            'family_location'  => $contact->family_location,
            'country'          => $contact->profile->country ?? null,
            'state'            => $contact->profile->state ?? null,
            'city'             => $contact->profile->city ?? null,
        ]
    ]);
}





public function myViewedContacts(Request $request)
{
    $user = Auth::user();

    $contactViews = ContactView::with(['contact.profile']) // load contact & profile
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate($request->input('per_page', 10)); // use per_page from request, default 10

    // Customize data before returning, but structure same as default
    $contactViews->getCollection()->transform(function ($view) {
        return [
            'id'               => $view->contact->id ?? null,
            'name'             => $view->contact->name ?? null,
            'profile_picture'  => $view->contact->profile_picture ?? null,
            'email'            => $view->contact->email ?? null,
            'phone'            => $view->contact->phone ?? null,
            'whatsapps'        => $view->contact->whatsapps ?? null,
            'family_location'  => $view->contact->family_location ?? null,
            'country'          => $view->contact->profile->country ?? null,
            'state'            => $view->contact->profile->state ?? null,
            'city'             => $view->contact->profile->city ?? null,
            'viewed_at'        => $view->created_at->toDateTimeString(),
        ];
    });

    return response()->json($contactViews);
}

}
