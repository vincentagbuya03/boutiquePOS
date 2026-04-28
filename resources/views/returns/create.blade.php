@extends('layouts.app')

@section('title', 'Log New Claim')

@section('styles')
<style>
    .create-claim-container {
        max-width: 900px;
    }

    .form-card {
        padding: 3.5rem;
    }

    .section-label {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-editorial);
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 0.75rem;
    }

    .form-control {
        width: 100%;
        padding: 1.1rem 1.5rem;
        background: #fdfdfd;
        border: 1px solid #eee;
        border-radius: 16px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: #1a1a1a;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        outline: none;
        background: #fff;
        border-color: var(--color-editorial);
        box-shadow: 0 10px 30px rgba(128, 32, 48, 0.05);
    }

    .select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23adb5bd'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.25rem center;
        background-size: 1.25rem;
    }

    .btn-submit {
        background: var(--color-editorial);
        color: #fff;
        border: none;
        padding: 1.25rem 3rem;
        border-radius: 100px;
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 30px rgba(128, 32, 48, 0.2);
        transition: all 0.3s;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(128, 32, 48, 0.3);
    }

    .btn-cancel {
        color: #adb5bd;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: color 0.2s;
    }

    .btn-cancel:hover {
        color: #1a1a1a;
    }

    .actions-footer {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #f8f9fa;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 2.5rem;
    }

    .error-tag {
        font-size: 0.75rem;
        color: #dc2626;
        margin-top: 0.5rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group.full-width {
            grid-column: span 1;
        }
    }
</style>
@endsection

@section('content')
<div class="create-claim-container">
    <div class="view-header">
        <h1 class="view-content-title">Log New Claim</h1>
        <p class="view-content-subtitle">Register a product reversal or quality discrepancy</p>
    </div>

    <div class="pos-card form-card">
        <form action="{{ route('returns.store') }}" method="POST">
            @csrf

            <h3 class="section-label"><i class="fas fa-tag"></i> Transaction Details</h3>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="product_id">Product Asset *</label>
                    <select id="product_id" name="product_id" class="form-control select-custom" required>
                        <option value="">Select an item from catalog...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @if(old('product_id') == $product->id) selected @endif>
                                {{ $product->name }} ({{ $product->category->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="error-tag">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="quantity_returned">Quantity Volume *</label>
                    <input type="number" id="quantity_returned" name="quantity_returned" class="form-control" value="{{ old('quantity_returned', 1) }}" min="1" required>
                    @error('quantity_returned') <div class="error-tag">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="return_date">Claim Timestamp *</label>
                    <input type="date" id="return_date" name="return_date" class="form-control" value="{{ old('return_date', date('Y-m-d')) }}" required>
                    @error('return_date') <div class="error-tag">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full-width">
                    <label for="reason">Primary Justification *</label>
                    <select id="reason" name="reason" class="form-control select-custom" required>
                        <option value="">Select a reason category...</option>
                        @foreach(['damaged' => 'Damaged Goods', 'defective' => 'Manufacturing Defect', 'wrong_item' => 'Incorrect Item Fulfilled', 'customer_request' => 'Customer Change of Mind'] as $key => $label)
                            <option value="{{ $key }}" @if(old('reason') === $key) selected @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('reason') <div class="error-tag">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full-width">
                    <label for="description">Detailed Dossier / Observations</label>
                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Provide a detailed narrative of the discrepancy or condition of the item...">{{ old('description') }}</textarea>
                    @error('description') <div class="error-tag">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="actions-footer">
                <a href="{{ route('returns.index') }}" class="btn-cancel">
                    Cancel Entry
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Authenticate Claim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
