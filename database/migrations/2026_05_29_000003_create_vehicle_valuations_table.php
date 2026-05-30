<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicle_valuations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->decimal('estimated_value', 12, 2);
            $table->decimal('base_price', 12, 2);
            $table->decimal('depreciation_adjustment', 12, 2)->default(0);
            $table->decimal('mileage_adjustment', 12, 2)->default(0);
            $table->decimal('age_adjustment', 12, 2)->default(0);
            $table->integer('confidence_score')->default(85); // 0-100
            $table->text('valuation_factors')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->index('estimated_value');
        });
    }

    public function down(): void {
        Schema::dropIfExists('vehicle_valuations');
    }
};
