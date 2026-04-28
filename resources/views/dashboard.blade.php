@extends('layouts.app')

@section('title', 'Overview')

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

    /* Performance Grid */
    .performance-grid {
        display: grid;
        grid-template-columns: 2.2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .chart-perf-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--color-border);
        padding: 2.5rem;
    }

    .chart-perf-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 3rem;
    }

    .chart-perf-label { font-size: 0.65rem; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; display: block; }
    .chart-perf-title { font-size: 1.75rem; font-weight: 800; color: #1a1a1a; }

    .chart-toggle-pill {
        background: #f1f3f5;
        padding: 0.25rem;
        border-radius: 100px;
        display: flex;
        gap: 0.25rem;
    }

    .toggle-pill-item {
        padding: 0.5rem 1.25rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .toggle-pill-item.active { background: white; color: #1a1a1a; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

    .arch-bars-container {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 250px;
        padding: 0 1rem;
    }

    .arch-bar-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        gap: 1.5rem;
    }

    .arch-bar-item {
        width: 65%;
        border-radius: 8px;
        min-height: 20px;
        transition: height 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .bar-mauve { background: #c299a0; }
    .bar-maroon { background: #802030; }
    .bar-teal { background: #3c5e5e; }

    .arch-bar-label { font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; }

    /* Seasonal Card */
    .seasonal-archive-card {
        background: linear-gradient(rgba(128, 32, 48, 0.85), rgba(128, 32, 48, 0.85)), url('https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800');
        background-size: cover;
        background-position: center;
        border-radius: 24px;
        padding: 3rem;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .seasonal-title { font-family: 'Bodoni Moda', serif; font-size: 2.25rem; font-weight: 800; line-height: 1.1; margin-bottom: 2rem; }
    .seasonal-desc { font-size: 0.95rem; line-height: 1.6; opacity: 0.9; margin-bottom: 2.5rem; font-weight: 500; }
    .seasonal-action-btn { background: white; color: #1a1a1a; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; width: fit-content; transition: transform 0.2s; }
    .seasonal-action-btn:hover { transform: scale(1.05); }

    /* Table Section */
    .orders-section-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--color-border);
        padding: 2.5rem;
        position: relative;
    }

    .section-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; }
    .section-title { font-size: 1.75rem; font-weight: 800; color: #1a1a1a; }
    .view-all-table { color: var(--color-editorial); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; text-decoration: none; }

    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.5rem 1rem; font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 1px solid #f8f9fa; }
    .arch-table td { padding: 1.5rem 1rem; border-bottom: 1px solid #f8f9fa; font-size: 0.85rem; font-weight: 600; vertical-align: middle; }

    .order-id-txt { color: #555; }
    .customer-profile { display: flex; align-items: center; gap: 1rem; }
    .avatar-mini { width: 34px; height: 34px; border-radius: 50%; background: #eef2f5; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; color: var(--color-editorial); }
    
    .status-arch-badge { padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .status-proc { background: #fdf2f2; color: #802030; }
    .status-ship { background: #f0fdf4; color: #166534; }

    .action-ellipsis { color: #adb5bd; cursor: pointer; font-size: 1.2rem; }

    .fab-plus-arch {
        position: absolute;
        bottom: 3.5rem;
        right: 3.5rem;
        width: 60px;
        height: 60px;
        background: var(--color-editorial);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 10px 25px rgba(128, 32, 48, 0.3);
        cursor: pointer;
        transition: transform 0.3s;
        border: none;
    }

    .fab-plus-arch:hover { transform: scale(1.1) rotate(90deg); }

    @media (max-width: 1200px) {
        .stats-main-grid { grid-template-columns: repeat(2, 1fr); }
        .performance-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="overview-header">
    <h1 class="overview-title">Overview</h1>
    <p class="overview-subtitle">Refined analytics for V’S Fashion Boutique.</p>
</div>

<!-- Stats Dashboard -->
<div class="stats-main-grid">
    <div class="stat-arch-card">
        <div class="stat-arch-label">Total Inventory</div>
        <div class="stat-arch-value">{{ number_format($inventoryCount ?? 1284) }}</div>
        <div class="stat-arch-meta stat-meta-positive">
            <i class="fas fa-chart-line"></i> +12% this month
        </div>
        <i class="fas fa-box-open stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Pending Orders</div>
        <div class="stat-arch-value">42</div>
        <div class="stat-arch-meta stat-meta-neutral">
            <i class="fas fa-clock"></i> 8 urgent delivery
        </div>
        <i class="fas fa-shopping-cart stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Daily Sales</div>
        <div class="stat-arch-value">₱14.2k</div>
        <div class="stat-arch-meta stat-meta-positive" style="color: #802030;">
            <i class="fas fa-award"></i> New record reached
        </div>
        <i class="fas fa-wallet stat-ghost-icon"></i>
    </div>

    <div class="stat-arch-card">
        <div class="stat-arch-label">Reports</div>
        <div class="stat-arch-value">18</div>
        <div class="stat-arch-meta stat-meta-neutral">
            <i class="fas fa-check-circle"></i> All insights synced
        </div>
        <i class="fas fa-chart-bar stat-ghost-icon"></i>
    </div>
</div>

<!-- Analytics Row -->
<div class="performance-grid">
    <div class="chart-perf-card">
        <div class="chart-perf-header">
            <div>
                <span class="chart-perf-label">Analytical Performance</span>
                <h2 class="chart-perf-title">Sales Performance</h2>
            </div>
            <div class="chart-toggle-pill">
                <div class="toggle-pill-item active">Weekly</div>
                <div class="toggle-pill-item">Monthly</div>
            </div>
        </div>

        <div class="arch-bars-container">
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-mauve" style="height: 45%;"></div>
                <span class="arch-bar-label">Mon</span>
            </div>
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-mauve" style="height: 65%;"></div>
                <span class="arch-bar-label">Tue</span>
            </div>
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-mauve" style="height: 35%;"></div>
                <span class="arch-bar-label">Wed</span>
            </div>
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-maroon" style="height: 80%;"></div>
                <span class="arch-bar-label">Thu</span>
            </div>
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-mauve" style="height: 55%;"></div>
                <span class="arch-bar-label">Fri</span>
            </div>
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-teal" style="height: 90%;"></div>
                <span class="arch-bar-label">Sat</span>
            </div>
            <div class="arch-bar-wrapper">
                <div class="arch-bar-item bar-mauve" style="height: 30%;"></div>
                <span class="arch-bar-label">Sun</span>
            </div>
        </div>
    </div>

    <div class="seasonal-archive-card">
        <div>
            <h3 class="seasonal-title">Seasonal Forecast:<br>V’S Summer Lookbook</h3>
            <p class="seasonal-desc">
                Anticipate a burst in luxury fashion demand. Restock your inventory to meet curated expectations.
            </p>
        </div>
        <a href="{{ route('inventory.index') }}" class="seasonal-action-btn">Adjust Stock</a>
    </div>
</div>

<!-- Data Table Section -->
<div class="orders-section-card">
    <div class="section-header-flex">
        <h2 class="section-title">Recent Orders</h2>
        <a href="{{ route('sales.index') }}" class="view-all-table">View All</a>
    </div>

    <table class="arch-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="order-id-txt">#ARC-9281</span></td>
                <td>
                    <div class="customer-profile">
                        <div class="avatar-mini">ES</div>
                        <span>Eleanor Shellstrop</span>
                    </div>
                </td>
                <td style="font-weight: 800; color: #1a1a1a;">₱1,240.00</td>
                <td><span class="status-arch-badge status-proc">Processing</span></td>
                <td><i class="fas fa-ellipsis-h action-ellipsis"></i></td>
            </tr>
            <tr>
                <td><span class="order-id-txt">#ARC-9280</span></td>
                <td>
                    <div class="customer-profile">
                        <div class="avatar-mini" style="background: #fdf2f4; color: #802030;">CA</div>
                        <span>Chidi Anagonye</span>
                    </div>
                </td>
                <td style="font-weight: 800; color: #1a1a1a;">₱890.00</td>
                <td><span class="status-arch-badge status-ship">Shipped</span></td>
                <td><i class="fas fa-ellipsis-h action-ellipsis"></i></td>
            </tr>
            <tr>
                <td><span class="order-id-txt">#ARC-9279</span></td>
                <td>
                    <div class="customer-profile">
                        <div class="avatar-mini" style="background: #eef2f5; color: #3c5e5e;">JT</div>
                        <span>Jason Teague</span>
                    </div>
                </td>
                <td style="font-weight: 800; color: #1a1a1a;">₱450.00</td>
                <td><span class="status-arch-badge status-proc">Processing</span></td>
                <td><i class="fas fa-ellipsis-h action-ellipsis"></i></td>
            </tr>
            <tr>
                <td><span class="order-id-txt">#ARC-9278</span></td>
                <td>
                    <div class="customer-profile">
                        <div class="avatar-mini" style="background: #fdf2f4; color: #802030;">TA</div>
                        <span>Tahani Al-Jamil</span>
                    </div>
                </td>
                <td style="font-weight: 800; color: #1a1a1a;">₱3,120.00</td>
                <td><span class="status-arch-badge status-ship">Shipped</span></td>
                <td><i class="fas fa-ellipsis-h action-ellipsis"></i></td>
            </tr>
        </tbody>
    </table>

    <button class="fab-plus-arch" onclick="window.location.href='{{ route('sales.create') }}'">
        <i class="fas fa-plus"></i>
    </button>
</div>
</div>
@endsection
