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
        // Add soft deletes to financial records for audit trail protection
        if (Schema::hasTable('transactions') && !Schema::hasColumn('transactions', 'deleted_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('payroll_records') && !Schema::hasColumn('payroll_records', 'deleted_at')) {
            Schema::table('payroll_records', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('deductions') && !Schema::hasColumn('deductions', 'deleted_at')) {
            Schema::table('deductions', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions') && Schema::hasColumn('transactions', 'deleted_at')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('payroll_records') && Schema::hasColumn('payroll_records', 'deleted_at')) {
            Schema::table('payroll_records', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('deductions') && Schema::hasColumn('deductions', 'deleted_at')) {
            Schema::table('deductions', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
