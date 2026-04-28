@extends('layouts.app')

@section('title', "Inventory Intelligence - V'S Fashion")

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

    .stats-editorial {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .editorial-stat-card {
        background: white;
        padding: 2.5rem;
        border-radius: 24px;
        border: 1px solid var(--color-border);
    }

    .stat-arch-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 1rem; display: block; }
    .stat-arch-value { font-size: 2.25rem; font-weight: 900; color: #1a1a1a; letter-spacing: -0.03em; }

    .arch-table-shell {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        overflow: hidden;
    }

    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.5rem 2rem; font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; border-bottom: 1px solid #f1f1f1; }
    .arch-table td { padding: 1.5rem 2rem; font-size: 0.85rem; font-weight: 600; color: #1a1a1a; border-bottom: 1px solid #f8f9fa; }

    .badge-arch { padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-optimal { background: #f0f6f6; color: #3c5e5e; }
    .badge-critical { background: #fdf2f4; color: #802030; }

    @media print {
        @page { margin: 1.5cm; }
        body { background: white !important; color: black !important; }
        .sidebar, .top-navbar, .filter-ribbon, .btn-print, .report-header, .pos-action-tray { display: none !important; }
        .print-only-header { display: block !important; }
        .main-workspace, .workspace-scroll { overflow: visible !important; padding: 0 !important; display: block !important; }
        .inventory-report-container { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
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
        <div style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem;">Boutique Management Audit</div>
        <div style="font-size: 0.85rem; color: #666; font-weight: 500;">
            San Carlos City, Pangasinan<br>
            Contact: +63 09158969268 • Official Business Document
        </div>
    </div>
</div>

<div class="report-header">
    <div>
        <h1 class="report-title">Inventory Intelligence</h1>
        <p class="report-subtitle">Real-time stock valuation and availability analysis.</p>
    </div>
    <button onclick="window.print()" class="btn-print" style="background: var(--color-editorial); color: white; padding: 1rem 2.5rem; border-radius: 100px; border: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; cursor: pointer;">
        <i class="fas fa-print" style="margin-right: 0.5rem;"></i> Generate Audit
    </button>
</div>

<form action="{{ route('reports.inventory') }}" method="GET" class="filter-ribbon">
    <label style="display: flex; align-items: center; gap: 1rem; cursor: pointer; font-size: 0.85rem; font-weight: 700; color: #666;">
        <input type="checkbox" name="low_stock" value="1" @if(request('low_stock')) checked @endif onchange="this.form.submit()" style="accent-color: var(--color-editorial); width: 1.2rem; height: 1.2rem;">
        Focus on Critical Stock Only
    </label>
    
    @if(auth()->user()->isOwner())
    <div style="margin-left: auto; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase;">Filter Branch</span>
        <select name="branch" onchange="this.form.submit()" style="border: none; background: #f8f9fa; padding: 0.6rem 1.5rem; border-radius: 100px; font-size: 0.8rem; font-weight: 700; color: #1a1a1a; outline: none;">
            <option value="">Global Network</option>
            <option value="San Carlos" @if(request('branch') === 'San Carlos') selected @endif>San Carlos</option>
        </select>
    </div>
    @endif
</form>

<div class="stats-editorial">
    <div class="editorial-stat-card">
        <span class="stat-arch-label">Total Asset Value</span>
        <div class="stat-arch-value">₱{{ number_format($totalValue, 2) }}</div>
    </div>
    <div class="editorial-stat-card">
        <span class="stat-arch-label">Stock Alerts</span>
        <div class="stat-arch-value" style="color: var(--color-editorial);">{{ $lowStockCount }} <span style="font-size: 0.8rem; color: #adb5bd;">Critical Items</span></div>
    </div>
</div>

<div class="arch-table-shell">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Batch ID</th>
                <th>Piece Descriptor</th>
                <th>Supplier</th>
                <th>Stock Volume</th>
                <th>Market Price</th>
                <th>Batch Valuation</th>
                <th>Received</th>
            </tr>
        </thead>
        <tbody>
            @forelse($batches as $batch)
            <tr>
                <td style="font-family: monospace; color: #adb5bd;">#{{ $batch->batch_number }}</td>
                <td style="font-weight: 800;">{{ $batch->product->name }}</td>
                <td>{{ $batch->supplier->name ?? 'N/A' }}</td>
                <td style="font-weight: 700;">{{ $batch->quantity }} <span style="font-weight: 500; color: #adb5bd; font-size: 0.75rem;">pcs</span></td>
                <td>₱{{ number_format($batch->selling_price, 2) }}</td>
                <td style="font-weight: 800; color: var(--color-editorial);">₱{{ number_format($batch->quantity * $batch->selling_price, 2) }}</td>
                <td style="font-size: 0.75rem; color: #adb5bd;">{{ $batch->date_received->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 5rem 0; color: #adb5bd;">
                    <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.1; display: block; margin-bottom: 1.5rem;"></i>
                    No batch identities found in this sequence.
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
