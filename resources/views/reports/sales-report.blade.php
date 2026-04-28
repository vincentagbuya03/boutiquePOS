@extends('layouts.app')

@section('title', "Sales Intelligence - V'S Fashion")

@section('styles')
<style>
    .report-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .report-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .report-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .filter-ribbon {
        background: white;
        padding: 1.5rem 2.5rem;
        border-radius: 100px;
        border: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .filter-group { display: flex; align-items: center; gap: 0.75rem; }
    .filter-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; }
    .filter-input { border: none; background: #f8f9fa; padding: 0.6rem 1rem; border-radius: 10px; font-size: 0.8rem; font-weight: 700; color: #495057; outline: none; }

    .stats-editorial {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .editorial-stat-card {
        background: white;
        padding: 2.5rem;
        border-radius: 24px;
        border: 1px solid var(--color-border);
        position: relative;
    }

    .stat-arch-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 1rem; display: block; }
    .stat-arch-value { font-size: 1.75rem; font-weight: 900; color: #1a1a1a; letter-spacing: -0.03em; }

    .arch-table-shell {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        overflow: hidden;
    }

    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.5rem 2rem; font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; border-bottom: 1px solid #f1f1f1; }
    .arch-table td { padding: 1.5rem 2rem; font-size: 0.85rem; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #f8f9fa; }

    .btn-arch-view { 
        padding: 0.5rem 1.25rem; 
        background: #fdf2f4; 
        color: var(--color-editorial); 
        border-radius: 100px; 
        text-decoration: none; 
        font-size: 0.7rem; 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: 0.05em;
        transition: all 0.2s;
    }
    .btn-arch-view:hover { background: var(--color-editorial); color: white; }

    @media print {
        @page { margin: 1.5cm; }
        body { background: white !important; color: black !important; }
        .sidebar, .top-navbar, .filter-ribbon, .btn-print, .report-header, .btn-arch-view { display: none !important; }
        .print-only-header { display: block !important; }
        .main-workspace, .workspace-scroll { overflow: visible !important; padding: 0 !important; display: block !important; }
        .editorial-stat-card { border: 1px solid #eee !important; margin-bottom: 2rem !important; }
        .arch-table-shell { border: 1px solid #eee !important; border-radius: 0 !important; }
        .report-footer { margin-top: 3rem !important; padding-top: 2rem !important; page-break-inside: avoid; }
        img { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endsection

@section('content')
<div class="print-only-header" style="display: none;">
    <div style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #1a1a1a; padding-bottom: 2rem;">
        <div style="font-family: 'Bodoni Moda', serif; font-size: 2.5rem; font-weight: 900; color: #802030; margin-bottom: 0.5rem; letter-spacing: -0.05em;">V’S Fashion</div>
        <div style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem;">Sales Performance Audit</div>
        <div style="font-size: 0.85rem; color: #666; font-weight: 500;">
            San Carlos City, Pangasinan<br>
            Contact: +63 09158969268 • Official Financial Document
        </div>
    </div>
</div>

<div class="report-header">
    <div>
        <h1 class="report-title">Sales Intelligence</h1>
        <p class="report-subtitle">Historical performance data and transaction analytics.</p>
    </div>
    <button onclick="window.print()" class="btn-print" style="background: var(--color-editorial); color: white; padding: 1rem 2.5rem; border-radius: 100px; border: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; cursor: pointer;">
        <i class="fas fa-print" style="margin-right: 0.5rem;"></i> Generate Report
    </button>
</div>

<form action="{{ route('reports.sales') }}" method="GET" class="filter-ribbon">
    <div class="filter-group">
        <span class="filter-label">Period</span>
        <input type="date" name="start_date" class="filter-input" value="{{ request('start_date') ?? now()->subMonth()->format('Y-m-d') }}">
        <span style="color: #eee;">to</span>
        <input type="date" name="end_date" class="filter-input" value="{{ request('end_date') ?? now()->format('Y-m-d') }}">
    </div>
    
    <div class="filter-group">
        <span class="filter-label">Frequency</span>
        <select name="period" class="filter-input">
            <option value="">All Transactions</option>
            <option value="daily" @if(request('period') === 'daily') selected @endif>Daily Aggregates</option>
            <option value="weekly" @if(request('period') === 'weekly') selected @endif>Weekly View</option>
            <option value="monthly" @if(request('period') === 'monthly') selected @endif>Monthly Overview</option>
        </select>
    </div>

    <button type="submit" style="margin-left: auto; background: none; border: none; color: var(--color-editorial); font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer;">
        Apply Filters <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
    </button>
</form>

<div class="stats-editorial">
    <div class="editorial-stat-card">
        <span class="stat-arch-label">Gross Volume</span>
        <div class="stat-arch-value">₱{{ number_format($summary['total_sales'], 2) }}</div>
    </div>
    <div class="editorial-stat-card">
        <span class="stat-arch-label">Acquisitions</span>
        <div class="stat-arch-value">{{ $summary['total_transactions'] }} <span style="font-size: 0.8rem; color: #adb5bd;">Sales</span></div>
    </div>
    <div class="editorial-stat-card">
        <span class="stat-arch-label">Items Curated</span>
        <div class="stat-arch-value">{{ $summary['total_items_sold'] }} <span style="font-size: 0.8rem; color: #adb5bd;">Pieces</span></div>
    </div>
    <div class="editorial-stat-card">
        <span class="stat-arch-label">Average Order</span>
        <div class="stat-arch-value">₱{{ number_format($summary['average_transaction'], 2) }}</div>
    </div>
</div>

<div class="arch-table-shell">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Date of Acquisition</th>
                <th>Sequence ID</th>
                <th>Curator</th>
                <th>Branch</th>
                <th>Total Sum</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td style="font-weight: 800;">{{ $sale->date_sold->format('M d, Y') }}</td>
                <td style="font-family: monospace; color: #adb5bd;">#{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->branch }}</td>
                <td style="font-weight: 800; color: var(--color-editorial);">₱{{ number_format($sale->total_amount, 2) }}</td>
                <td style="text-align: right;">
                    <a href="{{ route('sales.show', $sale) }}" class="btn-arch-view">View Identity</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 5rem 0; color: #adb5bd;">
                    <i class="fas fa-search" style="font-size: 3rem; opacity: 0.1; display: block; margin-bottom: 1.5rem;"></i>
                    No acquisition records found for this sequence.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
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
