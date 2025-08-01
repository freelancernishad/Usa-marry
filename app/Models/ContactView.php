<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contact_user_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact()
    {
        return $this->belongsTo(User::class, 'contact_user_id'); // assuming contacts are also users
    }
}
