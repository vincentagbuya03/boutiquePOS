@extends('layouts.app')

@section('title', "Profit Analysis - V'S Fashion")

@section('styles')
<style>
    .profit-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .profit-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .profit-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .analysis-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 2.5rem; margin-bottom: 4rem; }

    .summary-card {
        background: white;
        padding: 3rem;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
    }

    .profit-indicator {
        margin: 2.5rem 0;
        padding: 2.5rem;
        background: #fdf2f4;
        border-radius: 20px;
        text-align: center;
    }

    .indicator-label { font-size: 0.75rem; font-weight: 800; color: #802030; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 1rem; display: block; }
    .indicator-value { font-size: 3rem; font-weight: 900; color: #1a1a1a; letter-spacing: -0.05em; }

    .mini-stat { display: flex; justify-content: space-between; padding: 1.25rem 0; border-bottom: 1px solid #f8f9fa; font-weight: 600; font-size: 0.9rem; }
    .mini-stat:last-child { border-bottom: none; }
    .mini-stat span:first-child { color: #999; }
    .mini-stat span:last-child { color: #1a1a1a; }

    .top-products-card {
        background: white;
        border-radius: 30px;
        padding: 3rem;
        border: 1px solid var(--color-border);
    }

    .product-rank-item { display: flex; align-items: center; gap: 1.5rem; padding: 1.5rem 0; border-bottom: 1px solid #f8f9fa; }
    .product-rank-item:last-child { border-bottom: none; }
    
    .rank-number { width: 32px; font-weight: 900; font-size: 1.25rem; color: #eee; }
    .product-info { flex: 1; }
    .product-name { font-size: 1rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.25rem; }
    .product-meta { font-size: 0.75rem; color: #adb5bd; font-weight: 600; }
    .revenue-val { font-weight: 800; color: var(--color-editorial); font-size: 1rem; }

    @media print {
        @page { margin: 1.5cm; }
        body { background: white !important; color: black !important; }
        .sidebar, .top-navbar, .btn-print, .filter-form, .profit-header { display: none !important; }
        .print-only-header { display: block !important; }
        .main-workspace, .workspace-scroll { overflow: visible !important; padding: 0 !important; display: block !important; }
        .summary-card, .top-products-card { border: 1px solid #eee !important; box-shadow: none !important; margin-bottom: 2rem !important; }
        .analysis-grid { grid-template-columns: 1fr; gap: 2rem; }
        .report-footer { margin-top: 3rem !important; padding-top: 2rem !important; page-break-inside: avoid; }
        img { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endsection

@section('content')
<div class="print-only-header" style="display: none;">
    <div style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #1a1a1a; padding-bottom: 2rem;">
        <div style="font-family: 'Bodoni Moda', serif; font-size: 2.5rem; font-weight: 900; color: #802030; margin-bottom: 0.5rem; letter-spacing: -0.05em;">V’S Fashion</div>
        <div style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem;">Financial Profitability Analysis</div>
        <div style="font-size: 0.85rem; color: #666; font-weight: 500;">
            San Carlos City, Pangasinan<br>
            Contact: +63 09158969268 • Confidential Management Document
        </div>
    </div>
</div>

<div class="profit-header">
    <div>
        <h1 class="profit-title">Profit Analysis</h1>
        <p class="profit-subtitle">Analytical breakdown of revenue and gross margins.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <form action="{{ route('reports.profit') }}" method="GET" class="filter-form" style="display: flex; gap: 0.5rem; align-items: center; background: white; padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid #eee;">
            <input type="date" name="start_date" value="{{ $validated['start_date'] ?? now()->subMonth()->format('Y-m-d') }}" style="border: none; outline: none; font-weight: 600; font-size: 0.8rem; color: #666;">
            <span style="color: #eee;">|</span>
            <input type="date" name="end_date" value="{{ $validated['end_date'] ?? now()->format('Y-m-d') }}" style="border: none; outline: none; font-weight: 600; font-size: 0.8rem; color: #666;">
            <button type="submit" style="background: none; border: none; color: var(--color-editorial); cursor: pointer; padding: 0 0.5rem;"><i class="fas fa-sync-alt"></i></button>
        </form>
        <button onclick="window.print()" class="btn-print" style="background: var(--color-editorial); color: white; padding: 0.85rem 2rem; border-radius: 100px; border: none; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer;">
            <i class="fas fa-print" style="margin-right: 0.5rem;"></i> Print Analysis
        </button>
    </div>
</div>

<div class="analysis-grid">
    <!-- Left: Financial Summary -->
    <div class="summary-card">
        <h2 style="font-size: 1.15rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem;">Financial Core</h2>
        <p style="font-size: 0.85rem; color: #999; font-weight: 500;">Aggregated data for the selected period.</p>

        <div class="profit-indicator">
            <span class="indicator-label">Gross Profit</span>
            <div class="indicator-value">₱{{ number_format($grossProfit, 2) }}</div>
        </div>

        <div class="mini-stats-list">
            <div class="mini-stat">
                <span>Total Revenue</span>
                <span>₱{{ number_format($sales->total_sales ?? 0, 2) }}</span>
            </div>
            <div class="mini-stat">
                <span>Cost of Goods (COGS)</span>
                <span style="color: #666;">- ₱{{ number_format($totalCost, 2) }}</span>
            </div>
            <div class="mini-stat">
                <span>Total Refunds</span>
                <span style="color: #802030;">- ₱{{ number_format($refunds->total_refunds ?? 0, 2) }}</span>
            </div>
            <div class="mini-stat">
                <span>Calculated Margin</span>
                @php $margin = ($sales->total_sales ?? 0) > 0 ? ($grossProfit / $sales->total_sales) * 100 : 0; @endphp
                <span style="color: #3c5e5e;">{{ number_format($margin, 1) }}%</span>
            </div>
        </div>

        <div style="margin-top: 3rem; padding: 1.5rem; background: #f8f9fa; border-radius: 16px; font-size: 0.75rem; color: #666; font-weight: 500; line-height: 1.6;">
            <i class="fas fa-info-circle" style="color: var(--color-editorial); margin-right: 0.5rem;"></i>
            Gross profit is calculated as (Revenue - COGS - Refunds). This provides a real look at your product profitability.
        </div>
    </div>

    <!-- Right: Top Products -->
    <div class="top-products-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem;">Top Curated Pieces</h2>
                <p style="font-size: 0.85rem; color: #999; font-weight: 500;">Highest revenue generating products.</p>
            </div>
            <span style="font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; background: #f8f9fa; padding: 0.4rem 1rem; border-radius: 100px;">Ranked by Rev</span>
        </div>

        <div class="product-ranking">
            @forelse($topProducts as $id => $data)
            <div class="product-rank-item">
                <div class="rank-number">#{{ $loop->iteration }}</div>
                <div class="product-info">
                    <div class="product-name">{{ $data['product']->name ?? 'Archived Piece' }}</div>
                    <div class="product-meta">Volume: {{ $data['quantity'] }} Sold • Category: {{ $data['product']->category->name ?? 'Collection' }}</div>
                </div>
                <div class="revenue-val">₱{{ number_format($data['revenue'], 2) }}</div>
            </div>
            @empty
            <div style="text-align: center; padding: 5rem 0; color: #adb5bd;">
                <i class="fas fa-chart-line" style="font-size: 3rem; opacity: 0.1; display: block; margin-bottom: 1.5rem;"></i>
                No transaction data available for this sequence.
            </div>
            @endforelse
        </div>
    </div>
</div>

<div style="background: white; border-radius: 30px; padding: 3rem; border: 1px solid var(--color-border);">
    <h2 style="font-size: 1.15rem; font-weight: 800; color: #1a1a1a; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-lightbulb" style="color: var(--color-editorial);"></i>
        Business Insights
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
        <div style="padding: 1.5rem; background: #fdf2f4; border-radius: 20px; border-left: 4px solid var(--color-editorial);">
            <h4 style="font-size: 0.8rem; font-weight: 800; color: var(--color-editorial); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Profitability</h4>
            <p style="font-size: 0.85rem; color: #666; font-weight: 500;">Your current gross margin of {{ number_format($margin, 1) }}% indicates healthy revenue retention across collections.</p>
        </div>
        <div style="padding: 1.5rem; background: #f0f6f6; border-radius: 20px; border-left: 4px solid #3c5e5e;">
            <h4 style="font-size: 0.8rem; font-weight: 800; color: #3c5e5e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Refund Rate</h4>
            @php $refundRate = ($sales->total_sales ?? 0) > 0 ? (($refunds->total_refunds ?? 0) / $sales->total_sales) * 100 : 0; @endphp
            <p style="font-size: 0.85rem; color: #666; font-weight: 500;">Refunds account for {{ number_format($refundRate, 1) }}% of total gross sales in this period.</p>
        </div>
        <div style="padding: 1.5rem; background: #fffbeb; border-radius: 20px; border-left: 4px solid #f59e0b;">
            <h4 style="font-size: 0.8rem; font-weight: 800; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Top Performer</h4>
            <p style="font-size: 0.85rem; color: #666; font-weight: 500;">
                @if($topProducts->first())
                    <strong>{{ $topProducts->first()['product']->name }}</strong> is your highest revenue driver, contributing ₱{{ number_format($topProducts->first()['revenue'], 2) }}.
                @else
                    Insufficient data to identify top performers.
                @endif
            </p>
        </div>
    </div>
</div>

<div class="report-footer" style="margin-top: 4rem; display: flex; justify-content: flex-end; page-break-inside: avoid;">
        <div style="text-align: right; min-width: 250px;">
            <div style="position: relative; display: inline-block;">
                <img src="{{ asset('assets/signatures/owner-signature.png') }}" style="height: 110px; position: absolute; top: -60px; left: 50%; transform: translateX(-50%) rotate(-3deg); z-index: 1; opacity: 0.9; pointer-events: none;">
                <strong style="font-size: 1.5rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.01em; position: relative; z-index: 0; display: block; border-top: 2px solid #f8f9fa; padding-top: 0.5rem; width: 220px; margin: 0 auto;">{{ App\Models\User::getOwnerName() }}</strong>
                <p style="font-size: 0.75rem; color: #adb5bd; font-weight: 700; margin-top: 0.2rem; text-transform: uppercase; letter-spacing: 0.1em;">Boutique Owner</p>
            </div>
        </div>
</div>

<div style="margin-top: 4rem; text-align: center; color: #adb5bd; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.2em;">
    Generated on {{ date('F d, Y - h:i A') }} • Official V'S Fashion Audit Document
</div>
@endsection
