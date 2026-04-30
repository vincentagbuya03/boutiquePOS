<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\ProductBatch;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = ProductBatch::with(['product', 'supplier'])
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->orderBy('date_received', 'desc')
            ->paginate(15);
        return view('batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        // Generate automatic batch number
        $nextId = ProductBatch::max('id') + 1;
        $batchNumber = 'V-BATCH-' . date('Ymd') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        
        return view('batches.create', compact('products', 'suppliers', 'batchNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'batch_number' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'date_received' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            // Create the batch
            ProductBatch::create($validated);

            // Update or Create inventory record
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $validated['product_id']],
                ['quantity' => 0, 'reorder_level' => 5, 'last_updated' => now()]
            );

            // Increment inventory quantity
            $inventory->increment('quantity', $validated['quantity']);
            $inventory->update(['last_updated' => now()]);
        });

        return redirect()->route('batches.index')->with('success', 'Product batch added and inventory updated successfully');
    }

    /**
     * Archive the specified resource.
     */
    public function destroy(ProductBatch $batch)
    {
        DB::transaction(function () use ($batch) {
            // Decrement inventory when the batch is archived so it is no longer sellable.
            $inventory = Inventory::where('product_id', $batch->product_id)->first();
            if ($inventory) {
                $inventory->decrement('quantity', min($inventory->quantity, $batch->quantity));
            }
            $batch->delete();
        });

        return redirect()->route('batches.index')->with('success', 'Batch archived and inventory adjusted successfully');
    }
}
