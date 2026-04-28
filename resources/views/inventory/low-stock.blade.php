@extends('layouts.app')

@section('title', 'Low Stock Items')

@section('content')
<div>
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('inventory.index') }}" style="color: #667eea; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <h1 style="color: #f56565; margin-bottom: 2rem;">
        <i class="fas fa-exclamation-triangle"></i> Low Stock Items
    </h1>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        @if($lowStockItems->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Branch</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Shortage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockItems as $inventory)
                        <tr style="background: #fff5f5;">
                            <td style="vertical-align: middle;">
                                @if($inventory->product->image_path)
                                    <img src="{{ asset('storage/' . $inventory->product->image_path) }}" alt="{{ $inventory->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $inventory->product->name }}</strong>
                            </td>
                            <td>{{ $inventory->product->category->name }}</td>
                            <td>{{ $inventory->branch }}</td>
                            <td>
                                <span style="color: #f56565; font-weight: bold;">
                                    {{ $inventory->quantity }}
                                </span>
                            </td>
                            <td>{{ $inventory->reorder_level }}</td>
                            <td>
                                <span style="background: #fed7d7; color: #c53030; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: bold;">
                                    {{ $inventory->reorder_level - $inventory->quantity }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('inventory.edit', $inventory->product) }}" class="btn btn-sm" style="background: #f56565; color: white; text-decoration: none; padding: 0.5rem 0.75rem;">
                                    <i class="fas fa-plus"></i> Restock
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem;">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: #48bb78; margin-bottom: 1rem; display: block;"></i>
                <h2 style="color: #48bb78; margin-bottom: 0.5rem;">All Stock Levels Healthy</h2>
                <p style="color: #999;">No items are currently below reorder level.</p>
                <a href="{{ route('inventory.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    View All Inventory
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
