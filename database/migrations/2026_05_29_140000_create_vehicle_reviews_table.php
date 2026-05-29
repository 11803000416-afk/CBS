<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->enum('status', ['published', 'pending', 'hidden'])->default('published');
            $table->timestamps();

            $table->unique('transaction_id');
            $table->index(['vehicle_id', 'status']);
            $table->index(['seller_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_reviews');
    }
};