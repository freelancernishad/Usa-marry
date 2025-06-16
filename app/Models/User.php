<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\NotificationHelper;
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

    public function profileVisit()
    {
        return $this->hasOne(ProfileVisit::class, 'visitor_id')->latestOfMany();
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



        // Relationship for connections initiated by this user
    public function connections()
    {
        return $this->hasMany(UserConnection::class, 'user_id'); // 'user_id' is the foreign key in the UserConnection model
    }

    // Relationship for connections that have been received by this user
    public function connectedUsers()
    {
        return $this->hasMany(UserConnection::class, 'connected_user_id'); // 'connected_user_id' is the foreign key in the UserConnection model
    }

public function connectWithUser($connectedUserId)
{
    if ($this->id == $connectedUserId) {
        return response()->json(['message' => 'You cannot send a connection request to yourself.'], 400);
    }

    $connectedUser = User::find($connectedUserId);
    if (!$connectedUser) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    $existingConnection = $this->connections()
                               ->where('connected_user_id', $connectedUserId)
                               ->first();

    if ($existingConnection) {
        switch ($existingConnection->status) {
            case 'pending':
                return response()->json(['message' => 'Connection request is already pending.'], 400);

            case 'accepted':
                return response()->json(['message' => 'You are already connected.'], 400);

            case 'disconnected':
                $existingConnection->status = 'pending';
                $existingConnection->save();

                // Notify both users
                NotificationHelper::sendUserNotification(
                    $connectedUser,
                    "{$this->name} has sent you a connection request again.",
                    'Connection Request Re-sent',
                    'User',
                    $this->id
                );

                NotificationHelper::sendUserNotification(
                    $this,
                    "You have re-sent a connection request to {$connectedUser->name}.",
                    'Connection Request Re-sent',
                    'User',
                    $connectedUser->id
                );

                return response()->json(['message' => 'Connection request has been re-sent.'], 200);

            case 'blocked':
                return response()->json(['message' => 'You have blocked this user or have been blocked.'], 400);

            case 'rejected':
                $existingConnection->status = 'pending';
                $existingConnection->save();

                NotificationHelper::sendUserNotification(
                    $connectedUser,
                    "{$this->name} has sent you a connection request after rejection.",
                    'Connection Request Re-sent',
                    'User',
                    $this->id
                );

                NotificationHelper::sendUserNotification(
                    $this,
                    "You have re-sent a connection request to {$connectedUser->name} after rejection.",
                    'Connection Request Re-sent',
                    'User',
                    $connectedUser->id
                );

                return response()->json(['message' => 'Connection request has been re-sent after rejection.'], 200);

            case 'cancelled':
                $existingConnection->status = 'pending';
                $existingConnection->save();

                NotificationHelper::sendUserNotification(
                    $connectedUser,
                    "{$this->name} has sent you a connection request after cancellation.",
                    'Connection Request Re-sent',
                    'User',
                    $this->id
                );

                NotificationHelper::sendUserNotification(
                    $this,
                    "You have re-sent a connection request to {$connectedUser->name} after cancellation.",
                    'Connection Request Re-sent',
                    'User',
                    $connectedUser->id
                );

                return response()->json(['message' => 'Connection request has been re-sent after cancellation.'], 200);

            default:
                return response()->json(['message' => 'Unknown connection status.'], 400);
        }
    }

    // Create new connection request
    $this->connections()->create([
        'connected_user_id' => $connectedUserId,
        'status' => 'pending',
    ]);

    // Notify receiver
    NotificationHelper::sendUserNotification(
        $connectedUser,
        "{$this->name} has sent you a connection request.",
        'New Connection Request',
        'User',
        $this->id
    );

    // Notify sender
    NotificationHelper::sendUserNotification(
        $this,
        "You have sent a connection request to {$connectedUser->name}.",
        'Connection Request Sent',
        'User',
        $connectedUser->id
    );

    return response()->json(['message' => 'Connection request sent successfully.'], 201);
}


   public function disconnectFromUser($connectedUserId)
{
    $connection = $this->connections()
        ->where('connected_user_id', $connectedUserId)
        ->first();

    if ($connection) {
        $connection->status = 'disconnected';
        $connection->save();

        $otherUser = User::find($connectedUserId);
        if ($otherUser) {
            // Notify the other user
            NotificationHelper::sendUserNotification(
                $otherUser,
                "{$this->name} has disconnected from you.",
                'Connection Disconnected',
                'User',
                $this->id
            );

            // Notify yourself
            NotificationHelper::sendUserNotification(
                $this,
                "You have disconnected from {$otherUser->name}.",
                'Connection Disconnected',
                'User',
                $otherUser->id
            );
        }

        return $connection;
    }

    return null;
}


   public function rejectConnectionRequest($UserId)
{
    $connection = $this->connections()
        ->where('user_id', $UserId)
        ->first();

    if ($connection) {
        $connection->status = 'rejected';
        $connection->save();

        $otherUser = User::find($UserId);
        if ($otherUser) {
            // Notify the other user
            NotificationHelper::sendUserNotification(
                $otherUser,
                "{$this->name} has rejected your connection request.",
                'Connection Rejected',
                'User',
                $this->id
            );

            // Notify yourself
            NotificationHelper::sendUserNotification(
                $this,
                "You have rejected the connection request from {$otherUser->name}.",
                'Connection Rejected',
                'User',
                $otherUser->id
            );
        }

        return $connection;
    }

    return null;
}



    // Get the list of all accepted connections for this user
    public function getConnections()
    {
        // Get all accepted connections where the current user is either the sender or recipient
        return UserConnection::where(function ($query) {
                $query->where('user_id', $this->id)
                    ->orWhere('connected_user_id', $this->id);
            })
            ->where('status', 'accepted')
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($connection) {
                // Determine the other user in the connection
                if ($connection->user_id == $this->id) {
                    $matchedUser = $connection->receiver;
                } else {
                    $matchedUser = $connection->sender;
                }
                // Use UserResource for connection_user
                $connection->connection_user = new \App\Http\Resources\UserResource($matchedUser);
                // Remove sender and receiver from the result
                unset($connection->sender, $connection->receiver);
                return $connection;
            });
    }


    // Get the list of all pending connections (requests sent or received) for this user
