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
        Schema::table('online_orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE online_orders MODIFY COLUMN order_status ENUM('pending', 'approved', 'shipped', 'delivered', 'completed', 'cancelled', 'returned') DEFAULT 'pending'");
        });
    }

    public function down(): void
    {
        Schema::table('online_orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE online_orders MODIFY COLUMN order_status ENUM('pending', 'approved', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
        });
    }
};
