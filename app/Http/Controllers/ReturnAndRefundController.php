<?php

namespace App\Http\Controllers;

use App\Models\ReturnAndRefund;
use App\Models\Sale;
use App\Models\OnlineOrder;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnAndRefundController extends Controller
{
    /**
     * Show all returns.
     * Owners see all returns, staff/admins see only their branch returns.
     */
    public function index()
    {
        $user = auth()->user();
        $query = ReturnAndRefund::with('product', 'sale', 'onlineOrder', 'processedByUser');
        
        if (!$user->isOwner()) {
            // Non-owners only see returns from their branch sales/online orders
            $query->whereHas('sale', function($q) use ($user) {
                $q->where('branch', $user->branch);
            })->orWhereHas('onlineOrder.product.inventories', function($q) use ($user) {
                $q->where('branch', $user->branch);
            });
        }
        
        $returns = $query->orderBy('return_date', 'desc')->paginate(20);

        $pendingCountQuery = ReturnAndRefund::where('status', 'pending');
        
        if (!$user->isOwner()) {
            $pendingCountQuery->whereHas('sale', function($q) use ($user) {
                $q->where('branch', $user->branch);
            })->orWhereHas('onlineOrder.product.inventories', function($q) use ($user) {
                $q->where('branch', $user->branch);
            });
        }
        
        $pendingCount = $pendingCountQuery->count();

        return view('returns.index', compact('returns', 'pendingCount'));
    }

    /**
     * Show the form to create a new return.
     * Cashiers can only create returns for their branch.
     */
    public function create()
    {
        // Only Cashier and Owner can create returns
        if (!auth()->user()->canAccessPOS()) {
            abort(403, 'You do not have permission to create returns');
        }

        $user = auth()->user();
        $reasons = ['damaged', 'defective', 'wrong_item', 'customer_request'];
        $actions = ['refund', 'replacement', 'store_credit'];
        
        // Cashiers see only products from their branch
        if ($user->isOwner()) {
            $products = Product::all();
        } else {
            $products = Product::whereHas('inventories', function($query) use ($user) {
                $query->where('branch', $user->branch);
            })->get();
        }

        return view('returns.create', compact('reasons', 'actions', 'products'));
    }

    /**
     * Store a new return.
     */
    public function store(Request $request)
    {
        // Only Cashier and Owner can create returns
        if (!auth()->user()->canAccessPOS()) {
            abort(403, 'You do not have permission to create returns');
        }
        $validated = $request->validate([
            'sale_id' => 'nullable|exists:sales,id',
            'online_order_id' => 'nullable|exists:online_orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity_returned' => 'required|integer|min:1',
            'reason' => 'required|in:damaged,defective,wrong_item,customer_request',
            'description' => 'nullable|string',
            'return_date' => 'required|date'
        ]);

        $validated['status'] = 'pending';

        ReturnAndRefund::create($validated);

        return redirect()->route('returns.index')->with('success', 'Return submitted successfully');
    }

    /**
     * Show return details.
     */
    public function show(ReturnAndRefund $return)
    {
        return view('returns.show', compact('return'));
    }

    /**
     * Show the form to approve a return.
     */
    public function edit(ReturnAndRefund $return)
    {
        $actions = ['refund', 'replacement', 'store_credit'];
        return view('returns.edit', compact('return', 'actions'));
    }

    /**
     * Approve a return.
     * Admins can only approve returns from their branch.
     */
    public function approve(Request $request, ReturnAndRefund $return)
    {
        // Only Admin and Owner can approve returns
        if (!Auth::user()->canManageReturns()) {
            abort(403, 'You do not have permission to approve returns');
        }

        $user = Auth::user();
        
        // Check if user has access to this return
        if (!$user->isOwner()) {
            $hasAccess = false;
            if ($return->sale && $return->sale->branch === $user->branch) {
                $hasAccess = true;
            } elseif ($return->onlineOrder && $return->onlineOrder->product->inventories()->where('branch', $user->branch)->exists()) {
                $hasAccess = true;
            }
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to approve this return');
            }
        }

        $validated = $request->validate([
            'action' => 'required|in:refund,replacement,store_credit',
            'refund_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $product = $return->product;
        $quantity = $return->quantity_returned;

        if ($validated['action'] === 'replacement') {
            // Add inventory back for the appropriate branch
            $branch = $return->sale?->branch ?? ($return->onlineOrder?->product?->inventories?->first()?->branch);
            if ($branch) {
                $inventory = Inventory::where('product_id', $product->id)
                    ->where('branch', $branch)
                    ->first();
                if ($inventory) {
                    $inventory->increment('quantity', $quantity);
                    $inventory->update(['last_updated' => today()]);
                }
            }
        } elseif ($validated['action'] === 'refund') {
            $refundAmount = $validated['refund_amount'] ?? ($product->price * $quantity);
            $return->refund_amount = $refundAmount;
        }

        $return->approve(
            $validated['action'],
            $return->refund_amount ?? null,
            Auth::id()
        );

        return redirect()->route('returns.show', $return)->with('success', 'Return approved successfully');
    }

    /**
     * Reject a return.
     */
    public function reject(Request $request, ReturnAndRefund $return)
    {
        // Only Admin and Owner can reject returns
        if (!Auth::user()->canManageReturns()) {
            abort(403, 'You do not have permission to reject returns');
        }

        $validated = $request->validate([
            'description' => 'nullable|string'
        ]);

        $return->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'processed_date' => today()
        ]);

        return redirect()->route('returns.show', $return)->with('success', 'Return rejected');
    }

    /**
     * Get returns report.
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:pending,approved,rejected,refunded,replaced',
            'reason' => 'nullable|string|in:damaged,defective,wrong_item,customer_request'
        ]);

        $query = ReturnAndRefund::query();

        if ($validated['start_date'] ?? null) {
            $query->whereDate('return_date', '>=', $validated['start_date']);
        }

        if ($validated['end_date'] ?? null) {
            $query->whereDate('return_date', '<=', $validated['end_date']);
        }

        if ($validated['status'] ?? null) {
            $query->where('status', $validated['status']);
        }

        if ($validated['reason'] ?? null) {
            $query->where('reason', $validated['reason']);
        }

        $returns = $query->with('product')->orderBy('return_date', 'desc')->get();
        $totalRefunded = $returns->where('status', 'refunded')->sum('refund_amount');
        $totalReturns = $returns->count();

        return view('reports.returns', compact('returns', 'totalRefunded', 'totalReturns', 'validated'));
    }
}
