<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Inventory;
use App\Models\ProductBatch;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default supplier
        $supplier = Supplier::create([
            'name' => 'V\'s Primary Supplier',
            'contact_person' => 'Supplier Manager',
            'email' => 'supplier@vsfashion.com'
        ]);

        $products = [
            [
                'name' => 'Classic White T-Shirt',
                'category_id' => 1,
                'brand' => "V's Casual",
                'size' => 'M',
                'description' => 'Premium white cotton t-shirt',
                'date_added' => now()->toDateString()
            ],
            [
                'name' => 'Black V-Neck Shirt',
                'category_id' => 1,
                'brand' => "V's Premium",
                'size' => 'M',
                'description' => 'Stylish black v-neck shirt',
                'date_added' => now()->toDateString()
            ],
        ];

        $prices = [
            ['cost' => 250.00, 'selling' => 499.99],
            ['cost' => 300.00, 'selling' => 549.99],
        ];

        foreach ($products as $index => $productData) {
            $product = Product::create($productData);

            // Create a batch for this product
            ProductBatch::create([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'batch_number' => 'B-' . date('Y') . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'quantity' => 3,
                'cost_price' => $prices[$index]['cost'],
                'selling_price' => $prices[$index]['selling'],
                'date_received' => now()->toDateString(),
            ]);

            // Add global inventory for the site
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => 3, // Consolidated quantity
                'reorder_level' => 10,
                'last_updated' => now()->toDateString()
            ]);
        }
    }
}
