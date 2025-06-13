<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::create('profile_visits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('visitor_id'); // The user who visited
                $table->unsignedBigInteger('visited_id'); // The profile being viewed
                $table->timestamps();

                $table->foreign('visitor_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('visited_id')->references('id')->on('users')->onDelete('cascade');
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_visits');
    }
};
