<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'gender', 'dob', 'religion', 'caste', 'sub_caste',
        'marital_status', 'height', 'blood_group', 'disability_issue', 'family_location',
        'grew_up_in', 'hobbies', 'disability', 'mother_tongue', 'profile_created_by',
        'verified', 'profile_completion', 'account_status', 'email_verified_at',
        'email_verification_hash', 'otp', 'otp_expires_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verification_hash',
        'email_verified_at', 'otp', 'otp_expires_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
        'disability' => 'boolean',
        'verified' => 'boolean',
        'hobbies' => 'array',
    ];

    protected $appends = [
        'age', 'profile_picture', 'match_percentage', 'plan_name',
    ];

    // Cache property for age attribute
    protected $ageCache;

    // ----------------------------
    // JWT Methods
    // ----------------------------

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => !is_null($this->email_verified_at),
        ];
    }

    // ----------------------------
    // Accessors
    // ----------------------------

    public function getPlanNameAttribute()
    {
        $subscription = $this->activeSubscription;

        if ($subscription && $subscription->relationLoaded('plan') && $subscription->plan) {
            return $subscription->plan->name;
        } elseif ($subscription && $subscription->plan) {
            return $subscription->plan->name;
        }

        return 'Free';
    }

    public function getMatchPercentageAttribute()
    {
        $authUser = Auth::user();

        if ($authUser && $authUser->id !== $this->id) {
            return calculateMatchPercentage($authUser, $this);
        }

        return null;
    }

    // public function getProfilePictureAttribute()
    // {
    //     return $this->primaryPhoto ? $this->primaryPhoto->path : null;
    // }



    public function getProfilePictureAttribute()
    {
        $authUser = Auth::user();

        if (!$authUser || $authUser->id === $this->id) {
            return $this->primaryPhoto?->path;
        }

        if ($this->hasAcceptedPhotoRequestWith($authUser)) {
            return $this->primaryPhoto?->path;
        }

        return 'Locked';
    }

    public function getAgeAttribute()
    {
        if ($this->ageCache !== null) {
            return $this->ageCache;
        }

        if (!$this->dob) {
            return null;
        }

        $dob = Carbon::parse($this->dob);
        $now = Carbon::now();
        $diff = $dob->diff($now);

        return $this->ageCache = "{$diff->y} years, {$diff->m} months, {$diff->d} days";
    }

    public function getContactViewBalanceAttribute()
    {
        $subscription = $this->activeSubscription;

        if (!$subscription || !is_array($subscription->plan_features)) {
            return 0;
        }

        $viewContactFeature = collect($subscription->plan_features)
            ->firstWhere('key', 'view_contact');

        $allowed = isset($viewContactFeature['value']) ? (int) $viewContactFeature['value'] : 0;
        $used = \App\Models\ContactView::where('user_id', $this->id)->count();

        return max(0, $allowed - $used);
    }

    // ----------------------------
    // Relationships
    // ----------------------------

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function partnerPreference()
    {
        return $this->hasOne(PartnerPreference::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(Photo::class)->where('is_primary', true);
    }

    public function profileVisit()
    {
        return $this->hasOne(ProfileVisit::class, 'visitor_id')->latestOfMany();
    }

    public function matches()
    {
        return $this->hasMany(UserMatch::class, 'user_id');
    }

    public function matchedUsers()
    {
        return $this->hasManyThrough(
            User::class,
            UserMatch::class,
            'user_id',
            'id',
            'id',
            'matched_user_id'
        );
    }

    public function reverseMatches()
    {
        return $this->hasMany(UserMatch::class, 'matched_user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Eager load plan with activeSubscription to optimize queries
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'Success')
            ->where('end_date', '>=', now())
            ->with('plan');  // eager load plan relationship here
    }

    public function connections()
    {
        return $this->hasMany(UserConnection::class, 'user_id');
    }

    public function connectedUsers()
    {
        return $this->hasMany(UserConnection::class, 'connected_user_id');
    }

    // ----------------------------
    // Subscription Feature Helpers
    // ----------------------------

    public function isSubscribedToPlan($planId)
    {
        return $this->subscriptions()
            ->where('plan_id', $planId)
            ->where('status', 'active')
            ->exists();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription()->exists();
    }

    public function getFeatureLimit(string $key): ?int
    {
        $subscription = $this->activeSubscription()->first();

        if (!$subscription || !$subscription->relationLoaded('plan') || !$subscription->plan) {
            return null;
        }

        $feature = collect($subscription->plan->features)
            ->firstWhere('key', $key);

        return $feature['value'] ?? null;
    }


    public function sentPhotoRequests()
    {
        return $this->hasMany(PhotoRequest::class, 'sender_id');
    }

    public function receivedPhotoRequests()
    {
        return $this->hasMany(PhotoRequest::class, 'receiver_id');
    }

    public function hasAcceptedPhotoRequestWith(User $otherUser): bool
    {
        return PhotoRequest::where(function ($q) use ($otherUser) {
            $q->where('sender_id', $this->id)
            ->where('receiver_id', $otherUser->id);
        })->orWhere(function ($q) use ($otherUser) {
            $q->where('sender_id', $otherUser->id)
            ->where('receiver_id', $this->id);
        })->where('status', 'accepted')->exists();
    }


}
