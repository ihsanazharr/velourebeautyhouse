<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->uuid('customer_id')->nullable()->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->uuid('customer_id')->nullable()->change();
            $table->boolean('show_customer_name')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('show_customer_name');
            $table->uuid('customer_id')->nullable(false)->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->uuid('customer_id')->nullable(false)->change();
        });
    }
};
