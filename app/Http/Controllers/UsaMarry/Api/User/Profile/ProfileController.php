<?php

namespace App\Http\Controllers\UsaMarry\Api\User\Profile;

use App\Models\User;
use App\Models\Profile;
use App\Models\ContactView;
use Illuminate\Http\Request;
use App\Models\UserConnection;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SubscriptionResource;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user()->load(['profile', 'photos', 'partnerPreference']);

        // Using UserResource to transform the data
        $user = new UserResource($user);
        return response()->json($user);
    }


    public function updateBasicInfo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            // Required personal details
            'name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'phone' => 'nullable|numeric',

            // Religious and background information
            'religion' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|in:Never Married,Divorced,Widowed,Awaiting Divorce',

            // Physical attributes
            'height' => 'nullable|numeric|between:100,250',
            'disability' => 'nullable|boolean',
            'mother_tongue' => 'nullable|string|max:255',

            // Family information
            'father_status' => 'nullable|string|max:255',
            'mother_status' => 'nullable|string|max:255',
            'siblings' => 'nullable|integer|min:0',
            'family_type' => 'nullable|string|in:Nuclear,Joint,Other',
            'family_values' => 'nullable|string|in:Traditional,Moderate,Liberal',
            'financial_status' => 'nullable|string|in:Affluent,Upper Middle Class,Middle Class,Lower Middle Class',

            // Lifestyle
            'diet' => 'nullable|string|in:Vegetarian,Eggetarian,Non-Vegetarian,Vegan',
            'drink' => 'nullable|string|in:No,Occasionally,Yes',
            'smoke' => 'nullable|string|in:No,Occasionally,Yes',

            // Education and career
            'highest_degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string|in:Government,Private,Business,Self-Employed,Not Working',

            // Location
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'resident_status' => 'nullable|string|in:Citizen,Permanent Resident,Temporary Resident',

            // Horoscope
            'has_horoscope' => 'nullable|boolean',
            'rashi' => 'nullable|string|max:255',
            'nakshatra' => 'nullable|string|max:255',
            'manglik' => 'nullable|string|in:Yes,No,Partial',

            // Profile settings
            'profile_created_by' => 'nullable|string|in:Self,Parent,Sibling,Relative,Friend',
            'show_contact' => 'nullable|boolean',
            'visible_to' => 'nullable|string|in:All,My Community,My Matches',
            'about' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update user basic fields
        $user->update($request->only([
            'name', 'gender', 'dob', 'phone', 'religion',
            'caste', 'sub_caste', 'marital_status', 'height',
            'disability', 'mother_tongue', 'profile_created_by'
        ]));

        // Update or create the profile
        $profileData = $request->except([
            'name', 'gender', 'dob', 'phone', 'religion',
            'caste', 'sub_caste', 'marital_status', 'height',
            'disability', 'mother_tongue', 'profile_created_by'
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        updateProfileCompletion($user, 'basic_info');
        $user->load('profile'); // Reload with profile




        return response()->json([
            'message' => 'Basic info updated successfully',
            'profile_completion' => $user->profile_completion,
            'user' =>  new UserResource($user)
        ]);

    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            // User model fields
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|nullable|numeric',
            'gender' => 'sometimes|nullable|in:Male,Female,Other',
            'dob' => 'sometimes|nullable|date',
            'religion' => 'sometimes|nullable|string|max:255',
            'caste' => 'sometimes|nullable|string|max:255',
            'sub_caste' => 'nullable|string|max:255',
            'marital_status' => 'sometimes|nullable|string',
            'height' => 'sometimes|nullable|numeric|between:100,250',

            'blood_group' => 'sometimes|nullable|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'disability_issue' => 'sometimes|nullable|string|max:255',
            'family_location' => 'sometimes|nullable|string|max:255',
            'grew_up_in' => 'sometimes|nullable|string|max:255',

            // Add hobbies field
            'hobbies' => 'sometimes|nullable|array', // Add validation for hobbies as an array
            'hobbies.*' => 'string|max:255', // Each hobby should be a string with a max length of 255



            'disability' => 'nullable|boolean',
            'mother_tongue' => 'sometimes|nullable|string|max:255',
            'profile_created_by' => 'nullable|string|in:Self,Parent,Sibling,Relative,Friend',
            // 'account_status' => 'sometimes|in:Active,Suspended,Deleted',

            // Profile model fields
            'about' => 'nullable|string|max:1000',
            'highest_degree' => 'sometimes|nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'occupation' => 'sometimes|nullable|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'employed_in' => 'nullable|string',
            'father_status' => 'nullable|string|max:255',
            'mother_status' => 'nullable|string|max:255',
            'siblings' => 'nullable|integer|min:0',
            'family_type' => 'nullable|string|in:Nuclear,Joint,Other',
            'family_values' => 'nullable|string|in:Traditional,Moderate,Liberal',
            'financial_status' => 'nullable|string|in:Affluent,Upper Middle Class,Middle Class,Lower Middle Class',
            'diet' => 'sometimes|nullable|string|in:Vegetarian,Eggetarian,Non-Vegetarian,Vegan',
            'drink' => 'sometimes|nullable|string|in:No,Occasionally,Yes',
            'smoke' => 'sometimes|nullable|string|in:No,Occasionally,Yes',
            'country' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'resident_status' => 'nullable|string|in:Citizen,Permanent Resident,Temporary Resident',

            'rashi' => 'nullable|string|max:255',
            'nakshatra' => 'nullable|string|max:255',
            'manglik' => 'nullable|string|in:Yes,No,Partial',

            'visible_to' => 'nullable|string|in:All,My Community,My Matches',


           'update_step' => 'nullable|string|in:account_signup,profile_creation,personal_information,location_details,education_career,about_me,photos,partner_preference'

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update user fields
        $userFields = $request->only([
            'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'blood_group','disability_issue','family_location','grew_up_in', 'disability', 'mother_tongue',
            'profile_created_by',
        ]);


        // If hobbies are provided, update them in the profile
        if ($request->has('hobbies')) {
            $userFields['hobbies'] = $request->hobbies;
        }

        if (!empty($userFields)) {
            $user->update($userFields);
        }

        // Update profile fields
        $profileFields = $request->except([
            'name', 'email', 'phone', 'gender', 'dob', 'religion', 'caste',
            'sub_caste', 'marital_status', 'height', 'blood_group','disability_issue','family_location','grew_up_in', 'disability', 'mother_tongue',
            'profile_created_by', 'account_status', 'password', 'verified',
            'profile_completion', 'email_verified_at', 'email_verification_hash',
            'otp', 'otp_expires_at'
        ]);


        // Set defaults if not provided or blank
        $profileFields['has_horoscope'] = $request->filled('has_horoscope') ? $request->has_horoscope : 0;
        $profileFields['show_contact'] = $request->filled('show_contact') ? $request->show_contact : 0;
        $profileFields['visible_to'] = $request->filled('visible_to') ? $request->visible_to : 'My Matches';



        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileFields
        );

        updateProfileCompletion($user, $request->update_step ?? 'profile_creation');



        return response()->json([
            'message' => 'Profile updated successfully',
            'profile_completion' => $user->profile_completion,
            'user' =>  new UserResource($user)

        ]);
    }

