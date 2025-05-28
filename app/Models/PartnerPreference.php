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
        'family_type',
        'state',
        'city',
        'mother_tongue',
    ];

    protected $casts = [
        'marital_status' => 'array',
        'religion' => 'array',
        'caste' => 'array',
        'education' => 'array',
        'occupation' => 'array',
        'country' => 'array',
        'family_type' => 'array',
        'state' => 'array',
        'city' => 'array',
        'mother_tongue' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
