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

        Schema::create('admin_users_login_log', function (Blueprint $table) {
            $table->id();
            $table->string('email_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('attempt_status')->nullable();
            $table->string('referrer')->nullable();
            $table->string('url')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('unlock_timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users_login_log');
    }
};
