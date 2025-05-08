<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age_min',
        'age_max',
        'height_min',
        'height_max',
        'marital_status',
        'religion',
        'caste',
        'education',
        'occupation',
        'country',
    ];

    protected $casts = [
        'marital_status' => 'array',
        'religion' => 'array',
        'caste' => 'array',
        'education' => 'array',
        'occupation' => 'array',
        'country' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
