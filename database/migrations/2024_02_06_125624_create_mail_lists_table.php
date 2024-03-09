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
        Schema::create('mail_lists', function (Blueprint $table) {
            $table->id();
            $table->longText('send_to');
            $table->longText('sender_username');
            $table->longText('send_cc')->nullable();
            $table->longText('send_bcc')->nullable();
            $table->string('send_title');
            $table->longText('send_body');
            $table->string('send_file')->nullable();
            $table->dateTime('send_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_lists');
    }
};
