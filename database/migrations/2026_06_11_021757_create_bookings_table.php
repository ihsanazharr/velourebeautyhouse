<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('booking_number')->unique();

            $table->uuid('customer_id');

            $table->date('booking_date');

            $table->timestamp('booking_start');

            $table->timestamp('booking_end');

            $table->decimal('dp_amount', 15, 2)->default(0);

            $table->decimal('total_amount', 15, 2)->default(0);

            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled',
                'no_show'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};