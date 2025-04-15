make a RegistrationControler and here make some step to registraion =>
account signup which have Name,email or phone ,password,confirm password
then profile for which have profile_created_by , gender
then personal informations which have name, dob , religion, community ,  country , email and phone
then create your profile which have city ,resident_status, marital_status ,height , diet
then just few questions about educaiton and career which have highest_degree, institution , occupation , annual_income
then add a few description about that person which have about

--- give me best proccess for complete this and update profile_completion

this is my exting function update profile completion also update this function to make it more efficient and add new sections or make dynamic

 function updateProfileCompletion(User $user, $section)
    {
        $completion = $user->profile_completion;

        // Define completion percentages for each section
        $sections = [
            'basic_info' => 30,
            'profile' => 40,
            'photos' => 20,
            'partner_preference' => 10
        ];

        if (!isset($sections[$section])) {
            return;
        }

        // Only add if not already completed
        if (($completion & $sections[$section]) === 0) {
            $user->profile_completion += $sections[$section];
            $user->save();
        }
    }


    this is user model
    <?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
    ];


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


this is profile model

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'about',
        'highest_degree',
        'institution',
        'occupation',
        'annual_income',
        'employed_in',
        'father_status',
        'mother_status',
        'siblings',
        'family_type',
        'family_values',
        'financial_status',
        'diet',
        'drink',
        'smoke',
        'country',
        'state',
        'city',
        'resident_status',
        'has_horoscope',
        'rashi',
        'nakshatra',
        'manglik',
        'show_contact',
        'visible_to',
    ];

    protected $casts = [
        'has_horoscope' => 'boolean',
        'show_contact' => 'boolean',
        'siblings' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
