@extends('layouts.app')

@section('title', 'Add Category')

@section('styles')
<style>
    .form-card { background: white; border-radius: 30px; border: 1px solid var(--color-border); padding: 3rem; max-width: 600px; }
    .form-title { font-size: 2rem; font-weight: 800; color: #1a1a1a; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid var(--color-border); font-size: 0.9rem; font-weight: 600; outline: none; transition: border-color 0.2s; }
    .form-control:focus { border-color: var(--color-editorial); }
    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 1rem 2.5rem; border-radius: 100px; border: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: transform 0.2s; }
</style>
@endsection

@section('content')
<div class="form-card">
    <h1 class="form-title">New Category</h1>
    
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. Dresses, Accessories">
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Optional details about this collection..."></textarea>
        </div>
        <div style="margin-top: 2rem;">
            <button type="submit" class="btn-arch-primary">Create Category</button>
            <a href="{{ route('categories.index') }}" style="margin-left: 1.5rem; font-size: 0.75rem; font-weight: 700; color: #adb5bd; text-decoration: none; text-transform: uppercase;">Cancel</a>
        </div>
    </form>
</div>
@endsection
