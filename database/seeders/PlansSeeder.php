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
                'Send unlimited messages',
                'View up to 75 contact numbers',
                '5 Shaadi Live passes worth BDT 2,750',
                'Stand out from other profiles',
                'Let matches contact you directly'
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
                'Send unlimited messages',
                'View up to 150 contact numbers',
                '6 Shaadi Live passes worth BDT 3,300',
                'Stand out from other profiles',
                'Let matches contact you directly',
                'Featured profile on top search results',
                'Priority customer support'
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
                'Send unlimited messages',
                'View up to 150 contact numbers',
                '8 Shaadi Live passes worth BDT 4,400',
                'Stand out from other profiles',
                'Let matches contact you directly',
                'Dedicated profile manager',
                '24/7 customer support'
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
                'Send unlimited messages',
                'View up to 300 contact numbers',
                '9 Shaadi Live passes worth BDT 4,950',
                'Stand out from other profiles',
                'Let matches contact you directly',
                'Profile highlighted in search results',
                'Personal matchmaking consultation',
                'Priority customer service'
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
                'Send unlimited messages',
                'View up to 600 contact numbers',
                '15 Shaadi Live passes worth BDT 8,250',
                'Stand out from other profiles',
                'Let matches contact you directly',
                'Featured profile on all major search pages',
                'Exclusive profile verification badge',
                'Premium matchmaking consultations',
                'Dedicated relationship manager',
                '24/7 customer support'
            ],
        ]);
    }
}
