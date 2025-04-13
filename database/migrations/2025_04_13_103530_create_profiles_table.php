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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('about')->nullable();

            // Education & Career
            $table->string('highest_degree')->nullable();
            $table->string('institution')->nullable();
            $table->string('occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('employed_in')->nullable();

            // Family
            $table->string('father_status')->nullable();
            $table->string('mother_status')->nullable();
            $table->integer('siblings')->nullable();
            $table->string('family_type')->nullable();
            $table->string('family_values')->nullable();
            $table->string('financial_status')->nullable();

            // Lifestyle
            $table->string('diet')->nullable();
            $table->string('drink')->nullable();
            $table->string('smoke')->nullable();

            // Location
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('resident_status')->nullable();

            // Horoscope
            $table->boolean('has_horoscope')->default(false);
            $table->string('rashi')->nullable();
            $table->string('nakshatra')->nullable();
            $table->string('manglik')->nullable();

            // Privacy
            $table->boolean('show_contact')->default(false);
            $table->string('visible_to')->default('All');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
