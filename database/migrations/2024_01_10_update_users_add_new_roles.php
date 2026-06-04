<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change the role enum to include new roles: owner, admin, staff, cashier
            $table->enum('role', ['owner', 'admin', 'staff', 'cashier'])->default('cashier')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // When rolling back, existing rows may still contain the newer roles.
        // Normalize them first so shrinking the enum doesn't error.
        DB::table('users')
            ->whereNotIn('role', ['admin', 'cashier'])
            ->update(['role' => 'cashier']);

        Schema::table('users', function (Blueprint $table) {
            // Revert to the original enum
            $table->enum('role', ['admin', 'cashier'])->default('cashier')->change();
        });
    }
};