public function profileOverview()
{
    $user = Auth::user()->load('activeSubscription.plan');

    // Invitations
    $pendingInvitations = UserConnection::where('user_id', $user->id)
        ->where('status', 'pending')->count();


    $acceptedInvitations = UserConnection::where(function ($query) use ($user) {
        $query->where('user_id', $user->id);
        // ->orWhere('connected_user_id', $user->id);
    })->where('status', 'accepted')->count();

    // Contacts Viewed
    $contactsViewedCount = ContactView::where('user_id', $user->id)->count();

    // Unique Profile Visitors
    $recentVisitorCount = \App\Models\ProfileVisit::where('visited_id', $user->id)
        ->distinct('visitor_id')
        ->count('visitor_id');


    // Subscription Info
    $subscription = $user->activeSubscription;
    $totalViewContactLimit = 0;

    if ($subscription && is_array($subscription->plan_features)) {
        $feature = collect($subscription->plan_features)->firstWhere('key', 'view_contact');
        $totalViewContactLimit = isset($feature['value']) ? (int) $feature['value'] : 0;
    }

    $remainingBalance = max(0, $totalViewContactLimit - $contactsViewedCount);


    // Contact View Usage Percentage
    $contactViewUsagePercentage = 0;
    if ($totalViewContactLimit > 0) {
        $contactViewUsagePercentage = round(($contactsViewedCount / $totalViewContactLimit) * 100, 2);
    }

    return response()->json([
        'status' => true,
        'message' => 'Profile overview fetched successfully',
        'basic_stats' => [
            'pending_invitations' => $pendingInvitations,
            'accepted_invitations' => $acceptedInvitations,
            'recent_visitors' => $recentVisitorCount,
        ],
        'premium_stats' => [
            'contacts_viewed' => $contactsViewedCount,
            'contact_view_limit' => $totalViewContactLimit,
            'contact_view_balance' => $remainingBalance,
             'usage_percentage' => $contactViewUsagePercentage,
            'chats_initiated' => 0,
        ],
        'user' =>  [
            'id' => $user->id,
            'name' => $user->name,
            'profile_picture' => $user->profile_picture,
            'country' => optional($user->profile)->country,
            'state' => optional($user->profile)->state,
            'city' => optional($user->profile)->city,
            'subscription' =>  $user->activeSubscription
            ? new \App\Http\Resources\SubscriptionResource($user->activeSubscription)
            : null,
        ],
    ]);
}


    public function recentStatsOverview()
    {
        $user = Auth::user();

        // 1. Recent received connection requests (latest 10)
        $receivedRequests = \App\Models\UserConnection::with('sender.profile')
            ->where('connected_user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'sender_id' => $req->user_id,
                    'name' => optional($req->sender)->name,
                    'age' => optional($req->sender)->age,
                    'country' => optional($req->sender->profile)->country,
                    'state' => optional($req->sender->profile)->state,
                    'city' => optional($req->sender->profile)->city,
                    'marital_status' => optional($req->sender)->marital_status,
                    'profile_picture' => optional($req->sender)->profile_picture,
                    'sent_at' => $req->created_at->diffForHumans(),
                ];
            });

        // 2. Recent profile visitors (latest 6 distinct visitors)
        $visitorIds = \App\Models\ProfileVisit::where('visited_id', $user->id)
            ->latest()
            ->pluck('visitor_id')
            ->unique()
            ->take(6);

        $visitors = \App\Models\User::with('profile')
            ->whereIn('id', $visitorIds)
            ->get()
            ->map(function ($visitor,) use ($user) {


                $connectionRequestStatus = null;
                $connection = UserConnection::where('user_id', $user->id)
                ->where('connected_user_id', $visitor->id)
                ->first();
                $connectionRequestStatus = $connection ? $connection->status : null;




                return [
                    'visitor_id' => $visitor->id,
                    'name' => $visitor->name,
                    'age' => optional($visitor)->age,
                    'country' => optional($visitor->profile)->country,
                    'state' => optional($visitor->profile)->state,
                    'city' => optional($visitor->profile)->city,
                    'marital_status' => optional($visitor)->marital_status,
                    'profile_picture' => optional($visitor)->profile_picture,
                    'visited_at' => optional($visitor->profileVisit)?->created_at?->diffForHumans(), // optional if you later build visit relationship
                    'connection_request_Status' => $connectionRequestStatus,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Recent data fetched successfully',
            'recent_received_requests' => $receivedRequests,
            'recent_visitors' => $visitors,
        ]);
    }





}
