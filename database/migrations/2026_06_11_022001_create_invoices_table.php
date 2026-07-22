<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('invoice_number')->unique();

            $table->uuid('booking_id');

            $table->uuid('customer_id');

            $table->decimal('subtotal', 15, 2);

            $table->decimal('discount', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2);

            $table->enum('payment_status', [
                'unpaid',
                'partial',
                'paid'
            ])->default('unpaid');

            $table->boolean('invoice_sent')
                ->default(false);

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->cascadeOnDelete();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};