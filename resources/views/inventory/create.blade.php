@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div>
    <h1 style="margin-bottom: 2rem;">Add New Product</h1>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Product Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" value="{{ old('brand') }}">
                @error('brand') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="price">Price *</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
                @error('price') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="size">Size</label>
                <input type="text" id="size" name="size" value="{{ old('size') }}">
                @error('size') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                @error('image') <span style="color: #f56565;">{{ $message }}</span> @enderror
            </div>

            <div style="background: #f5f5f5; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                <h3 style="margin-bottom: 1rem;">Initial Stock</h3>
                
                <div class="form-group">
                    <label for="quantity_dagupan">Dagupan Branch Quantity</label>
                    <input type="number" id="quantity_dagupan" name="quantity_dagupan" value="{{ old('quantity_dagupan', 0) }}" min="0">
                </div>

                <div class="form-group">
                    <label for="quantity_sancarlos">San Carlos Branch Quantity</label>
                    <input type="number" id="quantity_sancarlos" name="quantity_sancarlos" value="{{ old('quantity_sancarlos', 0) }}" min="0">
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Save Product
                </button>
                <a href="{{ route('products.index') }}" class="btn" style="background: #999; color: white; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
