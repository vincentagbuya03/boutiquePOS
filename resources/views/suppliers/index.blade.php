@extends('layouts.app')

@section('title', 'Suppliers')

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
        <h1 class="index-title">Suppliers</h1>
        <p class="index-subtitle">Manage your boutique's sourcing partners.</p>
    </div>
    <div class="action-group">
        <a href="{{ route('suppliers.create') }}" class="btn-arch-primary">
            <i class="fas fa-plus"></i> Add Supplier
        </a>
    </div>
</div>

<div class="arch-table-card">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->contact_person }}</td>
                <td>{{ $supplier->phone }}</td>
                <td style="color: #999;">{{ $supplier->email }}</td>
                <td style="text-align: right;">
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="action-btn-mini" title="Edit">
                        <i class="fas fa-pen-nib"></i>
                    </a>
                    <form id="archiveSupplierForm{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="action-btn-mini" title="Archive" onclick="showArchiveModal(document.getElementById('archiveSupplierForm{{ $supplier->id }}'), 'Archive Supplier?', 'Existing batches will keep this supplier history.')">
                            <i class="fas fa-archive"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 5rem 0; color: #adb5bd;">No suppliers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
