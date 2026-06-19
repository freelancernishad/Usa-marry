<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = ['user_id', 'phone', 'message', 'status', 'error_message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
