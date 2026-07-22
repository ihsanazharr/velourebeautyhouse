<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_adjustments', function (Blueprint $table) {

            $table->id();

            $table->uuid('employee_id');

            $table->date('adjustment_date');

            $table->enum('type', [
                'commission',
                'bonus',
                'deduction'
            ]);

            $table->decimal('amount', 15, 2);

            $table->text('description')->nullable();

            $table->uuid('approved_by')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_adjustments');
    }
};