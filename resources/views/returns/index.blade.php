@extends('layouts.app')

@section('title', 'Returns & Claims Archive')

@section('styles')
<style>
    .returns-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .view-header {
        margin-bottom: 3rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 20px;
        border: 1px solid var(--color-border);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .stat-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--color-editorial);
    }

    .returns-table-wrapper {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--color-border);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    .returns-table {
        width: 100%;
        border-collapse: collapse;
    }

    .returns-table th {
        text-align: left;
        padding: 1.25rem 2rem;
        font-size: 0.65rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-bottom: 1px solid #f8f9fa;
        background: #fdfdfd;
    }

    .returns-table td {
        padding: 1.5rem 2rem;
        font-size: 0.9rem;
        color: #1a1a1a;
        border-bottom: 1px solid #f8f9fa;
    }

    .returns-table tr:last-child td {
        border-bottom: none;
    }

    .returns-table tr:hover td {
        background: #fdf2f4/20;
    }

    .claim-id {
        font-family: 'Inter', monospace;
        font-weight: 800;
        color: #adb5bd;
        font-size: 0.8rem;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .product-name {
        font-weight: 700;
        color: #1a1a1a;
    }

    .product-category {
        font-size: 0.7rem;
        color: #adb5bd;
        text-transform: uppercase;
        font-weight: 800;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 1rem;
        border-radius: 100px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending { background: #fffbeb; color: #92400e; }
    .status-approved { background: #f0fdf4; color: #166534; }
    .status-rejected { background: #fef2f2; color: #991b1b; }
    .status-completed { background: #fdf2f4; color: var(--color-editorial); }

    .btn-create {
        background: var(--color-editorial);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 100px;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(128, 32, 48, 0.2);
    }

    .btn-create:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(128, 32, 48, 0.3);
    }

    .action-link {
        color: #adb5bd;
        transition: all 0.2s;
        font-size: 1.1rem;
    }

    .action-link:hover {
        color: var(--color-editorial);
        transform: translateX(3px);
    }

    .empty-state {
        text-align: center;
        padding: 8rem 2rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #f1f1f1;
        margin-bottom: 2rem;
    }
    @media print {
        @page { margin: 1.5cm; size: auto; }
        body { background: white !important; color: black !important; font-size: 10pt; }
        .sidebar, .top-navbar, .btn-create, .action-link, .view-header, .no-print { display: none !important; }
        .print-only-header { display: block !important; }
        .main-workspace, .workspace-scroll { overflow: visible !important; padding: 0 !important; margin: 0 !important; display: block !important; }
        .stats-grid { display: flex !important; flex-wrap: wrap; gap: 1rem !important; margin-bottom: 2rem !important; }
        .stat-card { border: 1px solid #eee !important; margin-bottom: 0 !important; padding: 1.5rem !important; flex: 1; border-radius: 12px !important; box-shadow: none !important; transform: none !important; }
        .returns-table-wrapper { border: none !important; border-radius: 0 !important; overflow: visible !important; box-shadow: none !important; }
        .returns-table { border: 1px solid #eee !important; }
        .returns-table th { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; border-bottom: 2px solid #000 !important; color: #000 !important; }
        .returns-table td { border-bottom: 1px solid #eee !important; }
        .returns-table tr { page-break-inside: avoid !important; }
        .report-footer { margin-top: 5rem !important; page-break-inside: avoid !important; }
        img { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endsection

@section('content')
<div class="print-only-header" style="display: none;">
    <div style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #1a1a1a; padding-bottom: 2rem;">
        <div style="font-family: 'Bodoni Moda', serif; font-size: 2.5rem; font-weight: 900; color: #802030; margin-bottom: 0.5rem; letter-spacing: -0.05em;">V’S Fashion</div>
        <div style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem;">Returns & Reversals Audit</div>
        <div style="font-size: 0.85rem; color: #666; font-weight: 500;">
            San Carlos City, Pangasinan<br>
            Contact: +63 09158969268 • Official Quality Control Document
        </div>
    </div>
</div>

<div class="returns-container">
    <div class="view-header">
        <div>
            <h1 class="view-content-title">Returns & Claims</h1>
            <p class="view-content-subtitle">Audit trail for product reversals and customer quality claims</p>
        </div>
        @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isCashier())
            <a href="{{ route('returns.create') }}" class="btn-create">
                <i class="fas fa-plus"></i> Log New Claim
            </a>
        @endif
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Active Claims</span>
            <div class="stat-value">{{ $pendingCount }} Cases</div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total History</span>
            <div class="stat-value">{{ $returns->total() }} Records</div>
        </div>
    </div>

    <div class="returns-table-wrapper">
        <table class="returns-table">
            <thead>
                <tr>
                    <th>Claim ID</th>
                    <th>Asset Detail</th>
                    <th>Quantity</th>
                    <th>Justification</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                <tr>
                    <td><span class="claim-id">#{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                    <td>
                        <div class="product-info">
                            <span class="product-name">{{ $return->product->name }}</span>
                            <span class="product-category">{{ $return->product->category->name }}</span>
                        </div>
                    </td>
                    <td><span style="font-weight: 700;">{{ $return->quantity_returned }}</span> <small style="color: #adb5bd;">UNITS</small></td>
                    <td>
                        <span style="font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: #666;">
                            {{ str_replace('_', ' ', $return->reason) }}
                        </span>
                    </td>
                    <td style="color: #666; font-size: 0.85rem;">{{ $return->return_date->format('d M Y') }}</td>
                    <td>
                        <span class="badge-status status-{{ $return->status }}">
                            {{ $return->status }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('returns.show', $return) }}" class="action-link">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-archive"></i></div>
                            <h3 style="font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em;">Archive is Empty</h3>
                            <p style="color: #ccc; margin-top: 0.5rem;">No product return claims have been logged yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($returns->hasPages())
        <div style="margin-top: 3rem; display: flex; justify-content: center;" class="no-print">
            {{ $returns->links() }}
        </div>
    @endif

    <div class="report-footer" style="display: none;">
        <div style="display: flex; justify-content: flex-end;">
            <div style="text-align: right; min-width: 250px;">
                <div style="position: relative; display: inline-block; padding-top: 2rem;">
                    <img src="{{ asset('assets/signatures/owner-signature.png') }}" style="height: 100px; position: absolute; top: -30px; left: 50%; transform: translateX(-50%) rotate(-3deg); z-index: 1; opacity: 0.95; pointer-events: none;">
                    <div style="border-top: 2px solid #1a1a1a; width: 220px; margin: 0 auto;"></div>
                    <strong style="font-size: 1.3rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.01em; margin-top: 0.75rem; display: block;">{{ App\Models\User::getOwnerName() }}</strong>
                    <p style="font-size: 0.7rem; color: #666; font-weight: 700; margin-top: 0.2rem; text-transform: uppercase; letter-spacing: 0.1em;">Boutique Owner</p>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none;" class="print-only-footer">
        <div style="margin-top: 4rem; text-align: center; color: #adb5bd; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.2em;">
            Generated on {{ date('F d, Y - h:i A') }} • Official V'S Fashion Audit Document
        </div>
    </div>
</div>
<style>
    @media print {
        .report-footer, .print-only-footer { display: block !important; }
    }
</style>
@endsection
