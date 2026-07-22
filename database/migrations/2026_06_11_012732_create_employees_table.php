<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('employee_code')->unique();

            $table->string('full_name')->index();

            $table->string('phone')->nullable();

            $table->date('birth_date')->nullable();

            $table->text('address')->nullable();

            $table->date('hire_date')->nullable();

            $table->decimal('basic_salary', 15, 2)->default(0);

            $table->decimal('commission_percentage', 5, 2)->default(0);

            $table->decimal('meal_allowance', 15, 2)->default(15000);

            $table->decimal('overtime_rate', 15, 2)->default(15000);

            $table->enum('employment_status', [
                'active',
                'inactive',
                'resigned'
            ])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};