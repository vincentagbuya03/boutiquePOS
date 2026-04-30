@extends('layouts.app')

@section('title', 'Archive')

@section('styles')
<style>
    .archive-header { margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem; }
    .archive-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .archive-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }
    .archive-section { margin-bottom: 3rem; }
    .archive-section-title { font-size: 1.1rem; font-weight: 900; color: #1a1a1a; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; }
    .archive-table-shell { background: white; border: 1px solid var(--color-border); border-radius: 24px; padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.02); overflow-x: auto; }
    .archive-table { width: 100%; border-collapse: collapse; min-width: 760px; }
    .archive-table th { text-align: left; padding: 1rem; font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 1px solid #f8f9fa; }
    .archive-table td { padding: 1rem; border-bottom: 1px solid #f8f9fa; font-size: 0.85rem; font-weight: 600; vertical-align: middle; }
    .archive-empty { color: #adb5bd; padding: 2rem 1rem; font-weight: 800; text-align: center; text-transform: uppercase; letter-spacing: 0.08em; }
    .restore-btn { border: none; background: #f0fdf4; color: #166534; padding: 0.7rem 1rem; border-radius: 999px; cursor: pointer; font-weight: 900; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.08em; display: inline-flex; align-items: center; gap: 0.5rem; }
    .restore-btn:hover { background: #dcfce7; }
    .muted { color: #999; }
    .badge-archive { display: inline-flex; align-items: center; padding: 0.4rem 0.8rem; border-radius: 999px; background: #f8f3f4; color: #802030; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em; }
</style>
@endsection

@section('content')
<div class="archive-header">
    <div>
        <h1 class="archive-title">Archive</h1>
        <p class="archive-subtitle">Restore records that were hidden from active lists.</p>
    </div>
</div>

@php
    $sections = [
        [
            'title' => 'Products',
            'icon' => 'fa-swatchbook',
            'type' => 'products',
            'items' => $archivedProducts,
            'columns' => ['Name', 'Category', 'Brand / Size', 'Archived', ''],
            'row' => fn($product) => [
                e($product->name),
                e($product->category->name ?? 'Archived category'),
                e(($product->brand ?? 'No brand') . ' / ' . ($product->size ?? 'OS')),
                e($product->deleted_at?->format('M d, Y g:i A')),
            ],
        ],
        [
            'title' => 'Categories',
            'icon' => 'fa-tags',
            'type' => 'categories',
            'items' => $archivedCategories,
            'columns' => ['Name', 'Description', 'Products', 'Archived', ''],
            'row' => fn($category) => [
                e($category->name),
                e($category->description ?? 'No description'),
                e($category->products_count),
                e($category->deleted_at?->format('M d, Y g:i A')),
            ],
        ],
        [
            'title' => 'Suppliers',
            'icon' => 'fa-truck-loading',
            'type' => 'suppliers',
            'items' => $archivedSuppliers,
            'columns' => ['Name', 'Contact', 'Email', 'Archived', ''],
            'row' => fn($supplier) => [
                e($supplier->name),
                e($supplier->contact_person ?? 'No contact'),
                e($supplier->email ?? 'No email'),
                e($supplier->deleted_at?->format('M d, Y g:i A')),
            ],
        ],
        [
            'title' => 'Product Batches',
            'icon' => 'fa-layer-group',
            'type' => 'batches',
            'items' => $archivedBatches,
            'columns' => ['Batch', 'Product', 'Supplier', 'Quantity', 'Archived', ''],
            'row' => fn($batch) => [
                e($batch->batch_number),
                e($batch->product->name ?? 'Archived product'),
                e($batch->supplier->name ?? 'No supplier'),
                e($batch->quantity),
                e($batch->deleted_at?->format('M d, Y g:i A')),
            ],
        ],
    ];

    if (auth()->user()->isOwner() || auth()->user()->isAdmin()) {
        $sections[] = [
            'title' => 'Personnel',
            'icon' => 'fa-user-shield',
            'type' => 'users',
            'items' => $archivedUsers,
            'columns' => ['Name', 'Email', 'Role', 'Archived', ''],
            'row' => fn($user) => [
                e($user->name),
                e($user->email),
                e(ucfirst($user->role)),
                e($user->deleted_at?->format('M d, Y g:i A')),
            ],
        ];
    }
@endphp

@foreach($sections as $section)
    <section class="archive-section">
        <h2 class="archive-section-title">
            <i class="fas {{ $section['icon'] }}"></i>
            {{ $section['title'] }}
            <span class="badge-archive">{{ $section['items']->count() }}</span>
        </h2>

        <div class="archive-table-shell">
            @if($section['items']->isEmpty())
                <div class="archive-empty">No archived {{ strtolower($section['title']) }}.</div>
            @else
                <table class="archive-table">
                    <thead>
                        <tr>
                            @foreach($section['columns'] as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section['items'] as $item)
                            <tr>
                                @foreach($section['row']($item) as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                                <td style="text-align: right;">
                                    <form action="{{ route('archives.restore', [$section['type'], $item->id]) }}" method="POST" onsubmit="return confirm('Unarchive this record?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="restore-btn">
                                            <i class="fas fa-rotate-left"></i>
                                            Unarchive
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>
@endforeach
@endsection
