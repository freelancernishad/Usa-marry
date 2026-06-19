<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = ['user_id', 'email', 'subject', 'message', 'status', 'error_message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
