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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('agreement_file')->nullable()->after('notes')->comment('Path to agreement/contract file for evidence');
            $table->timestamp('agreement_uploaded_at')->nullable()->after('agreement_file')->comment('When agreement was uploaded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['agreement_file', 'agreement_uploaded_at']);
        });
    }
};
