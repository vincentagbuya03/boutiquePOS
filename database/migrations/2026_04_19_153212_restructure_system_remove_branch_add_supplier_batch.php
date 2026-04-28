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
        // 1. Clean up dependencies before dropping online orders
        if (Schema::hasTable('returns_and_refunds')) {
            Schema::table('returns_and_refunds', function (Blueprint $table) {
                if (Schema::hasColumn('returns_and_refunds', 'online_order_id')) {
                    $table->dropForeign(['online_order_id']);
                    $table->dropColumn('online_order_id');
                }
            });
        }

        Schema::dropIfExists('order_items');
        Schema::dropIfExists('online_orders');

        // 2. Remove customer dependency from sales
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (Schema::hasColumn('sales', 'customer_id')) {
                    $table->dropForeign(['customer_id']);
                    $table->dropColumn('customer_id');
                }
                if (Schema::hasColumn('sales', 'type')) {
                    $table->dropColumn('type');
                }
                if (Schema::hasColumn('sales', 'branch')) {
                    $table->dropColumn('branch');
                }
            });
        }

        // 3. Drop customers table
        Schema::dropIfExists('customers');

        // 4. Remove branch column from users
        if (Schema::hasColumn('users', 'branch')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('branch');
            });
        }

        // 5. Remove branch column and its unique constraint from inventory
        if (Schema::hasColumn('inventory', 'branch')) {
            Schema::table('inventory', function (Blueprint $table) {
                // Add simple index on product_id so foreign key still works
                $table->index('product_id');
                $table->dropUnique(['product_id', 'branch']);
                $table->dropColumn('branch');
            });
        }

        // 5. Create suppliers table
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->timestamps();
            });
        }

        // 6. Create product_batches table
        if (!Schema::hasTable('product_batches')) {
            Schema::create('product_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
                $table->string('batch_number');
                $table->integer('quantity');
                $table->decimal('cost_price', 10, 2)->nullable();
                $table->date('expiry_date')->nullable();
                $table->date('date_received')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
        Schema::dropIfExists('suppliers');

        Schema::table('inventory', function (Blueprint $table) {
            $table->string('branch')->nullable();
            $table->unique(['product_id', 'branch']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->string('branch')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('branch')->nullable();
        });
    }
};
