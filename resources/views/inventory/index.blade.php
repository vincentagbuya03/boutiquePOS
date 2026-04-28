@extends('layouts.app')

@section('title', 'Inventory Management')

@section('styles')
<style>
    .index-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .index-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .index-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .action-group { display: flex; gap: 1rem; }
    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; transition: transform 0.2s; }
    .btn-arch-secondary { background: white; border: 1px solid var(--color-border); color: #1a1a1a; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; }
    .btn-arch-danger { background: #fdf2f2; border: 1px solid #fee2e2; color: #802030; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; }
    
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

    /* Product Specific Icons */
    .p-img-frame { width: 50px; height: 50px; border-radius: 14px; background: #fdfdfd; border: 1px solid #eee; overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .p-img-frame img { width: 100%; height: 100%; object-fit: cover; }

    /* Status Badges */
    .badge-arch { padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-mauve { background: #f8f3f4; color: #c299a0; }
    .badge-teal { background: #f0f6f6; color: #3c5e5e; }
    .badge-maroon { background: #fdf2f2; color: #802030; }

    .qty-display { font-size: 1rem; font-weight: 800; }
    .qty-urgent { color: #802030; }
    .qty-safe { color: #3c5e5e; }

    .action-btn-mini { color: #adb5bd; transition: color 0.2s; margin-left: 0.75rem; }
    .action-btn-mini:hover { color: var(--color-editorial); }
</style>
@endsection

@section('content')
<div class="index-header">
    <div>
        <h1 class="index-title">V'S Fashion: Inventory</h1>
        <p class="index-subtitle">Manage the physical stock of V’S Fashion Boutique (San Carlos Branch).</p>
    </div>
    <div class="action-group">
        @if(auth()->user()->canManageInventory())
            <a href="{{ route('inventory.low-stock') }}" class="btn-arch-danger">
                <i class="fas fa-bolt" style="margin-right: 0.5rem;"></i> Restock Alerts
            </a>
        @endif
        <a href="{{ route('batches.create') }}" class="btn-arch-primary">
            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Add Batch
        </a>
        <a href="{{ route('products.index') }}" class="btn-arch-secondary">
            <i class="fas fa-swatchbook" style="margin-right: 0.5rem;"></i> Master Catalog
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #bbfcce; color: #166534; padding: 1.25rem 2rem; border-radius: 100px; margin-bottom: 2.5rem; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="arch-table-card">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Piece</th>
                <th>Identity</th>
                <th>Category</th>
                <th>Availability</th>
                <th>Status</th>
                <th>Logged</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $inventory)
            <tr>
                <td>
                    <div class="p-img-frame">
                        @if($inventory->product->image_path)
                            <img src="{{ asset('storage/' . $inventory->product->image_path) }}" alt="{{ $inventory->product->name }}">
                        @else
                            <i class="fas fa-archive" style="color: #eee;"></i>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="font-weight: 800; color: #1a1a1a;">{{ $inventory->product->name }}</div>
                </td>
                <td><span class="badge-arch badge-mauve">{{ $inventory->product->category->name }}</span></td>
                <td>
                    <div class="qty-display {{ $inventory->isBelowReorderLevel() ? 'qty-urgent' : 'qty-safe' }}">
                        {{ $inventory->quantity }} <small style="font-size: 0.65rem; color: #adb5bd;">/ L{{ $inventory->reorder_level }}</small>
                    </div>
                </td>
                <td>
                    <span class="badge-arch {{ $inventory->isBelowReorderLevel() ? 'badge-maroon' : 'badge-teal' }}">
                        {{ $inventory->isBelowReorderLevel() ? 'Urgent Restock' : 'Stocked' }}
                    </span>
                </td>
                <td style="color: #999; font-size: 0.75rem;">{{ $inventory->last_updated->format('M d, Y') }}</td>
                <td style="text-align: right;">
                    <a href="{{ route('inventory.show', $inventory->product) }}" class="action-btn-mini">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if(auth()->user()->canManageInventory())
                        <a href="{{ route('inventory.edit', $inventory->product) }}" class="action-btn-mini">
                            <i class="fas fa-pen-nib"></i>
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 5rem 0; color: #adb5bd;">
                    <i class="fas fa-box-open" style="font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 1.5rem;"></i>
                    No inventory records present in this sequence.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($inventories->hasPages())
    <div style="margin-top: 2.5rem;">
        {{ $inventories->links() }}
    </div>
    @endif
</div>
@endsection
