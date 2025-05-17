<?php

namespace App\Models;

use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'dob',
        'religion',
        'caste',
        'sub_caste',
        'marital_status',
        'height',

        'blood_group',
        'disability_issue',
        'family_location',
        'grew_up_in',
        'hobbies',

        'disability',
        'mother_tongue',
        'profile_created_by',
        'verified',
        'profile_completion',
        'account_status',
        'email_verified_at',
        'email_verification_hash',
        'otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_hash',
        'email_verified_at',
        'otp',
        'otp_expires_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
        'disability' => 'boolean',
        'verified' => 'boolean',
        'hobbies' => 'array',
    ];

    protected $appends = ['age', 'profile_picture'];

    public function getProfilePictureAttribute()
    {
        return $this->primaryPhoto ? $this->primaryPhoto->path : null;
    }


    public function getAgeAttribute()
    {
        if (!$this->dob) {
            return null;
        }

        $dob = Carbon::parse($this->dob);
        $now = Carbon::now();
        $diff = $dob->diff($now);

        return "{$diff->y} years, {$diff->m} months, {$diff->d} days";
    }




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => !is_null($this->email_verified_at),
        ];
    }    public function profile()
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

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'Success')
            ->where('end_date', '>=', now());
    }
}
