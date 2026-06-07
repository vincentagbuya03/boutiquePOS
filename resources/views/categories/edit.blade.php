@extends('layouts.app')

@section('title', 'Edit Category')

@section('styles')
<style>
    .edit-page {
        max-width: 760px;
        margin: 0 auto;
    }

    .view-navigation {
        margin-bottom: 2rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #888;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: var(--color-editorial);
    }

    .edit-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.02);
    }

    .edit-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .edit-subtitle {
        color: #999;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        font-size: 0.9rem;
        font-weight: 600;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: var(--color-editorial);
        box-shadow: 0 0 0 4px rgba(128, 32, 48, 0.06);
    }

    .btn-arch-primary {
        background: var(--color-editorial);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 100px;
        border: none;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-arch-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(128, 32, 48, 0.15);
    }

    .btn-arch-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #adb5bd;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
</style>
@endsection

@section('content')
<div class="edit-page">
    <div class="view-navigation">
        <a href="{{ route('categories.index') }}" class="back-link">
            <i class="fas fa-chevron-left"></i> Back to Categories
        </a>
    </div>

    <div class="edit-card">
        <h1 class="edit-title">Edit Category</h1>
        <p class="edit-subtitle">Update this collection label and description.</p>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Category Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $category->name) }}"
                    required
                    placeholder="e.g. Dresses, Accessories"
                >
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Optional details about this collection..."
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn-arch-primary">Save Changes</button>
                <a href="{{ route('categories.index') }}" class="btn-arch-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection