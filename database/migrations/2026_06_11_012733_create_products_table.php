<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('sku')->unique();

            $table->string('name');

            $table->string('category');

            $table->text('description')
                ->nullable();

            $table->string('unit');

            $table->integer('current_stock')
                ->default(0);

            $table->integer('minimum_stock')
                ->default(0);

            $table->integer('alert_stock')
                ->default(0);

            $table->decimal('cost_price', 15, 2)
                ->default(0);

            $table->decimal('selling_price', 15, 2)
                ->default(0);

            $table->string('supplier')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};