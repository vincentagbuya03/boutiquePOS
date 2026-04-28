@extends('layouts.app')

@section('title', 'Sales Management')

@section('styles')
<style>
    .index-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .index-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .index-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .action-group { display: flex; gap: 1rem; }
    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; transition: transform 0.2s; }
    .btn-arch-secondary { background: white; border: 1px solid var(--color-border); color: #1a1a1a; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; }
    .btn-arch-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(128, 32, 48, 0.15); }

    /* Archive Table Shell */
    .arch-table-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.02);
    }

    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.5rem 1rem; font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 1px solid #f8f9fa; }
    .arch-table td { padding: 1.5rem 1rem; border-bottom: 1px solid #f8f9fa; font-size: 0.85rem; font-weight: 600; vertical-align: middle; }

    /* Specific Styles */
    .date-column { color: #999; width: 120px; }
    .amount-column { font-weight: 800; color: var(--color-editorial); font-size: 1rem; }
    
    .profile-mini { display: flex; align-items: center; gap: 1rem; }
    .p-avatar { width: 32px; height: 32px; border-radius: 50%; background: #fdf2f4; color: var(--color-editorial); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; }

    .badge-arch { padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-mauve { background: #f8f3f4; color: #c299a0; }
    .badge-teal { background: #f0f6f6; color: #3c5e5e; }
    .badge-maroon { background: #fdf2f2; color: #802030; }

    .action-btn-mini { color: #adb5bd; transition: color 0.2s; }
    .action-btn-mini:hover { color: var(--color-editorial); }
</style>
@endsection

@section('content')
<div class="index-header">
    <div>
        <h1 class="index-title">V'S Fashion: Transactions</h1>
        <p class="index-subtitle">A curated history of V’S Fashion Boutique sales.</p>
    </div>
    <div class="action-group">
        <a href="{{ route('sales.report') }}" class="btn-arch-secondary">
            <i class="fas fa-file-export" style="margin-right: 0.5rem;"></i> Audit Report
        </a>
        <a href="{{ route('sales.create') }}" class="btn-arch-primary">
            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> New Sale
        </a>
    </div>
</div>

<div class="arch-table-card">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Date Sold</th>
                <th>Curator</th>
                <th>Revenue</th>
                <th>Volume</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td class="date-column">{{ $sale->date_sold->format('M d, Y') }}</td>
                <td>
                    <div class="profile-mini">
                        @php $initials = collect(explode(' ', $sale->user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join(''); @endphp
                        <div class="p-avatar">{{ $initials }}</div>
                        <span>{{ $sale->user->name }}</span>
                    </div>
                </td>
                <td class="amount-column">₱{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ $sale->items->count() }} items</td>
                <td><span class="badge-arch badge-maroon">{{ $sale->status }}</span></td>
                <td style="text-align: right;">
                    <a href="{{ route('sales.show', $sale) }}" class="action-btn-mini">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 5rem 0; color: #adb5bd;">
                    <i class="fas fa-history" style="font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 1.5rem;"></i>
                    No transactions found in this sequence.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($sales->hasPages())
    <div style="margin-top: 2.5rem;">
        {{ $sales->links() }}
    </div>
    @endif
</div>
@endsection
