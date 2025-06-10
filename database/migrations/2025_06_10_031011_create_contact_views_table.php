<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactViewsTable extends Migration
{
    public function up(): void
    {
        Schema::create('contact_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')              // The viewer
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('contact_user_id')      // The user whose contact was viewed
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'contact_user_id']); // Optional: prevent duplicate views
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_views');
    }
}
