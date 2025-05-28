<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partner_preferences', function (Blueprint $table) {
            $table->json('family_type')->nullable();
            $table->json('state')->nullable();
            $table->json('city')->nullable();
            $table->json('mother_tongue')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_preferences', function (Blueprint $table) {
               $table->dropColumn(['family_type', 'state', 'city', 'mother_tongue']);
        });
    }
};
