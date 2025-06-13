<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Feature;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',  // Store plan_id instead of plan_name
        'start_date',
        'end_date',
        'original_amount',
        'final_amount',
        'coupon_code',
        'discount_amount',
        'discount_percent',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'plan_features',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'plan_features' => 'array',
    ];

    // Automatically append formatted_plan_features to JSON responses
    protected $appends = ['formatted_plan_features'];
    protected $hidden = ['plan_features'];

    /**
     * Relationship to the Plan model
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Relationship to the User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to return formatted plan features
     *
     * @return array
     */
    public function getFormattedPlanFeaturesAttribute()
    {
        if (empty($this->plan_features) || !is_array($this->plan_features)) {
            return [];
        }

        return collect($this->plan_features)->map(function ($item) {
            $feature = Feature::where('key', $item['key'])->first();

            if (!$feature) {
                return $item['value'] ?? '';
            }

            $data = $item;
            unset($data['key']);

            return $feature->render($data);
        })->filter()->all();
    }
}
