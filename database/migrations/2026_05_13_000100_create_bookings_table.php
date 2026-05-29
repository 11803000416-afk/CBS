<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->integer('duration_hours')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->text('buyer_message')->nullable();
            $table->text('seller_message')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['vehicle_id', 'status']);
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
