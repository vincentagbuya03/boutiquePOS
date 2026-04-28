@extends('layouts.app')

@section('title', 'Cashier Dashboard')

@section('styles')
<style>
    /* Overview Header */
    .overview-header { margin-bottom: 3.5rem; }
    .overview-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .overview-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    /* Stats Grid */
    .stats-main-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-arch-card {
        background: white;
        padding: 1.75rem 2rem;
        border-radius: 16px;
        border: 1px solid var(--color-border);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s;
    }

    .stat-arch-card:hover { transform: translateY(-4px); }

    .stat-arch-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1rem;
    }

    .stat-arch-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1;
        margin-bottom: 1rem;
    }

    .stat-arch-meta {
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-meta-positive { color: #802030; }
    .stat-meta-neutral { color: #666; }

    .stat-ghost-icon {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 6rem;
        color: #f8f9fa;
        z-index: 0;
    }

    .stat-arch-card > * { position: relative; z-index: 1; }

    /* Dual Grid for Content */
    .dashboard-content-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .arch-content-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--color-border);
        padding: 2.5rem;
    }

    .card-title-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .card-title-txt { font-size: 1.5rem; font-weight: 800; color: #1a1a1a; }

    /* Table Styling */
    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.25rem 0.75rem; font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; border-bottom: 1px solid #f8f9fa; }
    .arch-table td { padding: 1.25rem 0.75rem; border-bottom: 1px solid #f8f9fa; font-size: 0.85rem; font-weight: 600; }

    .status-badge-inline { padding: 0.35rem 0.85rem; border-radius: 100px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; }
    .badge-paid { background: #f0fdf4; color: #166534; }

    /* Stock List */
    .stock-scroll-list { max-height: 480px; overflow-y: auto; padding-right: 0.5rem; }
    .stock-item-row { 
        padding: 1.25rem; 
        background: #fdfdfd; 
        border: 1px solid #f1f1f1; 
        border-radius: 16px; 
        margin-bottom: 0.75rem; 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        transition: all 0.2s;
    }
    .stock-item-row:hover { border-color: var(--color-editorial); background: #fdf2f4; }
    .stock-item-info { display: flex; flex-direction: column; gap: 0.25rem; }
    .stock-item-name { font-size: 0.95rem; font-weight: 800; color: #1a1a1a; }
    .stock-item-price { font-size: 0.8rem; color: #999; font-weight: 600; }
    .stock-qty-pill { background: white; border: 2px solid #eee; padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.85rem; font-weight: 800; color: var(--color-editorial); }

    .open-pos-btn { 
        width: 100%; 
        margin-top: 1.5rem; 
        background: var(--color-editorial); 
        color: white; 
        padding: 1rem; 
        border-radius: 14px; 
        text-decoration: none; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 0.75rem; 
        font-weight: 800; 
        font-size: 0.85rem; 
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: transform 0.2s;
    }
    .open-pos-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(128, 32, 48, 0.25); }

    @media (max-width: 1100px) {
        .stats-main-grid { grid-template-columns: repeat(2, 1fr); }
        .dashboard-content-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="overview-header">
    <h1 class="overview-title">Cashier Point</h1>
    <p class="overview-subtitle">Operations for V’S Fashion Boutique · <strong>San Carlos Branch</strong></p>
</div>

<!-- Key Performance Stats -->
<div class="stats-main-grid">
    <div class="stat-arch-card">
        <div class="stat-arch-label">Today's Sales</div>
        <div class="stat-arch-value">₱{{ number_format($todaySales, 0) }}</div>
        <div class="stat-arch-meta stat-meta-positive">
            <i class="fas fa-calendar-alt"></i> {{ date('M d, Y') }}
        </div>
        <i class="fas fa-cash-register stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Transactions</div>
        <div class="stat-arch-value">{{ $todayTransactions }}</div>
        <div class="stat-arch-meta stat-meta-neutral">
            <i class="fas fa-users"></i> Volume today
        </div>
        <i class="fas fa-shopping-bag stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Avg. Transaction</div>
        <div class="stat-arch-value">₱{{ number_format($averageTransaction, 0) }}</div>
        <div class="stat-arch-meta stat-meta-positive">
            <i class="fas fa-chart-line"></i> Per customer
        </div>
        <i class="fas fa-receipt stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Lifetime Count</div>
        <div class="stat-arch-value">{{ number_format($totalSalesCount) }}</div>
        <div class="stat-arch-meta stat-meta-neutral">
            <i class="fas fa-database"></i> Total sales volume
        </div>
        <i class="fas fa-history stat-ghost-icon"></i>
    </div>
</div>

<div class="dashboard-content-grid">
    <!-- Transaction History -->
    <div class="arch-content-card">
        <div class="card-title-flex">
            <h3 class="card-title-txt">Recent Transactions</h3>
            <span style="font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase;">Real-time stream</span>
        </div>

        @if($recentSales->isEmpty())
            <div style="padding: 4rem 1rem; text-align: center; color: #adb5bd;">
                <i class="fas fa-ghost" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                <p style="font-weight: 600;">No transactions recorded today</p>
            </div>
        @else
            <table class="arch-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th style="text-align: right;">Amount</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                        <tr>
                            <td style="color: #666;">{{ $sale->date_sold->format('h:i A') }}</td>
                            <td style="text-align: right; color: #1a1a1a; font-weight: 800;">₱{{ number_format($sale->total_amount, 2) }}</td>
                            <td style="text-align: center;">
                                <span class="status-badge-inline badge-paid">Paid</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('sales.index') }}" style="display: block; text-align: center; margin-top: 2rem; color: var(--color-editorial); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; text-decoration: none; letter-spacing: 0.1em;">
            View Detailed History
        </a>
    </div>

    <!-- Inventory Quick Select -->
    <div class="arch-content-card">
        <div class="card-title-flex">
            <h3 class="card-title-txt">Stock Status</h3>
        </div>

        <div class="stock-scroll-list">
            @forelse($products as $item)
                <div class="stock-item-row">
                    <div class="stock-item-info">
                        <span class="stock-item-name">{{ $item->product->name }}</span>
                        <span class="stock-item-price">₱{{ number_format($item->product->firstAvailableBatch->selling_price ?? 0, 2) }}</span>
                    </div>
                    <span class="stock-qty-pill {{ $item->quantity < 5 ? 'urgent' : '' }}">
                        {{ $item->quantity }}
                    </span>
                </div>
            @empty
                <div style="padding: 2rem; text-align: center; color: #adb5bd;">No stock data available.</div>
            @endforelse
        </div>

        <a href="{{ route('sales.create') }}" class="open-pos-btn">
            <i class="fas fa-cash-register"></i> Open POS Dashboard
        </a>
    </div>
</div>

<!-- Historical Trend -->
<div class="arch-content-card">
    <div class="card-title-flex">
        <h3 class="card-title-txt">Branch Sales Trend</h3>
    </div>
    
    @if($salesTrend->isEmpty())
        <div style="padding: 2rem; text-align: center; color: #adb5bd;">No trend data available for this branch.</div>
    @else
        <table class="arch-table">
            <thead>
                <tr>
                    <th>Reporting Date</th>
                    <th style="text-align: right;">Daily Revenue</th>
                    <th style="text-align: center;">Orders</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesTrend as $data)
                    <tr>
                        <td style="font-weight: 800; color: #1a1a1a;">{{ \Carbon\Carbon::parse($data->date)->format('F d, Y') }}</td>
                        <td style="text-align: right; color: var(--color-editorial); font-weight: 800;">₱{{ number_format($data->total, 2) }}</td>
                        <td style="text-align: center; color: #666;">{{ $data->count }} items sold</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
