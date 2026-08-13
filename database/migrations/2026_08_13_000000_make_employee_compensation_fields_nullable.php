<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('basic_salary', 15, 2)->nullable()->change();
            $table->decimal('meal_allowance', 15, 2)->nullable()->change();
            $table->decimal('overtime_rate', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('basic_salary', 15, 2)->default(0)->change();
            $table->decimal('meal_allowance', 15, 2)->default(15000)->change();
            $table->decimal('overtime_rate', 15, 2)->default(15000)->change();
        });
    }
};
