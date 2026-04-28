@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    /* Overview Header */
    .overview-header { margin-bottom: 3.5rem; }
    .overview-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .overview-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    /* Stats Ribbon */
    .stats-editorial-ribbon {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .stat-arch-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        border: 1px solid var(--color-border);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .stat-arch-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.03); }

    .stat-arch-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 1rem; display: block; }
    .stat-arch-value { font-size: 2.25rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem; display: block; }
    .stat-arch-meta { font-size: 0.7rem; font-weight: 700; color: #adb5bd; display: flex; align-items: center; gap: 0.5rem; }
    .stat-meta-trend { color: #3c5e5e; }

    /* Branch Focus Grid */
    .focus-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 2.5rem; margin-bottom: 4rem; }

    .focus-card {
        background: white;
        border-radius: 30px;
        padding: 3rem;
        border: 1px solid var(--color-border);
    }

    .focus-card-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 2rem; color: #1a1a1a; display: flex; align-items: center; gap: 1rem; }

    /* Analytical Progress Bars */
    .arch-bar-shell { display: flex; align-items: flex-end; gap: 0.75rem; height: 200px; margin-top: 2rem; }
    .arch-bar-wrapper { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }
    .arch-bar-item { width: 100%; border-radius: 8px; transition: height 1s ease-out; }
    .arch-bar-label { font-size: 0.6rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; }

    .bar-maroon { background: #802030; }
    .bar-mauve { background: #f8f3f4; }
    .bar-teal { background: #3c5e5e; }

    /* Recent Feed */
    .feed-item { display: flex; align-items: center; gap: 1.5rem; padding: 1.5rem 0; border-bottom: 1px solid #f8f9fa; }
    .feed-item:last-child { border-bottom: none; }
    .feed-icon { width: 40px; height: 40px; border-radius: 12px; background: #fdf2f4; color: #802030; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; }
    .feed-info { flex: 1; }
    .feed-title { font-size: 0.9rem; font-weight: 800; color: #1a1a1a; }
    .feed-meta { font-size: 0.7rem; color: #adb5bd; font-weight: 600; }
    .feed-val { font-size: 0.95rem; font-weight: 800; color: #1a1a1a; }
</style>
@endsection

@section('content')
<div class="overview-header">
    <h1 class="overview-title">Branch Intelligence: San Carlos</h1>
    <p class="overview-subtitle">Curating operations and staff performance for V’S Fashion Boutique.</p>
</div>

<div class="stats-editorial-ribbon">
    <div class="stat-arch-card">
        <span class="stat-arch-label">Regional Revenue</span>
        <span class="stat-arch-value">₱{{ number_format($totalSales / 1000, 1) }}k</span>
        <div class="stat-arch-meta stat-meta-trend">
            <i class="fas fa-arrow-up"></i> Branch Aggregate
        </div>
    </div>

    <div class="stat-arch-card">
        <span class="stat-arch-label">Today's Acquisitions</span>
        <span class="stat-arch-value">₱{{ number_format($todaySales, 2) }}</span>
        <div class="stat-arch-meta">
            <i class="far fa-calendar-alt"></i> Current Session
        </div>
    </div>

    <div class="stat-arch-card">
        <span class="stat-arch-label">Management Hub</span>
        <span class="stat-arch-value">{{ $totalReturns }}</span>
        <div class="stat-arch-meta">
            <i class="fas fa-history"></i> Total Transactions
        </div>
    </div>

    <div class="stat-arch-card">
        <span class="stat-arch-label">Curator Count</span>
        <span class="stat-arch-value">{{ $staffCount }}</span>
        <div class="stat-arch-meta">
            <i class="fas fa-users"></i> Local Staff Records
        </div>
    </div>
</div>

<div class="focus-grid">
    <div class="focus-card">
        <div class="focus-card-title">
            <i class="fas fa-chart-bar" style="color: #adb5bd;"></i>
            <span>Analytical Performance</span>
        </div>
        <p style="font-size: 0.85rem; color: #999; font-weight: 600;">Daily transaction volume overview for the last 7 sessions.</p>
        
        <div class="arch-bar-shell">
            @forelse($salesTrend as $data)
                <div class="arch-bar-wrapper">
                    @php $max = $salesTrend->max('total') ?: 1; $height = ($data->total / $max) * 100; @endphp
                    <div class="arch-bar-item bar-maroon" style="height: {{ max(10, $height) }}%;"></div>
                    <span class="arch-bar-label">{{ \Carbon\Carbon::parse($data->date)->format('D') }}</span>
                </div>
            @empty
                <div style="flex: 1; text-align: center; color: #adb5bd; font-size: 0.8rem; padding-bottom: 2rem;">No analytical data available for this sequence.</div>
            @endforelse
        </div>
    </div>

    <div class="focus-card">
        <div class="focus-card-title">
            <i class="fas fa-bolt" style="color: #adb5bd;"></i>
            <span>Operational Feed</span>
        </div>
        
        <div style="margin-top: 1rem;">
            <h4 style="font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Recent Acquisitions</h4>
            @forelse($recentSales as $sale)
                <div class="feed-item">
                    <div class="feed-icon"><i class="fas fa-shopping-bag"></i></div>
                    <div class="feed-info">
                        <div class="feed-title">{{ $sale->user->name ?? 'Curator' }}</div>
                        <div class="feed-meta">{{ $sale->date_sold->format('M d, H:i') }} • San Carlos</div>
                    </div>
                    <div class="feed-val">₱{{ number_format($sale->total_amount, 2) }}</div>
                </div>
            @empty
                <p style="font-size: 0.85rem; color: #adb5bd;">No recent acquisitions recorded.</p>
            @endforelse
        </div>

        <div style="margin-top: 2.5rem;">
            <h4 style="font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Stock Status</h4>
            @forelse($lowStockItems->take(3) as $item)
                <div class="feed-item">
                    <div class="feed-icon" style="background: #fdfdfd; color: #adb5bd;"><i class="fas fa-archive"></i></div>
                    <div class="feed-info">
                        <div class="feed-title">{{ $item->product->name }}</div>
                        <div class="feed-meta">Stock: <span style="color: #802030;">{{ $item->quantity }}</span> / {{ $item->reorder_level }}</div>
                    </div>
                    <a href="{{ route('inventory.edit', $item->product) }}" style="color: #adb5bd; font-size: 0.8rem;"><i class="fas fa-arrow-right"></i></a>
                </div>
            @empty
                <p style="font-size: 0.85rem; color: #adb5bd;">All pieces are well-stocked.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="focus-card">
    <div class="focus-card-title">Quick V'S Fashion Actions</div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('inventory.index') }}" class="btn-arch-action" style="padding: 1rem 2rem; background: #fdf2f4; color: #802030; border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Inventory Records</a>
        <a href="{{ route('sales.index') }}" class="btn-arch-action" style="padding: 1rem 2rem; background: #f1f3f5; color: #495057; border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Sales Logs</a>
        <a href="{{ route('reports.inventory') }}" class="btn-arch-action" style="padding: 1rem 2rem; background: #f8f9fa; color: #1a1a1a; border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid #eee;">View Reports</a>
    </div>
</div>
@endsection
