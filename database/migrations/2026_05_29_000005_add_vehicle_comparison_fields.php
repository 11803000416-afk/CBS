<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicle_comparisons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable();
            $table->json('vehicle_ids'); // Store as JSON array
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'views_count')) {
                $table->integer('views_count')->default(0);
            }
            if (!Schema::hasColumn('vehicles', 'status')) {
                $table->enum('status', ['pending', 'approved', 'active', 'reserved', 'sold', 'archived'])
                    ->default('pending');
            }
        });
    }

    public function down(): void {
        Schema::dropIfExists('vehicle_comparisons');
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'status']);
        });
    }
};
