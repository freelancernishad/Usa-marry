<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('photo_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');   // requester
            $table->unsignedBigInteger('receiver_id'); // requested person
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('photo_requests');
    }
}