// app/Models/User.php
public function getPendingConnections()
{
    return UserConnection::where(function ($query) {
            $query->where('user_id', $this->id);
                // ->orWhere('connected_user_id', $this->id);
        })
        ->where('status', 'pending')
        ->with(['sender.profile', 'receiver.profile']) // Eager load nested profile
        ->get()
        ->map(function ($connection) {
            // Determine the other user in the connection
            $matchedUser = $connection->user_id == $this->id
                ? $connection->receiver
                : $connection->sender;

       

            $connection->connection_user = [
                'id' => $matchedUser->id ?? null,
                'name' => $matchedUser->name ?? '',
                'profile_picture' => $matchedUser->profile_picture ?? '',
                'age' => $matchedUser->age ?? '',
                'height' => $matchedUser->height ?? '',
                'caste' => $matchedUser->caste ?? '',
                'religion' => $matchedUser->religion ?? '',
                'highest_degree' => $matchedUser->profile->highest_degree ?? '',
                'occupation' => $matchedUser->profile->occupation ?? '',
            ];

            // Remove full sender and receiver objects
            unset($connection->sender, $connection->receiver);

            return $connection;
        });
}





    // Get the list of all pending connections that this user has received (connection requests received)
public function getPendingConnectionsForMe()
{
    // Get all pending connections where the current user is the recipient (connected_user_id)
    return UserConnection::where('connected_user_id', $this->id)  // Current user is the recipient
        ->where('status', 'pending')  // Only get pending connections
        ->with(['sender'])  // Eager load sender details (the user who sent the request)
        ->get()
        ->map(function ($connection) {
            // Format the connection, add role as "receiver" since this is for the received request
            $connection->role = 'receiver';  // Current user is the receiver
            $connection->connection_user = new \App\Http\Resources\UserResource($connection->sender);

            // Remove sender from the result to avoid redundancy
            unset($connection->sender);
            return $connection;
        });
}






    // Get the list of all users who have connected with this user (accepted connections)
public function getUsersWhoConnectedWithMe()
{
    return UserConnection::where('connected_user_id', $this->id)  // Current user is the recipient
        ->where('status', 'accepted')  // Only get accepted connections
        ->with(['sender'])  // Eager load sender details (the user who sent the request)
        ->get()
        ->map(function ($connection) {
            // Format the connection, add role as "receiver" since this is for the received connection
            $connection->role = 'receiver';  // Current user is the receiver
            $connection->connection_user = new \App\Http\Resources\UserResource($connection->sender);

            // Remove sender from the result to avoid redundancy
            unset($connection->sender);
            return $connection;
        });
}


public function getMySentAcceptedConnections()
{
    // Get all accepted connections where the current user is the sender
    return UserConnection::where('user_id', $this->id)  // Current user is the sender
        ->where('status', 'accepted')  // Only get accepted connections
        ->with(['receiver'])  // Eager load receiver details (the user who accepted the request)
        ->get()
        ->map(function ($connection) {
            // Format the connection, add role as "sender" since this is for the sent connection
            $connection->role = 'sender';  // Current user is the sender
            $connection->connection_user = new \App\Http\Resources\UserResource($connection->receiver);

            // Remove receiver from the result to avoid redundancy
            unset($connection->receiver);
            return $connection;
        });
}




        // Get the list of all disconnected users
public function getDisconnectedUsers()
{
    return UserConnection::where(function ($query) {
            $query->where('user_id', $this->id)  // Current user is the sender
                ->orWhere('connected_user_id', $this->id); // Current user is the recipient
        })
        ->where('status', 'disconnected')  // Only get disconnected connections
        ->with(['sender', 'receiver'])  // Eager load sender and receiver details
        ->get()
        ->map(function ($connection) {
            // Determine the other user in the connection
            if ($connection->user_id == $this->id) {
                $matchedUser = $connection->receiver;
            } else {
                $matchedUser = $connection->sender;
            }

            // Use UserResource for connection_user to format the matched user
            $connection->connection_user = new \App\Http\Resources\UserResource($matchedUser);

            // Remove sender and receiver from the result
            unset($connection->sender, $connection->receiver);
            return $connection;
        });
}



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
        $subscription = $this->activeSubscription()->with('plan')->first();

        if (!$subscription || !$subscription->plan) {
            return null;
        }

        $feature = collect($subscription->plan->features)
            ->firstWhere('key', $key);

        return $feature['value'] ?? null;
    }



}
