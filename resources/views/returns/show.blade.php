@extends('layouts.app')

@section('title', 'Claim Dossier #' . str_pad($return->id, 6, '0', STR_PAD_LEFT))

@section('styles')
<style>
    .claim-show-container {
        max-width: 1100px;
    }

    .claim-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 3rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #adb5bd;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: var(--color-editorial);
    }

    .dossier-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 3rem;
    }

    .dossier-card {
        padding: 3rem;
    }

    .section-label {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--color-editorial);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #fdf2f4;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        padding: 1.25rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .data-row:last-child {
        border-bottom: none;
    }

    .data-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .data-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .badge-status {
        padding: 0.5rem 1.25rem;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .status-pending { background: #fffbeb; color: #92400e; }
    .status-approved { background: #f0fdf4; color: #166534; }
    .status-rejected { background: #fef2f2; color: #991b1b; }
    .status-refunded { background: #fdf2f4; color: var(--color-editorial); }
    .status-replaced { background: #f0f6f6; color: #3c5e5e; }

    .description-box {
        margin-top: 2rem;
        background: #fdfdfd;
        border: 1px solid #eee;
        border-radius: 16px;
        padding: 1.5rem;
    }

    .description-label {
        font-size: 0.7rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
        display: block;
    }

    .description-text {
        color: #555;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Admin Action Section */
    .admin-section {
        margin-top: 3rem;
        background: #fdf2f4/30;
        border: 2px solid #fdf2f4;
        border-radius: 24px;
        padding: 3rem;
    }

    .admin-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }

    .action-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        border: 1px solid #fdf2f4;
    }

    .action-title {
        font-weight: 800;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1.25rem;
        border: 1px solid #eee;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
    }

    .btn-approve {
        width: 100%;
        background: #166534;
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 100px;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-reject {
        width: 100%;
        background: #991b1b;
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 100px;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-approve:hover, .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    @media (max-width: 992px) {
        .dossier-grid, .admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="claim-show-container">
    <a href="{{ route('returns.index') }}" class="back-link">
        <i class="fas fa-chevron-left"></i> Back to Archive
    </a>

    <div class="claim-header">
        <div>
            <h1 class="view-content-title">Claim Dossier</h1>
            <p class="view-content-subtitle">Incident ID #{{ str_pad($return->id, 6, '0', STR_PAD_LEFT) }} • Logged on {{ $return->return_date->format('M d, Y') }}</p>
        </div>
        <div class="badge-status status-{{ $return->status }}">
            {{ $return->status }}
        </div>
    </div>

    <div class="dossier-grid">
        <div class="pos-card dossier-card">
            <h3 class="section-label"><i class="fas fa-info-circle"></i> Incident Specification</h3>
            
            <div class="data-row">
                <span class="data-label">Product Name</span>
                <span class="data-value">{{ $return->product->name }}</span>
            </div>
            <div class="data-row">
                <span class="data-label">Product Category</span>
                <span class="data-value">{{ $return->product->category->name }}</span>
            </div>
            <div class="data-row">
                <span class="data-label">Claim Volume</span>
                <span class="data-value">{{ $return->quantity_returned }} Units</span>
            </div>
            <div class="data-row">
                <span class="data-label">Justification</span>
                <span class="data-value" style="text-transform: uppercase; font-size: 0.85rem;">{{ str_replace('_', ' ', $return->reason) }}</span>
            </div>

            @if($return->description)
                <div class="description-box">
                    <span class="description-label">Dossier Narrative</span>
                    <p class="description-text">{{ $return->description }}</p>
                </div>
            @endif
        </div>

        <div class="pos-card dossier-card">
            <h3 class="section-label"><i class="fas fa-file-invoice"></i> Processing</h3>
            
            <div class="data-row">
                <span class="data-label">Resolution</span>
                <span class="data-value">{{ $return->action ? ucfirst($return->action) : 'Awaiting Decision' }}</span>
            </div>
            
            @if($return->refund_amount)
            <div class="data-row">
                <span class="data-label">Refund Value</span>
                <span class="data-value" style="color: var(--color-editorial); font-size: 1.25rem;">₱{{ number_format($return->refund_amount, 2) }}</span>
            </div>
            @endif

            <div class="data-row">
                <span class="data-label">Handled By</span>
                <span class="data-value">{{ $return->processedByUser->name ?? 'System Queue' }}</span>
            </div>

            @if($return->processed_date)
            <div class="data-row">
                <span class="data-label">Completion Date</span>
                <span class="data-value">{{ $return->processed_date->format('M d, Y') }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($return->isPending() && (auth()->user()->isOwner() || auth()->user()->isAdmin()))
        <div class="admin-section">
            <h3 class="section-label"><i class="fas fa-shield-alt"></i> Administrative Resolution</h3>
            
            <div class="admin-grid">
                <div class="action-card">
                    <h4 class="action-title"><i class="fas fa-check-circle" style="color: #166534;"></i> Grant Approval</h4>
                    <form action="{{ route('returns.approve', $return) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label style="font-size: 0.7rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 0.5rem; display: block;">Select Resolution Path</label>
                            <select name="action" id="approve_action" class="form-control" required>
                                <option value="refund">Financial Refund</option>
                                <option value="replacement">Inventory Replacement</option>
                                <option value="store_credit">Store Credit Issuance</option>
                            </select>
                        </div>

                        <div class="form-group" id="refundGroup">
                            <label style="font-size: 0.7rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 0.5rem; display: block;">Authorized Refund Amount</label>
                            <input type="number" name="refund_amount" step="0.01" class="form-control" placeholder="₱0.00">
                        </div>

                        <button type="submit" class="btn-approve">
                            Confirm Resolution
                        </button>
                    </form>
                </div>

                <div class="action-card">
                    <h4 class="action-title"><i class="fas fa-times-circle" style="color: #991b1b;"></i> Deny Claim</h4>
                    <form action="{{ route('returns.reject', $return) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label style="font-size: 0.7rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; margin-bottom: 0.5rem; display: block;">Reason for Denial</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="State the reason for denying this claim..."></textarea>
                        </div>

                        <button type="submit" class="btn-reject" onclick="return confirm('Confirm permanent denial of this claim?')">
                            Reject Claim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const approveAction = document.getElementById('approve_action');
        const refundGroup = document.getElementById('refundGroup');
        
        if (approveAction) {
            approveAction.addEventListener('change', function() {
                if (this.value === 'refund') {
                    refundGroup.style.display = 'block';
                } else {
                    refundGroup.style.display = 'none';
                }
            });
            
            // Initial state
            if (approveAction.value !== 'refund') {
                refundGroup.style.display = 'none';
            }
        }
    });
</script>
@endsection
