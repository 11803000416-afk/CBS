<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->string('code', 10);
            $table->enum('sent_to', ['buyer', 'seller', 'both'])->default('both');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_otps');
    }
};
