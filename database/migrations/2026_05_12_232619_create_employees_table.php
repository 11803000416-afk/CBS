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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('designation');
            $table->string('department');
            $table->date('date_of_joining');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'On Leave'])->default('Active');
            $table->decimal('base_salary', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
