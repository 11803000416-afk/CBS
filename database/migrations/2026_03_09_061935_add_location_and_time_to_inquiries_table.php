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
            $table->string('meeting_location')->nullable()->after('message');
            $table->dateTime('preferred_time')->nullable()->after('meeting_location');
            $table->text('special_requirements')->nullable()->after('preferred_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['meeting_location', 'preferred_time', 'special_requirements']);
        });
    }
};
