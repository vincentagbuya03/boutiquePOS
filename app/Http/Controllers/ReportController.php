<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\OnlineOrder;
use App\Models\Inventory;
use App\Models\ReturnAndRefund;
use App\Models\ProductBatch;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show reports & insights dashboard.
     */
    public function index(Request $request)
    {
        // Only Admin and Owner can view reports
        if (!auth()->user()->canViewReports()) {
            abort(403, 'You do not have permission to view reports');
        }

        $thirtyDaysAgo = now()->subDays(30);
        $sixtyDaysAgo = now()->subDays(60);
        
        // Current 30 days
        $sales = Sale::whereBetween('date_sold', [$thirtyDaysAgo, now()])->get();
        $totalSales = $sales->sum('total_amount');
        $totalTransactions = $sales->count();
        
        // Previous 30 days for trend
        $prevSales = Sale::whereBetween('date_sold', [$sixtyDaysAgo, $thirtyDaysAgo])->get();
        $prevTotalSales = $prevSales->sum('total_amount');
    
        // Calculate sales trend percentage
        if ($prevTotalSales > 0) {
            $salesTrend = (($totalSales - $prevTotalSales) / $prevTotalSales) * 100;
        } elseif ($totalSales > 0 && $prevTotalSales == 0) {
            $salesTrend = 100; // 100% increase from zero
        } else {
            $salesTrend = 0; // No previous data, can't calculate trend
        }

        // Profit
        $totalProfit = 0;
        foreach($sales as $sale) {
            foreach($sale->items as $item) {
                if($item->product && $item->product->firstAvailableBatch) {
                    $totalProfit += ($item->unit_price - $item->product->firstAvailableBatch->cost_price) * $item->quantity;
                }
            }
        }

        // Inventory
        $inventories = Inventory::with('product.firstAvailableBatch')
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->get();
        $inventoryValue = $inventories->sum(function ($item) {
            return $item->quantity * ($item->product->firstAvailableBatch->selling_price ?? 0);
        });
        $totalInventoryItems = $inventories->sum('quantity');

        // Returns
        $returns = ReturnAndRefund::whereBetween('return_date', [$thirtyDaysAgo, now()])
            ->where('status', 'refunded')
            ->get();
        $totalReturns = $returns->sum('refund_amount');
        $returnCount = $returns->count();

        // Chart: Daily Sales
        $dailySales = Sale::where('date_sold', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(date_sold) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart: Category Distribution
        $categorySales = Sale::where('date_sold', '>=', $thirtyDaysAgo)
            ->with('items.product.category')
            ->get()
            ->flatMap->items
            ->groupBy(fn($item) => $item->product->category->name ?? 'Uncategorized')
            ->map(fn($items) => $items->sum('total_price'))
            ->sortByDesc(fn($val) => $val)
            ->take(5);

        return view('reports.index', compact(
            'totalSales', 'totalTransactions', 'salesTrend',
            'totalProfit', 'inventoryValue', 'totalInventoryItems',
            'totalReturns', 'returnCount', 'dailySales', 'categorySales'
        ));
    }

    /**
     * Show inventory report.
     * Owners see all branches, staff/admins see only their branch.
     */
    public function inventory(Request $request)
    {
        // Only Admin and Owner can view reports
        if (!auth()->user()->canViewReports()) {
            abort(403, 'You do not have permission to view reports');
        }

        $user = auth()->user();
        
        // We query individual batches that have quantity > 0
        $query = ProductBatch::with(['product.category', 'supplier'])
            ->whereHas('product', fn ($productQuery) => $productQuery->whereNull('deleted_at'))
            ->where('quantity', '>', 0);

        if ($request->get('low_stock')) {
            // Low stock check on the aggregate product level
            $lowStockProductIds = Inventory::whereRaw('quantity <= reorder_level')
                ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
                ->pluck('product_id');
            $query->whereIn('product_id', $lowStockProductIds);
        }

        $batches = $query->orderBy('date_received', 'desc')->get();
        
        $totalValue = $batches->sum(fn($b) => $b->quantity * $b->selling_price);

        // Still count low stock products for the indicator
        $lowStockCount = Inventory::whereRaw('quantity <= reorder_level')
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->count();

        return view('reports.inventory', compact('batches', 'totalValue', 'lowStockCount'));
    }

    /**
     * Show sales report.
     * Owners see all sales, non-owners see only their branch sales.
     */
    public function sales(Request $request)
    {
        // Only Admin and Owner can view reports
        if (!auth()->user()->canViewReports()) {
            abort(403, 'You do not have permission to view reports');
        }

        $user = auth()->user();
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'period' => 'nullable|in:daily,weekly,monthly'
        ]);

        $query = Sale::query();
        
        // Non-owners see only their branch sales
        if (!$user->isOwner()) {
            $query->where('branch', $user->branch);
        }

        if ($validated['start_date'] ?? null) {
            $query->whereDate('date_sold', '>=', $validated['start_date']);
        } else {
            $query->whereDate('date_sold', '>=', now()->subMonth());
        }

        if ($validated['end_date'] ?? null) {
            $query->whereDate('date_sold', '<=', $validated['end_date']);
        } else {
            $query->whereDate('date_sold', '<=', now());
        }

        $sales = $query->with('items')->orderBy('date_sold', 'desc')->get();

        $summary = [
            'total_sales' => $sales->sum('total_amount'),
            'total_transactions' => $sales->count(),
            'total_items_sold' => $sales->flatMap->items->count(),
            'average_transaction' => $sales->count() > 0 ? $sales->sum('total_amount') / $sales->count() : 0
        ];

        // Group by period
        $byPeriod = [];
        $selectedPeriod = $validated['period'] ?? null;
        if ($selectedPeriod === 'daily') {
            $byPeriod = $sales->groupBy(function ($sale) {
                return $sale->date_sold->format('Y-m-d');
            });
        } elseif ($selectedPeriod === 'weekly') {
            $byPeriod = $sales->groupBy(function ($sale) {
                return $sale->date_sold->format('Y-W');
            });
        } elseif ($selectedPeriod === 'monthly') {
            $byPeriod = $sales->groupBy(function ($sale) {
                return $sale->date_sold->format('Y-m');
            });
        }

        return view('reports.sales-report', compact('sales', 'summary', 'byPeriod', 'validated'));
    }

    /**
     * Show profit analysis.
     * Owners see all profits, non-owners see only their branch.
     */
    public function profitAnalysis(Request $request)
    {
        // Only Admin and Owner can view reports
        if (!auth()->user()->canViewReports()) {
            abort(403, 'You do not have permission to view reports');
        }

        $user = auth()->user();
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $startDate = $validated['start_date'] ?? now()->subMonth();
        $endDate = $validated['end_date'] ?? now();

        $salesQuery = Sale::selectRaw('SUM(total_amount) as total_sales')
            ->whereBetween('date_sold', [$startDate, $endDate]);
        
        $refundsQuery = ReturnAndRefund::selectRaw('SUM(refund_amount) as total_refunds')
            ->whereBetween('return_date', [$startDate, $endDate])
            ->where('status', '=', 'refunded');
        
        // Non-owners see only their branch data
        if (!$user->isOwner()) {
            $salesQuery->where('branch', $user->branch);
        }
        
        $sales = $salesQuery->first();
        $refunds = $refundsQuery->first();

        $salesDetails = Sale::with('items.product.firstAvailableBatch')
            ->whereBetween('date_sold', [$startDate, $endDate]);
        
        if (!$user->isOwner()) {
            $salesDetails->where('branch', $user->branch);
        }
        
        $salesList = $salesDetails->get();
        
        $totalCost = 0;
        foreach($salesList as $sale) {
            foreach($sale->items as $item) {
                if($item->product && $item->product->firstAvailableBatch) {
                    $totalCost += ($item->product->firstAvailableBatch->cost_price * $item->quantity);
                }
            }
        }

        $topProducts = $salesList
            ->flatMap->items
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product' => $items->first()->product,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum('total_price')
                ];
            })
            ->sortByDesc('revenue')
            ->take(10);

        $grossProfit = ($sales->total_sales ?? 0) - ($refunds->total_refunds ?? 0) - $totalCost;

        return view('reports.profit', compact('sales', 'refunds', 'topProducts', 'grossProfit', 'totalCost', 'validated'));
    }
}
