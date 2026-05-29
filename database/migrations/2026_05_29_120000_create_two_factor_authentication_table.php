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
        Schema::create('two_factor_authentication', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('two_factor_secret')->unique()->nullable();
            $table->string('two_factor_recovery_codes')->nullable(); // JSON array of backup codes
            $table->boolean('two_factor_confirmed')->default(false);
            $table->timestamp('two_factor_enabled_at')->nullable();
            $table->timestamp('two_factor_disabled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('two_factor_authentication');
    }
};
