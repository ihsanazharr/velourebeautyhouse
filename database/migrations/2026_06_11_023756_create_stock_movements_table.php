<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {

            $table->id();

            $table->uuid('product_id');

            $table->date('movement_date');

            $table->enum('type', [
                'in',
                'out',
                'adjustment'
            ]);

            $table->integer('quantity');

            $table->text('notes')->nullable();

            $table->uuid('created_by')->nullable();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};