<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponDiscountColumnsToSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->decimal('original_amount', 8, 2)->nullable()->after('end_date');
            $table->decimal('final_amount', 8, 2)->nullable()->after('original_amount');
            $table->string('coupon_code')->nullable()->after('final_amount');
            $table->decimal('discount_amount', 8, 2)->default(0)->after('coupon_code');
            $table->decimal('discount_percent', 5, 2)->default(0)->after('discount_amount');
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'original_amount',
                'final_amount',
                'coupon_code',
                'discount_amount',
                'discount_percent'
            ]);
        });
    }
}
