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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dealer_license_number', 100)->nullable()->after('is_active');
            $table->string('dealer_license_document_path')->nullable()->after('dealer_license_number');
            $table->enum('dealer_license_status', ['not_submitted', 'pending', 'approved', 'rejected'])
                ->default('not_submitted')
                ->after('dealer_license_document_path');
            $table->text('dealer_license_admin_notes')->nullable()->after('dealer_license_status');
            $table->timestamp('dealer_license_submitted_at')->nullable()->after('dealer_license_admin_notes');
            $table->timestamp('dealer_license_reviewed_at')->nullable()->after('dealer_license_submitted_at');
            $table->foreignId('dealer_license_reviewed_by')
                ->nullable()
                ->after('dealer_license_reviewed_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dealer_license_reviewed_by');
            $table->dropColumn([
                'dealer_license_number',
                'dealer_license_document_path',
                'dealer_license_status',
                'dealer_license_admin_notes',
                'dealer_license_submitted_at',
                'dealer_license_reviewed_at',
            ]);
        });
    }
};
