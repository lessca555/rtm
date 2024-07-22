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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id');
            $table->unsignedBigInteger('sender_id'); //bisa juga menggunakan uuid
            $table->foreign('sender_id')->references('id')->on('users');

            $table->unsignedBigInteger('recaiver_id');
            $table->foreign('recaiver_id')->references('id')->on('users');

            //kapan dibaca
            $table->timestamp('read_at')->nullable();

            //kapan chat dihapus
            $table->timestamp('recaiver_deleted_at')->nullable();
            $table->timestamp('sender_deleted_at')->nullable();

            $table->text('body')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
