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
        Schema::create('partner_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();
            $table->decimal('height_min', 5, 2)->nullable();
            $table->decimal('height_max', 5, 2)->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_preferences');
    }
};
