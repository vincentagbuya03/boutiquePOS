@extends('layouts.app')

@section('title', 'Categories')

@section('styles')
<style>
    .index-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .index-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .index-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; transition: transform 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-arch-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(128, 32, 48, 0.15); }

    .arch-table-card { background: white; border-radius: 30px; border: 1px solid var(--color-border); padding: 2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.02); }
    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.5rem 1rem; font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 1px solid #f8f9fa; }
    .arch-table td { padding: 1.5rem 1rem; border-bottom: 1px solid #f8f9fa; font-size: 0.85rem; font-weight: 600; vertical-align: middle; }

    .action-btn-mini { color: #adb5bd; transition: color 0.2s; margin-left: 0.75rem; background: none; border: none; cursor: pointer; padding: 0; }
    .action-btn-mini:hover { color: var(--color-editorial); }
</style>
@endsection

@section('content')
<div class="index-header">
    <div>
        <h1 class="index-title">Categories</h1>
        <p class="index-subtitle">Organize your boutique's collections.</p>
    </div>
    <div class="action-group">
        <a href="{{ route('categories.create') }}" class="btn-arch-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>
</div>

<div class="arch-table-card">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Products Count</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td style="color: #999;">{{ $category->description ?? 'No description' }}</td>
                <td>{{ $category->products()->count() }}</td>
                <td style="text-align: right;">
                    <a href="{{ route('categories.edit', $category) }}" class="action-btn-mini" title="Edit">
                        <i class="fas fa-pen-nib"></i>
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn-mini" title="Delete" onclick="return confirm('Are you sure you want to delete this category?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 5rem 0; color: #adb5bd;">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
