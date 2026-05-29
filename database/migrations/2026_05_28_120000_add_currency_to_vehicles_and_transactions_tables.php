<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('currency', 10)->default('Nu.')->after('price');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('currency', 10)->default('Nu.')->after('broker_commission');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
