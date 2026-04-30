<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\OnlineOrder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = auth()->user();

        // Route to role-specific dashboard
        if ($user->isOwner()) {
            return $this->ownerDashboard();
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isStaff()) {
            return $this->staffDashboard();
        } elseif ($user->isCashier()) {
            return $this->cashierDashboard();
        }

        // Fallback to generic dashboard
        return view('dashboard');
    }

    /**
     * Owner Dashboard - System overview for San Carlos
     */
    private function ownerDashboard()
    {
        $totalSales = Sale::sum('total_amount');
        $todaySales = Sale::whereDate('date_sold', today())->sum('total_amount');
        $totalProducts = Product::count();
        $totalUsers = User::whereNotNull('role')->count();
        
        // Get sales trend for the last 7 days
        $salesTrend = Sale::selectRaw('DATE(date_sold) as date, SUM(total_amount) as total')
            ->whereBetween('date_sold', [now()->subDays(7), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get monthly summary
        $monthlySales = Sale::selectRaw('MONTH(date_sold) as month, SUM(total_amount) as total')
            ->whereYear('date_sold', now()->year)
            ->groupBy('month')
            ->get();

        // Low stock items
        $lowStockItems = Inventory::where('quantity', '<=', DB::raw('reorder_level'))
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->with('product')
            ->limit(10)
            ->get();

        // Calculate total earnings (Profit)
        $totalProfit = 0;
        
        // From Sales
        $sales = Sale::with('items.product.firstAvailableBatch')->get();
        foreach($sales as $sale) {
            foreach($sale->items as $item) {
                if($item->product && $item->product->firstAvailableBatch) {
                    $totalProfit += ($item->unit_price - $item->product->firstAvailableBatch->cost_price) * $item->quantity;
                }
            }
        }

        return view('dashboards.owner', compact(
            'totalSales',
            'todaySales',
            'totalProducts',
            'totalUsers',
            'salesTrend',
            'monthlySales',
            'lowStockItems',
            'totalProfit'
        ));
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        $totalSales = Sale::sum('total_amount');
        $todaySales = Sale::whereDate('date_sold', today())->sum('total_amount');
        $staffCount = User::whereIn('role', ['staff', 'cashier'])->count();
        $totalReturns = Sale::count();

        // Low stock items
        $lowStockItems = Inventory::where('quantity', '<=', DB::raw('reorder_level'))
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->with('product')
            ->get();

        // Sales trend for the last 7 days
        $salesTrend = Sale::selectRaw('DATE(date_sold) as date, SUM(total_amount) as total')
            ->whereBetween('date_sold', [now()->subDays(7), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent sales
        $recentSales = Sale::with('user')
            ->orderBy('date_sold', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.admin', compact(
            'totalSales',
            'todaySales',
            'staffCount',
            'totalReturns',
            'lowStockItems',
            'salesTrend',
            'recentSales'
        ));
    }

    /**
     * Staff Dashboard
     */
    private function staffDashboard()
    {
        $totalProducts = Product::count();
        $activeInventory = Inventory::whereHas('product', fn ($query) => $query->whereNull('deleted_at'));
        $lowStockItems = (clone $activeInventory)->where('quantity', '<=', DB::raw('reorder_level'))->count();
        $outOfStock = (clone $activeInventory)->where('quantity', 0)->count();
        $totalStock = (clone $activeInventory)->sum('quantity');

        // Low stock products
        $lowStockProducts = Inventory::where('quantity', '<=', DB::raw('reorder_level'))
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->with('product')
            ->orderBy('quantity')
            ->limit(10)
            ->get();

        // Recent inventory updates
        $recentUpdates = Inventory::with('product')
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.staff', compact(
            'totalProducts',
            'lowStockItems',
            'outOfStock',
            'totalStock',
            'lowStockProducts',
            'recentUpdates'
        ));
    }

    /**
     * Cashier Dashboard
     */
    private function cashierDashboard()
    {
        $user = auth()->user();

        $todaySales = Sale::where('user_id', $user->id)->whereDate('date_sold', today())->sum('total_amount');
        $totalSalesCount = Sale::where('user_id', $user->id)->count();
        $todayTransactions = Sale::where('user_id', $user->id)->whereDate('date_sold', today())->count();
        $averageTransaction = $totalSalesCount > 0 ? Sale::where('user_id', $user->id)->average('total_amount') : 0;

        // Get available products
        $products = Inventory::with('product.firstAvailableBatch')
            ->whereHas('product', fn ($query) => $query->whereNull('deleted_at'))
            ->limit(10)
            ->get();

        // Recent sales by this cashier
        $recentSales = Sale::where('user_id', $user->id)
            ->with('user')
            ->orderBy('date_sold', 'desc')
            ->limit(5)
            ->get();

        // Sales trend
        $salesTrend = Sale::where('user_id', $user->id)
            ->selectRaw('DATE(date_sold) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->whereBetween('date_sold', [now()->subDays(7), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboards.cashier', compact(
            'todaySales',
            'totalSalesCount',
            'todayTransactions',
            'averageTransaction',
            'products',
            'recentSales',
            'salesTrend'
        ));
    }
}

