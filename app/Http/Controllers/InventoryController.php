<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class InventoryController extends Controller
{
    /**
     * Show all inventory records.
     */
    public function index()
    {
        $inventories = Inventory::with('product.category')
            ->orderBy('product_id')
            ->paginate(15);
        
        return view('inventory.index', compact('inventories'));
    }

    /**
     * Show inventory for a specific product.
     */
    public function show(Product $product)
    {
        $product->load('firstAvailableBatch');
        $inventory = $product->inventories()->first();
        return view('inventory.show', compact('product', 'inventory'));
    }

    /**
     * Show the form to adjust inventory for a product.
     */
    public function edit(Product $product)
    {
        // Only Staff and Owner can edit inventory
        if (!auth()->user()->canManageInventory()) {
            throw new AuthorizationException('You do not have permission to edit inventory');
        }

        // Ensure inventory record exists
        $inventory = Inventory::firstOrCreate(
            ['product_id' => $product->id],
            ['quantity' => 0, 'reorder_level' => 5, 'last_updated' => today()]
        );
        
        $product->load('firstAvailableBatch');
        return view('inventory.edit', compact('product', 'inventory'));
    }

    /**
     * Update inventory for a product.
     */
    public function update(Request $request, Product $product)
    {
        // Only Staff and Owner can update inventory
        if (!auth()->user()->canManageInventory()) {
            throw new AuthorizationException('You do not have permission to update inventory');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0'
        ]);

        $inventory = Inventory::firstOrCreate(
            ['product_id' => $product->id],
            ['quantity' => 0, 'reorder_level' => 5, 'last_updated' => today()]
        );

        $inventory->update([
            'quantity' => $validated['quantity'],
            'reorder_level' => $validated['reorder_level'] ?? $inventory->reorder_level,
            'last_updated' => today()
        ]);

        return back()->with('success', 'Inventory updated successfully');
    }

    /**
     * Adjust inventory quantity (increase/decrease).
     */
    public function adjust(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer', // Can be positive or negative
            'reason' => 'nullable|string|max:255'
        ]);

        $inventory = Inventory::where('product_id', $product->id)->firstOrFail();

        $newQuantity = max(0, $inventory->quantity + $validated['adjustment']);
        
        $inventory->update([
            'quantity' => $newQuantity,
            'last_updated' => today()
        ]);

        return back()->with('success', 'Inventory adjusted successfully');
    }

    /**
     * Check low stock items.
     */
    public function lowStock()
    {
        $lowStockItems = Inventory::whereRaw('quantity <= reorder_level')
            ->with('product.category')
            ->get();
        
        return view('inventory.low-stock', compact('lowStockItems'));
    }
}
