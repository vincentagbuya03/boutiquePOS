@extends('layouts.app')

@section('title', 'Archive: Transaction Details')

@section('styles')
<style>
    .receipt-container { max-width: 900px; margin: 0 auto; }
    .receipt-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .receipt-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .receipt-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .arch-receipt-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 4rem;
        box-shadow: 0 40px 80px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
    }

    .boutique-marking {
        position: absolute;
        top: 2rem;
        right: 2rem;
        font-family: 'Bodoni Moda', serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--color-editorial);
        opacity: 0.15;
        transform: rotate(-15deg);
        user-select: none;
    }

    /* Info Grid */
    .receipt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-bottom: 4rem; }
    .receipt-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem; display: block; }
    .receipt-value { font-size: 1.1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 2rem; }

    /* Items Table */
    .receipt-table { width: 100%; border-collapse: collapse; margin-bottom: 4rem; }
    .receipt-table th { text-align: left; padding: 1.5rem 1rem; font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; border-bottom: 1px solid #f1f1f1; }
    .receipt-table td { padding: 1.5rem 1rem; border-bottom: 1px solid #f1f1f1; font-size: 0.95rem; font-weight: 600; vertical-align: middle; }

    .summary-box { 
        margin-left: auto; 
        width: 380px; 
        padding: 2.5rem; 
        background: #fdf2f4; 
        border-radius: 24px;
    }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-weight: 600; font-size: 0.85rem; }
    .total-row { display: flex; justify-content: space-between; align-items: baseline; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid rgba(128, 32, 48, 0.1); font-size: 1.5rem; font-weight: 900; color: var(--color-editorial); }

    .btn-print { 
        background: var(--color-editorial); 
        color: white; 
        padding: 1.2rem 3rem; 
        border-radius: 100px; 
        border: none; 
        font-weight: 800; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        cursor: pointer;
        transition: transform 0.2s;
        margin-top: 3rem;
    }
    .btn-print:hover { transform: scale(1.05); }

    @media print {
        @page { size: auto; margin: 5mm; }
        body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-size: 12pt; }
        .sidebar, .top-navbar, .btn-print, .back-link, .receipt-header { display: none !important; }
        .main-workspace, .workspace-scroll { overflow: visible !important; padding: 0 !important; display: block !important; margin: 0 !important; }
        .receipt-container { max-width: 100%; margin: 0; width: 100%; }
        .arch-receipt-card { border: none !important; box-shadow: none !important; padding: 0 !important; width: 100% !important; }
        
        /* Ensure the title is visible during print */
        .print-title-visible { display: block !important; text-align: center; margin-bottom: 1rem; }
        
        .receipt-header-print { margin-bottom: 2rem !important; padding-bottom: 1.5rem !important; border-bottom: 2px dashed #000 !important; }
        .receipt-grid { gap: 2rem !important; margin-bottom: 2rem !important; display: grid !important; grid-template-columns: 1fr 1fr !important; }
        .receipt-value { margin-bottom: 1rem !important; font-size: 1rem !important; }
        .receipt-table { margin-bottom: 2rem !important; }
        .receipt-table th, .receipt-table td { padding: 0.75rem 0.5rem !important; border-bottom: 1px dashed #eee !important; }
        .summary-box { width: 300px !important; padding: 1.5rem !important; border: 1px solid #eee !important; background: #fff !important; }
        .report-footer { margin-top: 2rem !important; padding-top: 1.5rem !important; page-break-inside: avoid; }
        .report-footer img { height: 80px !important; }
        .thank-you-footer { margin-top: 2rem !important; border-top: 1px dashed #000; padding-top: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="receipt-container">
    <div class="receipt-header">
        <div>
            <a href="{{ route('sales.index') }}" class="back-link" style="color: #adb5bd; text-decoration: none; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">
                <i class="fas fa-arrow-left"></i> Transaction History
            </a>
            <h1 class="receipt-title" style="margin-top: 1.5rem;">Official Receipt</h1>
            <p class="receipt-subtitle">Transaction proof of V’S Fashion Boutique.</p>
        </div>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print" style="margin-right: 0.75rem;"></i> Generate Print
        </button>
    </div>

    <div class="arch-receipt-card">
        <!-- Print-only Title -->
        <div class="print-title-visible" style="display: none;">
            <h1 style="font-size: 1.5rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #000; margin-bottom: 0.5rem;">Official Receipt</h1>
            <p style="font-size: 0.8rem; color: #666; margin-bottom: 2rem;">V’S Fashion Boutique Transaction Proof</p>
        </div>

        <!-- Boutique Professional Header -->
        <div class="receipt-header-print" style="text-align: center; margin-bottom: 3.5rem; border-bottom: 2px solid #f1f1f1; padding-bottom: 2rem;">
            <div style="font-family: 'Bodoni Moda', serif; font-size: 2.5rem; font-weight: 900; color: var(--color-editorial); margin-bottom: 0.5rem; letter-spacing: -0.05em;">V’S Fashion</div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem;">Boutique Management</div>
            <div style="font-size: 0.85rem; color: #666; font-weight: 500;">
                San Carlos City, Pangasinan<br>
                Contact: +63 09158969268 • TIN: 123-456-789-000
            </div>
        </div>

        <div class="receipt-grid">
            <div>
                <span class="receipt-label">Receipt Number</span>
                <div class="receipt-value">#{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</div>

                <span class="receipt-label">Served By (Cashier)</span>
                <div class="receipt-value">{{ $sale->user->name }}</div>

                <span class="receipt-label">Store Location</span>
                <div class="receipt-value">{{ $sale->branch }} Branch</div>
            </div>
            <div>
                <span class="receipt-label">Date & Time Issued</span>
                <div class="receipt-value">{{ $sale->date_sold->format('F d, Y') }} • {{ $sale->created_at->format('h:i A') }}</div>

                <span class="receipt-label">Transaction Type</span>
                <div class="receipt-value">{{ ucfirst(str_replace('_', ' ', $sale->type)) }}</div>

                <span class="receipt-label">Payment Mode</span>
                <div class="receipt-value">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</div>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>
                        <div style="font-size: 1rem; font-weight: 800; color: #1a1a1a;">{{ $item->product->name }}</div>
                        <div style="font-size: 0.7rem; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.05em;">{{ $item->product->category->name ?? 'Collection' }}</div>
                    </td>
                    <td style="text-align: center; font-weight: 700;">{{ $item->quantity }}</td>
                    <td style="text-align: right; color: #666;">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right; font-weight: 800; color: #1a1a1a;">₱{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="max-width: 300px; font-size: 0.75rem; color: #999; font-weight: 500; line-height: 1.6;">
                <strong style="color: #666; display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Notice:</strong>
                Please keep this receipt for returns or exchanges within 7 days. Items must be in original condition with tags attached.
            </div>
            
            <div class="summary-box">
                <div class="summary-row" style="color: #666;">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($sale->items->sum('total_price'), 2) }}</span>
                </div>
                @if($sale->discount_amount > 0)
                <div class="summary-row" style="color: #28a745; font-weight: 800;">
                    <span>
                        @if($sale->discount_type === 'pwd')
                            PWD Discount (12%)
                        @elseif($sale->discount_type === 'senior_citizen')
                            Senior Discount (20%)
                        @else
                            Discount Applied
                        @endif
                    </span>
                    <span>-₱{{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="total-row">
                    <span style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.1em; color: #666;">Total Amount Due</span>
                    <span>₱{{ number_format($sale->total_amount, 2) }}</span>
                </div>
                @if($sale->cash_received > 0)
                <div class="summary-row" style="margin-top: 1rem; color: #666;">
                    <span>{{ $sale->payment_method === 'gcash' ? 'GCash Amount' : 'Cash Received' }}</span>
                    <span>₱{{ number_format($sale->cash_received, 2) }}</span>
                </div>
                @if($sale->payment_method !== 'gcash')
                <div class="summary-row" style="color: #1a1a1a; font-weight: 800; border-top: 1px dashed rgba(128, 32, 48, 0.1); padding-top: 0.5rem;">
                    <span>Change Given</span>
                    <span>₱{{ number_format($sale->change_amount, 2) }}</span>
                </div>
                @endif
                @endif
                <div style="margin-top: 1.5rem; text-align: center;">
                    <span style="background: white; color: var(--color-editorial); padding: 0.5rem 1.5rem; border-radius: 100px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; border: 1px solid rgba(128, 32, 48, 0.1);">
                        Payment: {{ strtoupper($sale->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="report-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #eee; display: flex; justify-content: flex-end; page-break-inside: avoid;">
                <div style="text-align: right; min-width: 250px;">
                    <div style="position: relative; display: inline-block; margin-top: 2rem;">
                        <img src="{{ asset('assets/signatures/owner-signature.png') }}" style="height: 100px; position: absolute; top: -45px; left: 50%; transform: translateX(-50%) rotate(-3deg); z-index: 1; opacity: 0.9; pointer-events: none;">
                        <strong style="font-size: 1.5rem; font-weight: 900; color: #1a1a1a; letter-spacing: -0.02em; position: relative; z-index: 0; display: block; border-top: 1px solid #eee; padding-top: 0.5rem;">
                            {{ App\Models\User::getOwnerName() }}
                        </strong>
                        <p style="font-size: 0.7rem; color: #adb5bd; font-weight: 700; margin-top: 0.2rem; text-transform: uppercase; letter-spacing: 0.12em;">Boutique Owner</p>
                    </div>
                </div>
        </div>

        <div class="thank-you-footer" style="margin-top: 4rem; text-align: center;">
            <div style="font-family: 'Bodoni Moda', serif; font-size: 1.5rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem;">Thank You!</div>
            <p style="font-size: 0.8rem; color: #adb5bd; font-weight: 600;">Visit us again at V'S Fashion Boutique.</p>
        </div>
    </div>
</div>
@endsection
