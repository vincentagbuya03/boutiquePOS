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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('type', ['in_store', 'online'])->default('in_store');
            $table->string('branch')->nullable(); // Branch where sale occurred
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // Cash, Card, Online transfer
            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('completed');
            $table->date('date_sold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
