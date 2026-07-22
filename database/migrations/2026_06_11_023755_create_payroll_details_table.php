<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_details', function (Blueprint $table) {

            $table->id();

            $table->uuid('payroll_id');

            $table->string('description');

            $table->enum('type', [
                'earning',
                'deduction'
            ]);

            $table->decimal('amount', 15, 2);

            $table->foreign('payroll_id')
                ->references('id')
                ->on('payrolls')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};