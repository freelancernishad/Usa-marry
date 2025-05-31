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
    Schema::create('user_connections', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('connected_user_id')->constrained('users')->onDelete('cascade');
        $table->enum('status', [
            'pending',
            'accepted',
            'disconnected',
            'blocked',
            'rejected',
            'cancelled'
        ])->default('pending');
        $table->timestamps();

        $table->unique(['user_id', 'connected_user_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_connections');
    }
};
