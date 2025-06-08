<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'original_price',
        'discounted_price',
        'monthly_price',
        'discount_percentage',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($plan) {
            self::calculatePrices($plan);
        });

        static::updating(function ($plan) {
            self::calculatePrices($plan);
        });
    }

    protected static function calculatePrices($plan)
    {
        // duration থেকে মাস সংখ্যা বের করা
        $numericDuration = self::parseDuration($plan->duration);

        // যদি original_price না থাকে তবে monthly_price * duration
        if (is_null($plan->original_price) && is_numeric($plan->monthly_price) && is_numeric($numericDuration)) {
            $plan->original_price = round($plan->monthly_price * $numericDuration, 2);
        }

        // যদি monthly_price না থাকে তবে original_price / duration
        if (is_null($plan->monthly_price) && is_numeric($plan->original_price) && is_numeric($numericDuration) && $numericDuration > 0) {
            $plan->monthly_price = round($plan->original_price / $numericDuration, 2);
        }

        // যদি discount_percentage দেওয়া থাকে তবে discounted_price হিসাব করো
        if (is_numeric($plan->original_price) && is_numeric($plan->discount_percentage)) {
            $plan->discounted_price = round($plan->original_price * (1 - $plan->discount_percentage / 100), 2);
        }

        // যদি discounted_price না থাকে তবে 0
        if (!is_numeric($plan->discounted_price)) {
            $plan->discounted_price = 0;
        }

        // duration clean করে months সহ রাখো (যদি সংখ্যা পাওয়া যায়)
        $plan->duration = is_numeric($numericDuration) ? "{$numericDuration} months" : $plan->duration;
    }

    // duration থেকে সংখ্যা আলাদা করা (e.g. "3 months" => 3)
    protected static function parseDuration($duration)
    {
        if (is_numeric($duration)) {
            return (int) $duration;
        }

        if (is_string($duration) && preg_match('/(\d+)/', $duration, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
