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
        Schema::table('inquiries', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['buyer_id']);
            // Drop the buyer_id column
            $table->dropColumn('buyer_id');
            
            // Add user fields
            $table->string('user_name')->after('vehicle_id');
            $table->string('user_email')->after('user_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Drop user fields
            $table->dropColumn(['user_name', 'user_email']);
            
            // Add back buyer_id column
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
        });
    }
};
