<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansSeeder extends Seeder
{
    public function run()
    {
        Plan::create([
            'name' => 'Gold',
            'duration' => '3 Months',
            'original_price' => 8350,
            'discounted_price' => 2505,
            'monthly_price' => 835,
            'discount_percentage' => 70,
            'features' => [
                ['key' => 'send_messages', 'value' => 'unlimited'],
                ['key' => 'view_contact', 'value' => 75],
                ['key' => 'live_passes', 'value' => 5, 'amount' => 2750],
                ['key' => 'standout_profile', 'value' => true],
                ['key' => 'direct_contact', 'value' => true],
            ],
        ]);

        Plan::create([
            'name' => 'Gold Plus',
            'duration' => '3 Months',
            'original_price' => 10950,
            'discounted_price' => 3285,
            'monthly_price' => 1095,
            'discount_percentage' => 70,
            'features' => [
                ['key' => 'send_messages', 'value' => 'unlimited'],
                ['key' => 'view_contact', 'value' => 150],
                ['key' => 'live_passes', 'value' => 6, 'amount' => 3300],
                ['key' => 'standout_profile', 'value' => true],
                ['key' => 'direct_contact', 'value' => true],
                ['key' => 'custom', 'value' => 'Featured profile on top search results'],
                ['key' => 'custom', 'value' => 'Priority customer support'],
            ],
        ]);

        Plan::create([
            'name' => 'Diamond',
            'duration' => '6 Months',
            'original_price' => 12500,
            'discounted_price' => 3750,
            'monthly_price' => 625,
            'discount_percentage' => 70,
            'features' => [
                ['key' => 'send_messages', 'value' => 'unlimited'],
                ['key' => 'view_contact', 'value' => 150],
                ['key' => 'live_passes', 'value' => 8, 'amount' => 4400],
                ['key' => 'standout_profile', 'value' => true],
                ['key' => 'direct_contact', 'value' => true],
                ['key' => 'custom', 'value' => 'Dedicated profile manager'],
                ['key' => 'custom', 'value' => '24/7 customer support'],
            ],
        ]);

        Plan::create([
            'name' => 'Diamond Plus',
            'duration' => '6 Months',
            'original_price' => 16400,
            'discounted_price' => 5576,
            'monthly_price' => 929,
            'discount_percentage' => 66,
            'features' => [
                ['key' => 'send_messages', 'value' => 'unlimited'],
                ['key' => 'view_contact', 'value' => 300],
                ['key' => 'live_passes', 'value' => 9, 'amount' => 4950],
                ['key' => 'standout_profile', 'value' => true],
                ['key' => 'direct_contact', 'value' => true],
                ['key' => 'custom', 'value' => 'Profile highlighted in search results'],
                ['key' => 'custom', 'value' => 'Personal matchmaking consultation'],
                ['key' => 'custom', 'value' => 'Priority customer service'],
            ],
        ]);

        Plan::create([
            'name' => 'Platinum Plus',
            'duration' => '12 Months',
            'original_price' => 23500,
            'discounted_price' => 9400,
            'monthly_price' => 783,
            'discount_percentage' => 60,
            'features' => [
                ['key' => 'send_messages', 'value' => 'unlimited'],
                ['key' => 'view_contact', 'value' => 600],
                ['key' => 'live_passes', 'value' => 15, 'amount' => 8250],
                ['key' => 'standout_profile', 'value' => true],
                ['key' => 'direct_contact', 'value' => true],
                ['key' => 'custom', 'value' => 'Featured profile on all major search pages'],
                ['key' => 'custom', 'value' => 'Exclusive profile verification badge'],
                ['key' => 'custom', 'value' => 'Premium matchmaking consultations'],
                ['key' => 'custom', 'value' => 'Dedicated relationship manager'],
                ['key' => 'custom', 'value' => '24/7 customer support'],
            ],
        ]);
    }
}
