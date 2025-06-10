<?php

namespace App\Helpers;

use App\Models\ContactView;

class SubscriptionHelper
{
    /**
     * Check and store contact view if allowed.
     *
     * @param \App\Models\User $user
     * @param int $contactId
     * @return array [ 'allowed' => bool, 'message' => string ]
     */
public static function canViewContact($user, $contactId)
{
    $subscription = $user->activeSubscription()->with('plan')->first();

    if (!$subscription) {
        return [
            'allowed' => false,
            'message' => 'No active subscription found',
        ];
    }

    $plan = $subscription->plan;

    $viewLimit = collect($plan['features'])
        ->firstWhere('key', 'view_contact')['value'] ?? 0;

    // ✅ Already viewed check
    $alreadyViewed = ContactView::where('user_id', $user->id)
        ->where('contact_user_id', $contactId)
        ->exists();

    // ✅ If already viewed, always allow regardless of limit
    if ($alreadyViewed) {
        return [
            'allowed' => true,
            'message' => 'Contact previously viewed. Access granted.',
        ];
    }

    // ✅ Check how many new contacts viewed during subscription period
    $currentViews = ContactView::where('user_id', $user->id)
        ->whereBetween('created_at', [$subscription->start_date, $subscription->end_date])
        ->count();

    if ($currentViews >= $viewLimit) {
        return [
            'allowed' => false,
            'message' => 'Contact view limit reached',
        ];
    }

    // ✅ Record new view
    ContactView::create([
        'user_id' => $user->id,
        'contact_user_id' => $contactId,
    ]);

    return [
        'allowed' => true,
        'message' => 'Contact view allowed',
    ];
}

}
