<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('employee_id');

            $table->unsignedTinyInteger('month');

            $table->unsignedSmallInteger('year');

            $table->decimal('basic_salary', 15, 2)->default(0);

            $table->decimal('meal_allowance_total', 15, 2)->default(0);

            $table->decimal('overtime_total', 15, 2)->default(0);

            $table->decimal('adjustment_total', 15, 2)->default(0);

            $table->decimal('late_penalty_total', 15, 2)->default(0);

            $table->decimal('early_checkout_penalty_total', 15, 2)->default(0);

            $table->decimal('gross_salary', 15, 2)->default(0);

            $table->decimal('net_salary', 15, 2)->default(0);

            $table->uuid('generated_by')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->enum('status', [
                'draft',
                'approved',
                'paid'
            ])->default('draft');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete();

            $table->foreign('generated_by')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();

            $table->unique([
                'employee_id',
                'month',
                'year'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};