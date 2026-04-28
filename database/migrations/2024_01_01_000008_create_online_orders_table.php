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
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('customer_name', 100);
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->integer('quantity');
            $table->enum('order_status', ['pending', 'approved', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('delivery_address', 255);
            $table->string('platform')->nullable(); // Facebook, TikTok, Website
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_orders');
    }
};
