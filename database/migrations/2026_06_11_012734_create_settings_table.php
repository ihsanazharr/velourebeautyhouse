<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            $table->string('company_name');

            $table->text('address');

            $table->string('phone');

            $table->string('whatsapp_number');

            $table->string('email')
                ->nullable();

            $table->string('qris_image')
                ->nullable();

            $table->time('open_time');

            $table->time('close_time');

            $table->json('working_days')
                ->nullable();

            $table->integer('late_tolerance_minutes')
                ->default(10);

            $table->decimal('late_penalty_amount', 15, 2)
                ->default(10000);

            $table->decimal('overtime_rate', 15, 2)
                ->default(15000);

            $table->decimal('meal_allowance_amount', 15, 2)
                ->default(15000);

            $table->boolean('booking_enabled')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};