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
        if (! Schema::hasTable('online_orders')) {
            return;
        }

        Schema::table('online_orders', function (Blueprint $table) {
            $columns = array_filter(
                ['customer_name', 'customer_email', 'customer_phone'],
                fn ($column) => Schema::hasColumn('online_orders', $column)
            );

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('online_orders')) {
            return;
        }

        Schema::table('online_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('online_orders', 'customer_name')) {
                $table->string('customer_name', 100);
            }
            if (! Schema::hasColumn('online_orders', 'customer_email')) {
                $table->string('customer_email')->nullable();
            }
            if (! Schema::hasColumn('online_orders', 'customer_phone')) {
                $table->string('customer_phone', 20)->nullable();
            }
        });
    }
};
