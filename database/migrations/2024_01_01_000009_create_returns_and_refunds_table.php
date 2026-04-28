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
        Schema::create('returns_and_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null');
            $table->foreignId('online_order_id')->nullable()->constrained('online_orders')->onDelete('set null');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('quantity_returned');
            $table->enum('reason', ['damaged', 'defective', 'wrong_item', 'customer_request'])->default('damaged');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'refunded', 'replaced'])->default('pending');
            $table->enum('action', ['refund', 'replacement', 'store_credit'])->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->date('return_date');
            $table->date('processed_date')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns_and_refunds');
    }
};
