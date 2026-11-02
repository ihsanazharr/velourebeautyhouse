<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Gunakan raw SQL agar kompatibel MySQL tanpa doctrine/dbal
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE bookings MODIFY COLUMN customer_id CHAR(36) NULL');
            DB::statement('ALTER TABLE invoices MODIFY COLUMN customer_id CHAR(36) NULL');
        } else {
            // PostgreSQL (local dev)
            Schema::table('bookings', function (Blueprint $table) {
                $table->uuid('customer_id')->nullable()->change();
            });
            Schema::table('invoices', function (Blueprint $table) {
                $table->uuid('customer_id')->nullable()->change();
            });
        }

        // Tambah kolom show_customer_name jika belum ada
        if (!Schema::hasColumn('invoices', 'show_customer_name')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->boolean('show_customer_name')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if (Schema::hasColumn('invoices', 'show_customer_name')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('show_customer_name');
            });
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE invoices MODIFY COLUMN customer_id CHAR(36) NOT NULL');
            DB::statement('ALTER TABLE bookings MODIFY COLUMN customer_id CHAR(36) NOT NULL');
        } else {
            Schema::table('invoices', function (Blueprint $table) {
                $table->uuid('customer_id')->nullable(false)->change();
            });
            Schema::table('bookings', function (Blueprint $table) {
                $table->uuid('customer_id')->nullable(false)->change();
            });
        }
    }
};
