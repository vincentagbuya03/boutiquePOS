@extends('layouts.app')

@section('title', 'Archive: Adjust Inventory')

@section('styles')
<style>
    .adjust-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .adjust-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .adjust-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .adjust-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 2.5rem; }

    /* Piece Card */
    .piece-identity-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 3rem;
        position: sticky;
        top: 2rem;
    }

    .p-img-frame-lg { width: 100%; aspect-ratio: 1/1; border-radius: 20px; background: #fdfdfd; border: 1px solid #eee; overflow: hidden; margin-bottom: 2rem; display: flex; align-items: center; justify-content: center; }
    .p-img-frame-lg img { width: 100%; height: 100%; object-fit: cover; }

    .p-id-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem; display: block; }
    .p-id-value { font-size: 1.5rem; font-weight: 800; color: #1a1a1a; margin-bottom: 2rem; }

    /* Stock Controller Card */
    .stock-controller-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 3rem;
    }

    .branch-adjust-rack {
        margin-bottom: 3rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid #f1f1f1;
    }

    .branch-adjust-rack:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

    .branch-lbl { font-size: 1.25rem; font-weight: 800; color: #1a1a1a; margin-bottom: 1.5rem; display: block; }

    /* Modern Input Styling */
    .arch-field-group { margin-bottom: 2rem; }
    .arch-field-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 0.75rem; }
    
    .arch-input-underlined {
        width: 100%;
        border: none;
        border-bottom: 2px solid #f1f1f1;
        padding: 1rem 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #1a1a1a;
        background: transparent;
        transition: border-color 0.3s;
        outline: none;
    }
    .arch-input-underlined:focus { border-color: var(--color-editorial); }

    .current-stock-pill { background: #fdf2f4; color: var(--color-editorial); padding: 1rem 2rem; border-radius: 100px; display: inline-flex; align-items: center; gap: 1rem; margin-bottom: 2rem; width: 100%; }
    .stock-val-lg { font-size: 1.75rem; font-weight: 800; }

    .btn-arch-save { 
        width: 100%; 
        background: var(--color-editorial); 
        color: white; 
        padding: 1.2rem; 
        border-radius: 100px; 
        border: none; 
        font-weight: 800; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        cursor: pointer;
        transition: transform 0.2s;
        margin-top: 1rem;
    }
    .btn-arch-save:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(128, 32, 48, 0.1); }
</style>
@endsection

@section('content')
<div class="adjust-header">
    <div>
        <a href="{{ route('inventory.index') }}" style="color: #adb5bd; text-decoration: none; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">
            <i class="fas fa-arrow-left"></i> Stock Archive
        </a>
        <h1 class="adjust-title" style="margin-top: 1.5rem;">Adjust Stock</h1>
        <p class="adjust-subtitle">Synchronizing physical availability for V’S Fashion Boutique.</p>
    </div>
</div>

<div class="adjust-grid">
    <div class="piece-identity-card">
        <div class="p-img-frame-lg">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
            @else
                <i class="fas fa-cube" style="font-size: 5rem; color: #f1f1f1;"></i>
            @endif
        </div>
        
        <span class="p-id-label">Archive Piece Identity</span>
        <div class="p-id-value">{{ $product->name }}</div>

        <span class="p-id-label">Category</span>
        <div class="p-id-value" style="font-size: 1.1rem; color: #555;">{{ $product->category->name }}</div>

        <span class="p-id-label">Valuation</span>
        <div class="p-id-value" style="font-size: 1.5rem; color: var(--color-editorial);">
            @if($product->firstAvailableBatch)
                ₱{{ number_format($product->firstAvailableBatch->selling_price, 2) }}
            @else
                <span style="color: #adb5bd; font-size: 1rem;">N/A (No Available Stock)</span>
            @endif
        </div>
    </div>

    <div class="stock-controller-card">
        <h3 style="font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 3rem;">Digital Stock Controller</h3>

        @forelse($inventories as $inventory)
        <div class="branch-adjust-rack">
            <span class="branch-lbl">{{ $inventory->branch }} Branch</span>

            <div class="current-stock-pill">
                <div style="font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Current Archive Count</div>
                <div class="stock-val-lg">{{ $inventory->quantity }}</div>
                <div style="margin-left: auto; font-size: 0.7rem; font-weight: 700; opacity: 0.6;">Trigger: {{ $inventory->reorder_level }}</div>
            </div>

            <form action="{{ route('inventory.update', $product) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="branch" value="{{ $inventory->branch }}">

                <div class="arch-field-group">
                    <label class="arch-field-label">Set New Quantity</label>
                    <input type="number" name="quantity" value="{{ $inventory->quantity }}" class="arch-input-underlined" required min="0">
                    @error('quantity') <span style="color: #802030; font-size: 0.75rem; font-weight: 700;">{{ $message }}</span> @enderror
                </div>

                <div class="arch-field-group">
                    <label class="arch-field-label">Trigger Reorder Threshold</label>
                    <input type="number" name="reorder_level" value="{{ $inventory->reorder_level }}" class="arch-input-underlined" min="0">
                </div>

                <button type="submit" class="btn-arch-save">
                    <i class="fas fa-sync-alt" style="margin-right: 0.5rem;"></i> Sync {{ $inventory->branch }} Stock
                </button>
            </form>
        </div>
        @empty
            <div style="text-align: center; padding: 4rem; color: #adb5bd;">
                <i class="fas fa-exclamation-circle" style="display: block; font-size: 2rem; margin-bottom: 1rem;"></i>
                No branch records found for this piece.
            </div>
        @endforelse
    </div>
</div>
@endsection
