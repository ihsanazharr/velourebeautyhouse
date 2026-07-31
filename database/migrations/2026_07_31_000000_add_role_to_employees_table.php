<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('role')->nullable()->after('user_id');
        });

        // Copy existing role values from User roles to Employee profiles
        foreach (\App\Models\Employee::all() as $employee) {
            $roleName = $employee->user?->roles->first()?->name ?? 'hairstylist';
            $employee->update(['role' => $roleName]);
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
