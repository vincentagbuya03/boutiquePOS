@extends('layouts.app')

@section('title', 'Add Product Batch')

@section('styles')
<style>
    .form-card { background: white; border-radius: 30px; border: 1px solid var(--color-border); padding: 3rem; max-width: 800px; }
    .form-title { font-size: 2rem; font-weight: 800; color: #1a1a1a; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid var(--color-border); font-size: 0.9rem; font-weight: 600; outline: none; transition: border-color 0.2s; }
    .form-control:focus { border-color: var(--color-editorial); }
    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 1rem 2.5rem; border-radius: 100px; border: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: transform 0.2s; }
    .btn-arch-primary:hover { transform: translateY(-2px); }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    .choices { margin-bottom: 0; }
    .choices__inner { 
        border-radius: 12px !important; 
        border: 1px solid var(--color-border) !important; 
        background: #f8f9fa !important; 
        padding: 0.5rem !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
    }
    .choices__list--dropdown { border-radius: 12px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; border: 1px solid #eee !important; }
    .choices__item--selectable { font-weight: 600 !important; font-size: 0.85rem !important; }
    .choices__input { background-color: transparent !important; font-size: 0.85rem !important; }
</style>
@endsection

@section('content')
<div class="form-card">
    <h1 class="form-title">Log New Batch</h1>
    
    <form action="{{ route('batches.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Product</label>
            <select name="product_id" class="form-control" required>
                <option value="">Select Piece...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} {{ $product->size ? '('.$product->size.')' : '' }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">Select Supplier...</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Batch Number</label>
                <input type="text" name="batch_number" class="form-control" required value="{{ $batchNumber }}" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
            </div>
            <div class="form-group">
                <label class="form-label">Quantity Received</label>
                <input type="number" name="quantity" class="form-control" required min="1">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Cost per Piece (₱)</label>
                <input type="number" step="0.01" name="cost_price" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Selling Price (₱)</label>
                <input type="number" step="0.01" name="selling_price" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date Received</label>
                <input type="date" name="date_received" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn-arch-primary">Log Batch & Update Inventory</button>
            <a href="{{ route('batches.index') }}" style="margin-left: 1.5rem; font-size: 0.75rem; font-weight: 700; color: #adb5bd; text-decoration: none; text-transform: uppercase;">Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = new Choices('select[name="product_id"]', {
            searchEnabled: true,
            itemSelectText: '',
            allowHTML: true,
            shouldSort: false
        });
        
        const supplierSelect = new Choices('select[name="supplier_id"]', {
            searchEnabled: true,
            itemSelectText: '',
            allowHTML: true,
            shouldSort: false
        });
    });
</script>
@endsection
