<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'otp_verified_at')) {
                $table->timestamp('otp_verified_at')->nullable()->after('payment_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'otp_verified_at')) {
                $table->dropColumn('otp_verified_at');
            }
        });
    }
};
