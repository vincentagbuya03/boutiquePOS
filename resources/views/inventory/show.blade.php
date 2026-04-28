@extends('layouts.app')

@section('title', 'Product Inventory')

@section('content')
<div>
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('inventory.index') }}" style="color: #667eea; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <div class="card">
                <h2 style="margin-bottom: 1rem;">{{ $product->name }}</h2>
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 1rem;">
                @else
                    <div style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: #999;">
                        No Image
                    </div>
                @endif

                <table style="width: 100%;">
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 0.75rem 0; font-weight: 600;">Category:</td>
                        <td style="padding: 0.75rem 0;">{{ $product->category->name }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 0.75rem 0; font-weight: 600;">Price:</td>
                        <td style="padding: 0.75rem 0; color: #667eea; font-weight: bold;">
                            @if($product->firstAvailableBatch)
                                ₱{{ number_format($product->firstAvailableBatch->selling_price, 2) }}
                            @else
                                <span style="color: #adb5bd;">N/A (No Available Stock)</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <a href="{{ route('products.show', $product) }}" class="btn btn-primary" style="margin-top: 1rem; width: 100%; text-align: center;">
                    <i class="fas fa-info-circle"></i> View Product Details
                </a>
            </div>
        </div>

        <div>
            <div class="card">
                <h3 style="margin-bottom: 1rem;">Inventory by Branch</h3>

                @forelse($inventories as $inventory)
                <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee;">
                    <h4 style="margin-bottom: 0.5rem;">
                        {{ $inventory->branch }} Branch
                        @if($inventory->isBelowReorderLevel())
                            <span style="background: #fed7d7; color: #c53030; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">LOW STOCK</span>
                        @endif
                    </h4>
                    <div style="background: #f5f5f5; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <div style="margin-bottom: 0.5rem;">
                            <strong>Available Stock:</strong>
                            <span style="color: @if($inventory->isBelowReorderLevel()) #f56565 @else #48bb78 @endif; font-weight: bold; font-size: 1.25rem;">
                                {{ $inventory->quantity }}
                            </span>
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            <strong>Reorder Level:</strong> {{ $inventory->reorder_level }}
                        </div>
                        <div>
                            <strong>Last Updated:</strong> {{ $inventory->last_updated->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
                @empty
                <p style="color: #999;">No inventory records for this product</p>
                @endforelse

                <a href="{{ route('inventory.edit', $product) }}" class="btn btn-success" style="width: 100%; text-align: center;">
                    <i class="fas fa-edit"></i> Adjust Inventory
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
