@extends('layouts.app')

@section('title', 'Returns Report')

@section('content')
<div>
    <h1 style="margin-bottom: 2rem;">Returns & Refunds Report</h1>

    <div class="card" style="margin-bottom: 2rem;">
        <form action="{{ route('returns.report') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Generate Report</button>
            </div>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Returns</div>
            <div class="stat-value">{{ $totalReturns }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Refunded</div>
            <div class="stat-value">₱{{ number_format($totalRefunded, 2) }}</div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem;">Returns Details</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Return ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>#{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $return->product->name }}</td>
                        <td>{{ $return->quantity_returned }}</td>
                        <td>{{ ucfirst($return->reason) }}</td>
                        <td>{{ $return->return_date->format('M d, Y') }}</td>
                        <td>
                            <span style="background: @switch($return->status)
                                @case('pending') #fed7d7 @break
                                @case('approved') #bee3f8 @break
                                @case('refunded') #c6f6d5 @break
                                @endswitch;
                                color: @switch($return->status)
                                @case('pending') #742a2a @break
                                @case('approved') #2c5282 @break
                                @case('refunded') #22543d @break
                                @endswitch;
                                padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">
                                {{ ucfirst($return->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('returns.show', $return) }}" class="btn btn-sm" style="background: #667eea; color: white; text-decoration: none; padding: 0.5rem 0.75rem;">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                            No returns found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
</div>
@endsection
