<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Show sales dashboard.
     */
    public function index()
    {
        $sales = Sale::with('user', 'items')->orderBy('date_sold', 'desc')->paginate(20);
        $dailySales = Sale::selectRaw('DATE(date_sold) as date, COUNT(*) as count, SUM(total_amount) as total')
            ->whereDate('date_sold', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('sales.index', compact('sales', 'dailySales'));
    }

    /**
     * Show the form to create a new sales transaction.
     */
    public function create()
    {
        // Only Cashier and Owner can create sales
        if (!Auth::user()->canAccessPOS()) {
            abort(403, 'You do not have permission to create sales');
        }

        $products = Product::with(['category', 'firstAvailableBatch'])->get();
        
        return view('sales.create', compact('products'));
    }

    /**
     * Store a new sales transaction.
     */
    public function store(Request $request)
    {
        // Only Cashier and Owner can create sales
        if (!Auth::user()->canAccessPOS()) {
            abort(403, 'You do not have permission to create sales');
        }

        // Decode items if sent as JSON string
        $items = $request->input('items');
        if (is_string($items)) {
            $items = json_decode($items, true);
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,gcash',
            'discount_type' => 'required|in:none,pwd,senior_citizen',
            'discount_amount' => 'required|numeric|min:0',
            'cash_received' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'payment_method' => $validated['payment_method'],
                'discount_type' => $validated['discount_type'],
                'discount_amount' => $validated['discount_amount'],
                'cash_received' => $validated['cash_received'] ?? 0,
                'status' => 'completed',
                'date_sold' => today()
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $requestedQuantity = (int) $item['quantity'];

                $batches = ProductBatch::where('product_id', $item['product_id'])
                    ->where('quantity', '>', 0)
                    ->orderBy('date_received')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $availableQuantity = (int) $batches->sum('quantity');
                if ($availableQuantity < $requestedQuantity) {
                    throw new \Exception("Insufficient batch stock for product: " . $product->name);
                }

                $firstBatch = $batches->first();
                $unitPrice = (float) $firstBatch->selling_price;
                $totalPrice = $requestedQuantity * $unitPrice;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $requestedQuantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice
                ]);

                $remainingToDeduct = $requestedQuantity;

                foreach ($batches as $batch) {
                    if ($remainingToDeduct <= 0) {
                        break;
                    }

                    $deduct = min($batch->quantity, $remainingToDeduct);
                    $batch->decrement('quantity', $deduct);
                    $remainingToDeduct -= $deduct;
                }

                $inventory = Inventory::where('product_id', $item['product_id'])->lockForUpdate()->first();

                if ($inventory) {
                    $inventory->decrement('quantity', $requestedQuantity);
                    $inventory->update(['last_updated' => today()]);
                } else {
                    throw new \Exception("Inventory not found for product: " . $product->name);
                }

                $totalAmount += $totalPrice;
            }

            // Apply discount to final total
            $finalTotal = $totalAmount - $validated['discount_amount'];
            $changeAmount = ($validated['cash_received'] ?? 0) > $finalTotal ? ($validated['cash_received'] - $finalTotal) : 0;
            
            $sale->update([
                'total_amount' => $finalTotal,
                'change_amount' => $changeAmount
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', 'Sale recorded successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show a specific sale.
     */
    public function show(Sale $sale)
    {
        $sale->load('user', 'items.product');
        return view('sales.show', compact('sale'));
    }

    /**
     * Get sales report for a date range.
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $query = Sale::query();

        if ($validated['start_date'] ?? null) {
            $query->whereDate('date_sold', '>=', $validated['start_date']);
        }

        if ($validated['end_date'] ?? null) {
            $query->whereDate('date_sold', '<=', $validated['end_date']);
        }

        $sales = $query->with('items')->orderBy('date_sold', 'desc')->get();
        $totalAmount = $sales->sum('total_amount');
        $totalItems = $sales->flatMap->items->count();

        return view('reports.sales', compact('sales', 'totalAmount', 'totalItems', 'validated'));
    }
}
