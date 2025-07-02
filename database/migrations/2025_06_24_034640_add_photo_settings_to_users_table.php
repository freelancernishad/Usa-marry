<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoSettingsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('photo_privacy', ['all', 'premium', 'accepted'])->default('all');
            $table->enum('photo_visibility', ['all', 'profile_only', 'hidden'])->default('all');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['photo_privacy', 'photo_visibility']);
        });
    }
}
