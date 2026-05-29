<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('fuel_type')->nullable()->after('price');
            $table->string('transmission')->default('Manual')->after('fuel_type');
            $table->string('color')->nullable()->after('transmission');
            $table->integer('engine_capacity')->nullable()->after('color');
            $table->string('condition')->default('Good')->after('engine_capacity');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['fuel_type', 'transmission', 'color', 'engine_capacity', 'condition']);
        });
    }
};
