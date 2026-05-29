<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('offer_amount', 10, 2);
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->timestamps();
            
            $table->index(['vehicle_id', 'buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
