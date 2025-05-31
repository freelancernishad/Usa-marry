<?php

// app/Models/UserConnection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConnection extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'connected_user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function connectedUser()
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }


        // Relationship to the sender (user who initiated the request)
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id'); // Foreign key is 'user_id'
    }

    // Relationship to the receiver (user who received the request)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'connected_user_id'); // Foreign key is 'connected_user_id'
    }

}

