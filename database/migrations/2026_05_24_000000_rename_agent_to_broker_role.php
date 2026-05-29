<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'broker' to the enum if not already present
        if (DB::getDriverName() === 'mysql') {
            // For MySQL, we need to modify the enum by changing the column type
            Schema::table('users', function (Blueprint $table) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'agent', 'broker', 'seller', 'buyer') DEFAULT 'buyer'");
            });

            // Transform existing 'agent' values to 'broker'
            DB::update("UPDATE users SET role = 'broker' WHERE role = 'agent'");

            // Remove 'agent' from enum
            Schema::table('users', function (Blueprint $table) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'broker', 'seller', 'buyer') DEFAULT 'buyer'");
            });
        } else {
            // For SQLite and other drivers, direct update works
            DB::update("UPDATE users SET role = 'broker' WHERE role = 'agent'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Restore 'agent' to enum
            Schema::table('users', function (Blueprint $table) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'agent', 'broker', 'seller', 'buyer') DEFAULT 'buyer'");
            });

            // Transform 'broker' values back to 'agent'
            DB::update("UPDATE users SET role = 'agent' WHERE role = 'broker'");

            // Remove 'broker' from enum
            Schema::table('users', function (Blueprint $table) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'agent', 'buyer') DEFAULT 'buyer'");
            });
        } else {
            // For SQLite, just restore
            DB::update("UPDATE users SET role = 'agent' WHERE role = 'broker'");
        }
    }
};
