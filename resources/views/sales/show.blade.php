@extends('layouts.app')

@section('title', 'Archive: Transaction Details')

@section('styles')
<style>
    .receipt-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 1rem;
        padding-bottom: 3rem;
    }

    .receipt-header-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        width: 100%;
        max-width: 380px;
    }

    .btn-print { 
        background: var(--color-editorial); 
        color: white; 
        padding: 0.8rem 2rem; 
        border-radius: 100px; 
        border: none; 
        font-weight: 800; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        cursor: pointer;
        transition: transform 0.2s;
        width: 100%;
    }
    .btn-print:hover { transform: scale(1.02); }

    .arch-receipt-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--color-border);
        padding: 2.5rem 1.5rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 380px; /* Real receipt width on screen */
    }

    .receipt-header-print { 
        text-align: center; 
        margin-bottom: 1.5rem; 
        padding-bottom: 1rem; 
        border-bottom: 1px solid #f1f1f1; 
    }

    /* Change grid to flex row for pairs */
    .receipt-grid { margin-bottom: 1.5rem; }
    .receipt-grid > div {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.5rem;
        border-bottom: 1px dashed #f1f1f1;
        padding-bottom: 0.25rem;
    }
    .receipt-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.05em; }
    .receipt-value { font-size: 0.75rem; font-weight: 700; color: #1a1a1a; text-align: right; max-width: 60%; }

    /* Items Table */
    .receipt-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
    .receipt-table th { text-align: left; padding: 0.5rem 0; font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; border-bottom: 2px solid #f1f1f1; }
    .receipt-table td { padding: 0.5rem 0; border-bottom: 1px solid #f1f1f1; font-size: 0.75rem; font-weight: 600; vertical-align: middle; }

    .summary-box { 
        width: 100%; 
        padding: 1.25rem; 
        background: #fdf2f4; 
        border-radius: 12px;
        margin-top: 1.5rem;
        border: 1px solid rgba(128, 32, 48, 0.05);
    }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.75rem; color: #666; }
    .total-row { display: flex; justify-content: space-between; align-items: baseline; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid rgba(128, 32, 48, 0.1); font-size: 1.1rem; font-weight: 900; color: var(--color-editorial); }

    .notice-container {
        width: 100%;
        margin-top: 1rem;
        text-align: center;
        font-size: 0.65rem;
        color: #999;
        line-height: 1.4;
    }

    .report-footer { 
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f1f1;
        text-align: center;
    }
    .report-footer img {
        height: 40px;
    }
    .thank-you-footer { 
        margin-top: 1.5rem; 
        font-size: 0.75rem;
        text-align: center;
    }

    @media print {
        @page { 
            size: 80mm 297mm;
            margin: 0; 
        }
        html, body { 
            background: white !important; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
            width: 80mm !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
        }
        
        /* Hide all UI elements except the receipt */
        .sidebar, .top-navbar, .btn-print, .back-link, .receipt-header-actions { display: none !important; }
        
        /* Force parent containers to show and behave linearly */
        .app-container, .main-workspace, .workspace-scroll, .receipt-wrapper { 
            display: block !important; 
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important; 
            margin: 0 !important; 
            padding: 0 !important; 
            width: 80mm !important;
            background: white !important; 
        }

        /* The receipt card prints exactly 80mm wide without stretching */
        .arch-receipt-card { 
            box-shadow: none !important; 
            border: none !important; 
            margin: 0 !important; 
            padding: 4mm !important; 
            max-width: 80mm !important; 
            width: 80mm !important;
            background: white !important;
            box-sizing: border-box !important;
        }

        /* Adjust summary box background for print */
        .summary-box { 
            background: #fdf2f4 !important; 
            width: 100% !important;
            margin-top: 2rem !important;
        }
        
        .receipt-grid { margin-bottom: 2rem !important; }
    }
</style>
@endsection

@section('content')
<div class="receipt-wrapper">
    
    <div class="receipt-header-actions">
        <a href="{{ route('sales.index') }}" class="back-link" style="color: #adb5bd; text-decoration: none; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; width: 100%; text-align: left;">
            <i class="fas fa-arrow-left"></i> Transaction History
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print" style="margin-right: 0.75rem;"></i> Generate Print
        </button>
    </div>

    <div class="arch-receipt-card">
        <!-- Boutique Professional Header -->
        <div class="receipt-header-print">
            <div style="font-family: 'Bodoni Moda', serif; font-size: 2rem; font-weight: 900; color: var(--color-editorial); margin-bottom: 0.25rem; letter-spacing: -0.05em;">V’S Fashion</div>
            <div style="font-size: 0.65rem; font-weight: 800; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.25rem;">Boutique Management</div>
            <div style="font-size: 0.65rem; color: #666; font-weight: 500;">
                San Carlos City, Pangasinan<br>
                Contact: +63 09158969268 • TIN: 123-456-789-000
            </div>
        </div>

        <div class="receipt-grid">
            <div>
                <span class="receipt-label">Receipt No.</span>
                <span class="receipt-value">#{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div>
                <span class="receipt-label">Served By</span>
                <span class="receipt-value">{{ $sale->user->name }}</span>
            </div>
            <div>
                <span class="receipt-label">Branch</span>
                <span class="receipt-value">{{ $sale->branch }}</span>
            </div>
            <div>
                <span class="receipt-label">Date & Time</span>
                <span class="receipt-value">{{ $sale->date_sold->format('F d, Y') }}<br>{{ $sale->created_at->format('h:i A') }}</span>
            </div>
            <div>
                <span class="receipt-label">Payment Mode</span>
                <span class="receipt-value">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>
                        <div style="font-size: 0.8rem; font-weight: 800; color: #1a1a1a;">{{ $item->product->name }}</div>
                        <div style="font-size: 0.6rem; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.05em;">{{ $item->product->category->name ?? 'Item' }}</div>
                    </td>
                    <td style="text-align: center; font-weight: 700;">{{ $item->quantity }}</td>
                    <td style="text-align: right; font-weight: 800; color: #1a1a1a;">₱{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="notice-container">
            <strong style="color: #666; display: block; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Notice:</strong>
            Please keep this receipt for returns or exchanges within 7 days. Items must be in original condition with tags attached.
        </div>
        
        <div class="summary-box">
            <div class="summary-row">
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
                <span style="text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #666;">Total Due</span>
                <span>₱{{ number_format($sale->total_amount, 2) }}</span>
            </div>
            @if($sale->cash_received > 0)
            <div class="summary-row" style="margin-top: 0.75rem; color: #666;">
                <span>Received</span>
                <span>₱{{ number_format($sale->cash_received, 2) }}</span>
            </div>
            @if($sale->payment_method !== 'gcash')
            <div class="summary-row" style="color: #1a1a1a; font-weight: 800; border-top: 1px dashed rgba(128, 32, 48, 0.1); padding-top: 0.5rem; margin-top: 0.5rem;">
                <span>Change Given</span>
                <span>₱{{ number_format($sale->change_amount, 2) }}</span>
            </div>
            @endif
            @endif
            <div style="margin-top: 1rem; text-align: center;">
                <span style="background: white; color: var(--color-editorial); padding: 0.35rem 1rem; border-radius: 100px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; border: 1px solid rgba(128, 32, 48, 0.1);">
                    Payment: {{ strtoupper($sale->status) }}
                </span>
            </div>
        </div>

        <div class="report-footer">
            <img src="{{ asset('assets/signatures/owner-signature.png') }}" style="opacity: 0.9;">
            <div style="font-size: 1rem; font-weight: 900; color: #1a1a1a; letter-spacing: -0.02em;">{{ App\Models\User::getOwnerName() }}</div>
            <div style="font-size: 0.6rem; color: #adb5bd; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em;">Boutique Owner</div>
        </div>

        <div class="thank-you-footer">
            <div style="font-family: 'Bodoni Moda', serif; font-size: 1.25rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.25rem;">Thank You!</div>
            <p style="color: #adb5bd; font-weight: 600;">Visit us again.</p>
        </div>
    </div>
</div>
@endsection
