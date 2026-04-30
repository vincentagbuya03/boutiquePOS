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
        if (! Schema::hasTable('online_orders') || Schema::hasColumn('online_orders', 'branch')) {
            return;
        }

        Schema::table('online_orders', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('online_orders') || ! Schema::hasColumn('online_orders', 'branch')) {
            return;
        }

        Schema::table('online_orders', function (Blueprint $table) {
            $table->dropColumn('branch');
        });
    }
};
