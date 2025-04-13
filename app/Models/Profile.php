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
