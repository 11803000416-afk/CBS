<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_type'); // 'vehicle_views', 'sales', 'inquiries', etc.
            $table->string('metric_name');
            $table->integer('count')->default(0);
            $table->date('date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('revenue', 12, 2)->nullable();
            $table->timestamps();
            
            $table->index(['metric_type', 'date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('analytics');
    }
};
