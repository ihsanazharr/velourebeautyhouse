<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            $table->uuid('employee_id');

            $table->date('attendance_date');

            $table->timestamp('checkin_time')->nullable();

            $table->timestamp('checkout_time')->nullable();

            $table->string('checkin_photo')->nullable();

            $table->string('checkout_photo')->nullable();

            $table->integer('late_minutes')->default(0);

            $table->decimal('late_penalty', 15, 2)->default(0);

            $table->integer('early_checkout_minutes')->default(0);

            $table->decimal('early_checkout_penalty', 15, 2)->default(0);

            $table->decimal('meal_allowance', 15, 2)->default(0);

            $table->integer('overtime_minutes')->default(0);

            $table->decimal('overtime_amount', 15, 2)->default(0);

            $table->enum('status', [
                'present',
                'leave',
                'absent',
                'holiday'
            ])->default('present');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete();

            $table->unique([
                'employee_id',
                'attendance_date'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};