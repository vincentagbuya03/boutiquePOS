@extends('layouts.app')

@section('title', 'Staff Dashboard')

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
    .stat-meta-urgent { color: #802030; font-weight: 800; }
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
        grid-template-columns: 2fr 1fr;
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
    .badge-urgent { background: #fdf2f2; color: #802030; }
    .badge-ok { background: #f0fdf4; color: #166534; }

    /* Action Sidebar Elements */
    .activity-stream { display: flex; flex-direction: column; gap: 1.25rem; }
    .activity-item { padding-bottom: 1.25rem; border-bottom: 1px solid #f8f9fa; }
    .activity-name { font-size: 0.9rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.25rem; }
    .activity-meta { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 600; color: #999; }

    .ops-card-premium { 
        background: var(--color-editorial); 
        border-radius: 20px; 
        padding: 2rem; 
        color: white; 
        margin-top: 1.5rem;
    }
    .ops-link { 
        display: flex; 
        align-items: center; 
        gap: 1rem; 
        color: white; 
        text-decoration: none; 
        font-weight: 700; 
        font-size: 0.9rem; 
        padding: 1rem; 
        background: rgba(255,255,255,0.1); 
        border: 1px solid rgba(255,255,255,0.2); 
        border-radius: 12px; 
        margin-top: 1rem;
        transition: all 0.2s;
    }
    .ops-link:hover { background: rgba(255,255,255,0.2); transform: translateX(5px); }

    @media (max-width: 1100px) {
        .stats-main-grid { grid-template-columns: repeat(2, 1fr); }
        .dashboard-content-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="overview-header">
    <h1 class="overview-title">Inventory Control</h1>
    <p class="overview-subtitle">Stock Management for V’S Fashion Boutique · <strong>San Carlos Branch</strong></p>
</div>

<!-- Stock Highlights -->
<div class="stats-main-grid">
    <div class="stat-arch-card">
        <div class="stat-arch-label">Catalog Size</div>
        <div class="stat-arch-value">{{ $totalProducts }}</div>
        <div class="stat-arch-meta stat-meta-neutral">
            <i class="fas fa-tags"></i> Unique items
        </div>
        <i class="fas fa-barcode stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Total Units</div>
        <div class="stat-arch-value">{{ number_format($totalStock) }}</div>
        <div class="stat-arch-meta stat-meta-neutral">
            <i class="fas fa-cubes"></i> Units in branch
        </div>
        <i class="fas fa-archive stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Low Stock</div>
        <div class="stat-arch-value" style="color: #802030;">{{ $lowStockItems }}</div>
        <div class="stat-arch-meta stat-meta-urgent">
            <i class="fas fa-exclamation-triangle"></i> Needs attention
        </div>
        <i class="fas fa-bell stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Out of Stock</div>
        <div class="stat-arch-value" style="color: #1a1a1a;">{{ $outOfStock }}</div>
        <div class="stat-arch-meta stat-meta-urgent">
            <i class="fas fa-calendar-times"></i> Reorder immediately
        </div>
        <i class="fas fa-times-circle stat-ghost-icon"></i>
    </div>
</div>

<div class="dashboard-content-grid">
    <!-- Critical Inventory Table -->
    <div class="arch-content-card">
        <div class="card-title-flex">
            <h3 class="card-title-txt">Critical Inventory</h3>
            <a href="{{ route('inventory.low-stock') }}" style="color: var(--color-editorial); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; text-decoration: none; letter-spacing: 0.1em;">All Alerts</a>
        </div>

        @if($lowStockProducts->isEmpty())
            <div style="padding: 4rem 1rem; text-align: center; color: #166534; background: #f0fdf4; border-radius: 20px;">
                <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                <p style="font-weight: 800;">Inventory health is excellent!</p>
            </div>
        @else
            <table class="arch-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: center;">Limit</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $item)
                        <tr>
                            <td style="color: #1a1a1a; font-weight: 800;">{{ $item->product->name ?? 'N/A' }}</td>
                            <td style="text-align: center; color: #802030; font-weight: 800;">{{ $item->quantity }}</td>
                            <td style="text-align: center; color: #999;">{{ $item->reorder_level }} units</td>
                            <td>
                                <span class="status-badge-inline badge-urgent">{{ $item->quantity == 0 ? 'Out' : 'Low' }}</span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('inventory.edit', $item->id) }}" style="color: var(--color-editorial); text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Restock</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Right Sidebar Stack -->
    <div>
        <div class="arch-content-card">
            <h3 class="card-title-txt" style="margin-bottom: 1.5rem;">Recent Logs</h3>
            <div class="activity-stream">
                @forelse($recentUpdates as $update)
                    <div class="activity-item">
                        <div class="activity-name">{{ $update->product->name ?? 'N/A' }}</div>
                        <div class="activity-meta">
                            <span>Set to {{ $update->quantity }} units</span>
                            <span>{{ $update->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p style="color: #999; font-size: 0.85rem;">No recent logs found.</p>
                @endforelse
            </div>
        </div>

        <div class="ops-card-premium">
            <h3 style="font-family: 'Bodoni Moda', serif; font-size: 1.5rem; font-weight: 800; margin-bottom: 1.5rem;">Operations</h3>
            <a href="{{ route('inventory.index') }}" class="ops-link">
                <i class="fas fa-list-ul"></i> Stock Archive
            </a>
            <a href="{{ route('products.index') }}" class="ops-link">
                <i class="fas fa-swatchbook"></i> Product Catalog
            </a>
        </div>
    </div>
</div>
@endsection
