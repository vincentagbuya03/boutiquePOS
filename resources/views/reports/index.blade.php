@extends('layouts.app')

@section('title', 'Reports & Insights')

@section('styles')
<style>
    :root {
        --color-editorial: #802030;
        --color-success: #10b981;
        --color-warning: #f59e0b;
        --color-danger: #ef4444;
        --color-info: #3b82f6;
    }

    .reports-header {
        margin-bottom: 3rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .reports-title {
        font-size: 3rem;
        font-weight: 800;
        color: #1a1a1a;
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }

    .reports-subtitle {
        color: #999;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .date-range-picker {
        display: flex;
        gap: 1rem;
        align-items: center;
        background: white;
        padding: 1.25rem 2rem;
        border-radius: 16px;
        border: 1px solid #f1f1f1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .date-range-picker input {
        border: 1px solid #eee;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .date-range-picker input:focus {
        border-color: var(--color-editorial);
        box-shadow: 0 0 0 3px rgba(128, 32, 48, 0.1);
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .kpi-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid #f1f1f1;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        border-color: rgba(128, 32, 48, 0.1);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--color-editorial), #c299a0);
    }

    .kpi-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .kpi-icon.sales {
        background: #fef3c7;
        color: var(--color-warning);
    }

    .kpi-icon.profit {
        background: #dcfce7;
        color: var(--color-success);
    }

    .kpi-icon.inventory {
        background: #e0e7ff;
        color: var(--color-info);
    }

    .kpi-icon.returns {
        background: #fee2e2;
        color: var(--color-danger);
    }

    .kpi-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.75rem;
        display: block;
    }

    .kpi-value {
        font-size: 2.25rem;
        font-weight: 900;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
        line-height: 1;
        letter-spacing: -0.03em;
    }

    .kpi-meta {
        font-size: 0.85rem;
        color: #adb5bd;
        font-weight: 600;
    }

    .kpi-trend {
        display: inline-block;
        margin-left: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
    }

    .kpi-trend.up {
        background: #f0fdf4;
        color: var(--color-success);
    }

    .kpi-trend.down {
        background: #fee2e2;
        color: var(--color-danger);
    }
    .kpi-trend.neutral {
        background: #f3f4f6;
        color: #6b7280;
    }
    .reports-section {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f1f1;
    }

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .report-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid #f1f1f1;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(128, 32, 48, 0.02) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .report-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: var(--color-editorial);
    }

    .report-card:hover::before {
        opacity: 1;
    }

    .report-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, rgba(128, 32, 48, 0.1) 0%, rgba(128, 32, 48, 0.05) 100%);
        color: var(--color-editorial);
        position: relative;
        z-index: 1;
    }

    .report-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .report-description {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 2rem;
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .report-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .report-tag {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: #f8f9fa;
        border-radius: 100px;
        font-size: 0.65rem;
        font-weight: 700;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .report-arrow {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(128, 32, 48, 0.1) 0%, rgba(128, 32, 48, 0.05) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-editorial);
        font-size: 1.1rem;
        transition: all 0.3s;
    }

    .report-card:hover .report-arrow {
        transform: translateX(4px);
        background: linear-gradient(135deg, rgba(128, 32, 48, 0.2) 0%, rgba(128, 32, 48, 0.1) 100%);
    }

    .quick-access {
        background: linear-gradient(135deg, var(--color-editorial) 0%, #c299a0 100%);
        border-radius: 24px;
        padding: 3rem;
        color: white;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .quick-access::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .quick-access-content {
        position: relative;
        z-index: 2;
    }

    .quick-access h2 {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .quick-access p {
        font-size: 1.05rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }

    .quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-link {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        padding: 1.25rem;
        text-decoration: none;
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .quick-link:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-2px);
    }

    .charts-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .chart-card {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        border: 1px solid #f1f1f1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chart-placeholder {
        width: 100%;
        height: 250px;
        background: #fff;
        border-radius: 16px;
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
        padding: 2rem;
        position: relative;
    }

    .sim-bar {
        width: 15%;
        background: var(--color-editorial);
        border-radius: 8px 8px 0 0;
        transition: height 1s ease-out;
        opacity: 0.15;
    }

    .sim-bar:nth-child(2n) { opacity: 0.05; }
    .sim-bar:hover { opacity: 1; }

    @media (max-width: 768px) {
        .reports-header {
            flex-direction: column;
            gap: 2rem;
        }

        .date-range-picker {
            flex-direction: column;
            align-items: stretch;
        }

        .date-range-picker input {
            width: 100%;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .reports-grid {
            grid-template-columns: 1fr;
        }

        .charts-section {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="reports-header">
    <div>
        <h1 class="reports-title">V'S Insights</h1>
        <p class="reports-subtitle">Intelligence Archive & Business Analytics</p>
    </div>
    <div class="date-range-picker">
        <label style="font-size: 0.85rem; font-weight: 700; color: #666;">Date Range:</label>
        <input type="date" id="startDate" style="max-width: 150px;" value="{{ now()->subMonth()->format('Y-m-d') }}">
        <span style="color: #adb5bd; font-weight: 600;">to</span>
        <input type="date" id="endDate" style="max-width: 150px;" value="{{ now()->format('Y-m-d') }}">
    </div>
</div>

<!-- Quick Access Banner -->
<div class="quick-access">
    <div class="quick-access-content">
        <h2>Business Intelligence</h2>
        <p>Analyze sales dynamics, stock valuation, and operational profitability.</p>
        <div class="quick-links">
            <a href="{{ route('reports.inventory') }}" class="quick-link">
                <i class="fas fa-boxes"></i> Inventory Report
            </a>
            <a href="{{ route('reports.sales') }}" class="quick-link">
                <i class="fas fa-chart-line"></i> Sales Report
            </a>
            <a href="{{ route('reports.profit') }}" class="quick-link">
                <i class="fas fa-coins"></i> Profit Analysis
            </a>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon sales">
            <i class="fas fa-credit-card"></i>
        </div>
        <span class="kpi-label">Total Sales (30 Days)</span>
        <div class="kpi-value">₱{{ number_format($totalSales ?? 0, 0) }}</div>
        <div class="kpi-meta">
            <span>{{ $totalTransactions }} transactions</span>
            <span class="kpi-trend {{ $salesTrend > 0 ? 'up' : ($salesTrend < 0 ? 'down' : 'neutral') }}">
                <i class="fas fa-arrow-{{ $salesTrend > 0 ? 'up' : ($salesTrend < 0 ? 'down' : 'right') }}"></i> 
                @if($salesTrend == 0)
                    No prior data
                @else
                    {{ number_format(abs($salesTrend), 1) }}%
                @endif
            </span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon profit">
            <i class="fas fa-chart-pie"></i>
        </div>
        <span class="kpi-label">Total Profit</span>
        <div class="kpi-value">₱{{ number_format($totalProfit ?? 0, 0) }}</div>
        <div class="kpi-meta">
            <span>Vs. Cost price</span>
            <span class="kpi-tag" style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 800; color: #adb5bd; margin-left: 0.5rem;">REAL-TIME</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon inventory">
            <i class="fas fa-warehouse"></i>
        </div>
        <span class="kpi-label">Total Stock Value</span>
        <div class="kpi-value">₱{{ number_format($inventoryValue ?? 0, 0) }}</div>
        <div class="kpi-meta">
            <span>{{ $totalInventoryItems }} pieces in stock</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon returns">
            <i class="fas fa-undo"></i>
        </div>
        <span class="kpi-label">Returns & Refunds</span>
        <div class="kpi-value">₱{{ number_format($totalReturns ?? 0, 0) }}</div>
        <div class="kpi-meta">
            <span>{{ $returnCount }} claims processed</span>
        </div>
    </div>
</div>

<!-- Reports Section -->
<div class="reports-section">
    <h2 class="section-title">Available Reports</h2>
    <div class="reports-grid">
        <!-- Inventory Report -->
        <a href="{{ route('reports.inventory') }}" style="text-decoration: none;">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 class="report-title">Inventory Report</h3>
                <p class="report-description">
                    Track stock levels, valuations, and identify products below reorder thresholds
                </p>
                <div class="report-footer">
                    <span class="report-tag">Quarterly</span>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}" style="text-decoration: none;">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="report-title">Sales Report</h3>
                <p class="report-description">
                    Analyze sales trends, daily/weekly/monthly performance, and transaction details
                </p>
                <div class="report-footer">
                    <span class="report-tag">Daily</span>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Profit Analysis -->
        <a href="{{ route('reports.profit') }}" style="text-decoration: none;">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="report-title">Profit Analysis</h3>
                <p class="report-description">
                    Detailed profit calculations, top-performing products, and cost analysis
                </p>
                <div class="report-footer">
                    <span class="report-tag">Monthly</span>
                    <div class="report-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Charts Section -->
<div class="reports-section">
    <h2 class="section-title">Performance Overview</h2>
    <div class="charts-section">
        <div class="chart-card">
            <div class="chart-title">
                <span>Sales Trend</span>
                <span class="report-tag">30 Days</span>
            </div>
            <div class="chart-placeholder">
                @php $maxDaily = $dailySales->max('total') ?: 1; @endphp
                @foreach($dailySales as $day)
                    <div class="sim-bar" style="height: {{ max(10, ($day->total / $maxDaily) * 100) }}%;" title="{{ $day->date }}: ₱{{ number_format($day->total, 2) }}"></div>
                @endforeach
                @if($dailySales->isEmpty())
                    <span style="color: #adb5bd; font-size: 0.8rem;">No data for this period</span>
                @endif
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-title">
                <span>Top Categories</span>
                <span class="report-tag">Revenue</span>
            </div>
            <div class="chart-placeholder">
                @php $maxCat = $categorySales->max() ?: 1; @endphp
                @foreach($categorySales as $name => $total)
                    <div class="sim-bar" style="height: {{ max(10, ($total / $maxCat) * 100) }}%; background: #3c5e5e;" title="{{ $name }}: ₱{{ number_format($total, 2) }}"></div>
                @endforeach
                @if($categorySales->isEmpty())
                    <span style="color: #adb5bd; font-size: 0.8rem;">No category data</span>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');

        startDate.addEventListener('change', function() {
            // Handle date range change
            console.log('Date range changed:', startDate.value, 'to', endDate.value);
        });

        endDate.addEventListener('change', function() {
            // Handle date range change
            console.log('Date range changed:', startDate.value, 'to', endDate.value);
        });

        // Add report card click animations
        document.querySelectorAll('.report-card').forEach(card => {
            card.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.98)';
            });

            card.addEventListener('mouseup', function() {
                this.style.transform = '';
            });
        });
    });
</script>
@endsection
