<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            $table->decimal('selling_price', 10, 2)->after('cost_price')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price', 'cost_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->after('category_id')->default(0);
            $table->decimal('cost_price', 10, 2)->after('price')->default(0);
        });

        Schema::table('product_batches', function (Blueprint $table) {
            $table->dropColumn('selling_price');
        });
    }
};
