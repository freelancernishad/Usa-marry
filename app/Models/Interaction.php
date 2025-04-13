<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'type',
        'content',
    ];

    public function match()
    {
        return $this->belongsTo(UserMatch::class, 'match_id');
    }
}
