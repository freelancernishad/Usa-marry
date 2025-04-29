<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBloodGroupAndDisabilityIssueToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('blood_group')->nullable()->after('height');
            $table->string('disability_issue')->nullable()->after('disability');
            $table->string('family_location')->nullable()->after('mother_tongue');
            $table->string('grew_up_in')->nullable()->after('family_location');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['blood_group', 'disability_issue', 'family_location', 'grew_up_in']);
        });
    }
}
