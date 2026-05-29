<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'payment_requested_at')) {
                $table->timestamp('payment_requested_at')->nullable()->after('status');
            }

            if (!Schema::hasColumn('transactions', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('payment_requested_at');
            }

            if (!Schema::hasColumn('transactions', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('transactions', 'review_notes')) {
                $table->text('review_notes')->nullable()->after('notes');
            }
        });

        DB::statement("ALTER TABLE transactions MODIFY status ENUM('pending_review', 'completed', 'cancelled') NOT NULL DEFAULT 'pending_review'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('completed', 'cancelled') NOT NULL DEFAULT 'completed'");

        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'review_notes')) {
                $table->dropColumn('review_notes');
            }

            if (Schema::hasColumn('transactions', 'reviewed_by')) {
                $table->dropConstrainedForeignId('reviewed_by');
            }

            if (Schema::hasColumn('transactions', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }

            if (Schema::hasColumn('transactions', 'payment_requested_at')) {
                $table->dropColumn('payment_requested_at');
            }
        });
    }
};