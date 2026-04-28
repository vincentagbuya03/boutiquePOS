@extends('layouts.app')

@section('title', 'Product Management')

@section('styles')
<style>
    .index-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .index-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .index-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .action-group { display: flex; gap: 1rem; }
    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; transition: transform 0.2s; }
    
    .btn-arch-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(128, 32, 48, 0.15); }

    /* Product Ribbon Grid */
    .arch-product-ribbon {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .arch-piece-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .arch-piece-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.04); border-color: rgba(128, 32, 48, 0.1); }

    .piece-img-box {
        position: relative;
        width: 100%;
        aspect-ratio: 1/1;
        background: #fdfdfd;
        overflow: hidden;
    }

    .piece-img-box img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
    .arch-piece-card:hover .piece-img-box img { transform: scale(1.1); }

    .piece-cat-pill {
        position: absolute;
        bottom: 1.5rem;
        left: 1.5rem;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(4px);
        color: #1a1a1a;
        padding: 0.5rem 1.25rem;
        border-radius: 100px;
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .piece-details { padding: 2rem; flex: 1; display: flex; flex-direction: column; }
    .piece-brand { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem; }
    .piece-name { font-size: 1.25rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.75rem; line-height: 1.2; }
    .piece-price { font-size: 1.75rem; font-weight: 800; color: var(--color-editorial); margin-bottom: 1.5rem; }

    .piece-meta { margin-top: auto; display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid #f8f9fa; }
    .size-badge { font-size: 0.7rem; font-weight: 800; color: #999; }
    
    .piece-actions { display: flex; gap: 0.75rem; }
    .btn-piece-link { 
        width: 38px; 
        height: 38px; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: #fdf2f4; 
        color: var(--color-editorial); 
        text-decoration: none;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .btn-piece-link.edit { background: #f1f3f5; color: #1a1a1a; }
    .btn-piece-link:hover { transform: scale(1.1); }
</style>
@endsection

@section('content')
<div class="index-header">
    <div>
        <h1 class="index-title">V'S Fashion: Product Catalog</h1>
        <p class="index-subtitle">A curated collection of V'S Fashion boutique pieces.</p>
    </div>
    <div class="action-group">
        @if(auth()->user()->canManageProducts())
            <a href="{{ route('products.create') }}" class="btn-arch-primary">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Add Products
            </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #bbfcce; color: #166534; padding: 1.25rem 2rem; border-radius: 100px; margin-bottom: 2.5rem; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="arch-product-ribbon">
    @forelse($products as $product)
    <div class="arch-piece-card">
        <div class="piece-img-box">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
            @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #f1f1f1; font-size: 3rem;">
                    <i class="fas fa-cube"></i>
                </div>
            @endif
            <span class="piece-cat-pill">{{ $product->category->name }}</span>
        </div>

        <div class="piece-details">
            @if($product->brand)
                <div class="piece-brand">{{ $product->brand }}</div>
            @endif
            <h3 class="piece-name">{{ $product->name }}</h3>
            <div class="piece-price" style="display: flex; flex-direction: column; gap: 0.25rem;">
                @if($product->firstAvailableBatch)
                    <span style="font-size: 1.75rem;">₱{{ number_format($product->firstAvailableBatch->selling_price, 2) }}</span>
                    @if(auth()->user()->canManageProducts())
                        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem;">
                            <span style="font-size: 0.7rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 4px;">Cost: ₱{{ number_format($product->firstAvailableBatch->cost_price, 2) }}</span>
                            <span style="font-size: 0.7rem; font-weight: 800; color: #166534; text-transform: uppercase; background: #f0fdf4; padding: 0.25rem 0.5rem; border-radius: 4px;">Profit: ₱{{ number_format($product->firstAvailableBatch->selling_price - $product->firstAvailableBatch->cost_price, 2) }}</span>
                        </div>
                    @endif
                @else
                    <span style="font-size: 1.25rem; color: #adb5bd;">Price Pending</span>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #adb5bd; text-transform: uppercase;">No Available Stock</span>
                @endif
            </div>

            <div class="piece-meta">
                <span class="size-badge">{{ $product->size ? 'SIZE: ' . $product->size : 'OS' }}</span>
                <div class="piece-actions">
                    <a href="{{ route('products.show', $product) }}" class="btn-piece-link" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if(auth()->user()->canManageProducts())
                        <a href="{{ route('products.edit', $product) }}" class="btn-piece-link edit" title="Edit Piece">
                            <i class="fas fa-pen-nib"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 8rem 0; color: #adb5bd;">
        <i class="fas fa-swatchbook" style="font-size: 4rem; opacity: 0.2; display: block; margin-bottom: 2rem;"></i>
        <p style="font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2rem;">No Archive Pieces Found</p>
        @if(auth()->user()->canManageProducts())
            <a href="{{ route('products.create') }}" class="btn-arch-primary">Add Your First Piece</a>
        @endif
    </div>
    @endforelse
</div>

@if($products->hasPages())
    <div style="margin-top: 4rem;">
        {{ $products->links() }}
    </div>
@endif
@endsection
