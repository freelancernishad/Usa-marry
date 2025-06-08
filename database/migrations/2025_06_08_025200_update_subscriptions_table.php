<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Remove the plan_name column
            $table->dropColumn('plan_name');

            // Add the plan_id foreign key column as nullable first
            $table->unsignedBigInteger('plan_id')->nullable()->after('id');
        });

        // Update existing subscriptions with a valid plan_id (adjust as needed)
        // Example: set all to 1, make sure a plan with id=1 exists
        DB::table('subscriptions')->update(['plan_id' => 1]);

        // Now make plan_id not nullable and add the foreign key constraint
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable(false)->change();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Revert the changes (in case of rollback)
            $table->string('plan_name');
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
    }
}
