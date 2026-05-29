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
        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year'); // Year
            $table->integer('working_days')->default(0);
            $table->integer('days_present')->default(0);
            $table->integer('days_absent')->default(0);
            $table->integer('paid_leave')->default(0);
            $table->integer('unpaid_leave')->default(0);
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Paid', 'Rejected'])->default('Pending');
            $table->date('payment_date')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
    }
};
