@extends('layouts.app')

@section('title', 'Product Details')

@section('styles')
<style>
    .product-details-container {
        max-width: 1200px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .detail-card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        padding: 2.5rem;
        height: 100%;
        transition: transform 0.3s ease;
    }

    .product-title {
        font-family: 'Bodoni Moda', serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .product-image-wrapper {
        width: 100%;
        height: 400px;
        background: #f8f9fa;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid #f1f1f1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        color: #adb5bd;
    }

    .no-image-placeholder i {
        font-size: 3rem;
        opacity: 0.3;
    }

    .detail-table {
        width: 100%;
        margin-bottom: 2rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.95rem;
    }

    .price-value {
        color: var(--color-editorial);
        font-size: 1.25rem;
        font-weight: 800;
    }

    .action-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-premium {
        padding: 0.8rem 1.75rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: var(--color-editorial);
        color: white;
    }

    .btn-edit:hover {
        background: #6a1a28;
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 8px 20px rgba(128, 32, 48, 0.2);
    }

    .btn-archive {
        background: #f8f9fa;
        color: #777;
        border: 1px solid #eee;
    }

    .btn-archive:hover {
        background: #fff;
        color: #dc3545;
        border-color: #dc3545;
        transform: translateY(-2px);
    }

    .inventory-section-title {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-editorial);
        margin-bottom: 1.5rem;
    }

    .inventory-card-mini {
        background: #fcfcfc;
        border: 1px solid #f1f1f1;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .inventory-card-mini:hover {
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        border-color: #eee;
    }

    .branch-name {
        font-weight: 800;
        font-size: 0.9rem;
        color: #1a1a1a;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stock-badge {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
    }

    .stock-low { color: #dc3545; }
    .stock-ok { color: #198754; }

    .btn-manage {
        width: 100%;
        background: white;
        color: #1a1a1a;
        border: 1px solid #eee;
        justify-content: center;
        margin-top: 1rem;
    }

    .btn-manage:hover {
        background: var(--color-editorial);
        color: white;
        border-color: var(--color-editorial);
        transform: translateY(-2px);
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
        margin-bottom: 2rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: var(--color-editorial);
    }
</style>
@endsection

@section('content')
<div class="product-details-container">
    <a href="{{ route('products.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Catalog
    </a>

    <div class="row g-4">
        <!-- Left: Product Information -->
        <div class="col-lg-8">
            <div class="detail-card">
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-image-wrapper">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                    @else
                        <div class="no-image-placeholder">
                            <i class="fas fa-image"></i>
                            <span>No visual asset available</span>
                        </div>
                    @endif
                </div>

                <div class="detail-table">
                    <div class="detail-row">
                        <span class="detail-label">Brand</span>
                        <span class="detail-value">{{ $product->brand ?? 'Generic' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Category</span>
                        <span class="detail-value">{{ $product->category->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Market Price</span>
                        <span class="detail-value price-value">
                            @if($product->firstAvailableBatch)
                                ₱{{ number_format($product->firstAvailableBatch->selling_price, 2) }}
                            @else
                                <span style="color: #adb5bd; font-size: 0.9rem;">Waitlisted</span>
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Size/Variant</span>
                        <span class="detail-value">{{ $product->size ?? 'Universal' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Archive Date</span>
                        <span class="detail-value text-muted">{{ $product->date_added->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($product->description)
                <div class="mb-4">
                    <h4 class="detail-label mb-2">Product Description</h4>
                    <p class="text-muted" style="font-size: 0.9rem; line-height: 1.6;">{{ $product->description }}</p>
                </div>
                @endif

                <div class="action-group">
                    <a href="{{ route('products.edit', $product) }}" class="btn-premium btn-edit">
                        <i class="fas fa-pen-nib"></i> Edit Specifications
                    </a>
                    <form id="deleteProductForm" action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-premium btn-archive" onclick="showArchiveModal(document.getElementById('deleteProductForm'), 'Archive Product?', 'This product will be moved to archives and hidden from POS terminal.')">
                            <i class="fas fa-box-archive"></i> Archive Product
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Inventory & Availability -->
        <div class="col-lg-4">
            <div class="detail-card">
                <h3 class="inventory-section-title">Stock Availability</h3>

                @forelse($inventories as $inventory)
                <div class="inventory-card-mini">
                    <div class="branch-name">
                        <i class="fas fa-store"></i> {{ $inventory->branch }} Branch
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <div class="detail-label" style="font-size: 0.6rem;">Available Stock</div>
                            <div class="stock-badge @if($inventory->isBelowReorderLevel()) stock-low @else stock-ok @endif">
                                {{ $inventory->quantity }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="detail-label" style="font-size: 0.6rem;">Threshold</div>
                            <div class="detail-value" style="font-size: 0.8rem;">{{ $inventory->reorder_level }} units</div>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top" style="font-size: 0.65rem; color: #adb5bd; font-weight: 600;">
                        Last Sync: {{ $inventory->last_updated->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-warehouse fa-3x mb-3" style="opacity: 0.1;"></i>
                    <p class="text-muted small">No inventory records found for this product.</p>
                </div>
                @endforelse

                <a href="{{ route('inventory.edit', $product) }}" class="btn-premium btn-manage">
                    <i class="fas fa-layer-group"></i> Manage Inventory
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
