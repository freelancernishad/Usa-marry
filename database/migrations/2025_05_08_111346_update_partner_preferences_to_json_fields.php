<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePartnerPreferencesToJsonFields extends Migration
{
    public function up()
    {
        Schema::table('partner_preferences', function (Blueprint $table) {
            DB::statement('UPDATE partner_preferences SET marital_status = JSON_ARRAY(marital_status) WHERE marital_status IS NOT NULL AND JSON_VALID(marital_status) = 0');
            DB::statement('UPDATE partner_preferences SET religion = JSON_ARRAY(religion) WHERE religion IS NOT NULL AND JSON_VALID(religion) = 0');
            DB::statement('UPDATE partner_preferences SET caste = JSON_ARRAY(caste) WHERE caste IS NOT NULL AND JSON_VALID(caste) = 0');
            DB::statement('UPDATE partner_preferences SET education = JSON_ARRAY(education) WHERE education IS NOT NULL AND JSON_VALID(education) = 0');
            DB::statement('UPDATE partner_preferences SET occupation = JSON_ARRAY(occupation) WHERE occupation IS NOT NULL AND JSON_VALID(occupation) = 0');
            DB::statement('UPDATE partner_preferences SET country = JSON_ARRAY(country) WHERE country IS NOT NULL AND JSON_VALID(country) = 0');

            $table->json('marital_status')->nullable()->change();
            $table->json('religion')->nullable()->change();
            $table->json('caste')->nullable()->change();
            $table->json('education')->nullable()->change();
            $table->json('occupation')->nullable()->change();
            $table->json('country')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('partner_preferences', function (Blueprint $table) {
            $table->string('marital_status', 255)->nullable()->change();
            $table->string('religion', 255)->nullable()->change();
            $table->string('caste', 255)->nullable()->change();
            $table->string('education', 255)->nullable()->change();
            $table->string('occupation', 255)->nullable()->change();
            $table->string('country', 255)->nullable()->change();
        });
    }
}
