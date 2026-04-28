@extends('layouts.app')

@section('title', 'Archive: Create New Piece')

@section('styles')
<style>
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-header { margin-bottom: 3.5rem; }
    .form-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .form-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .arch-form-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 4rem;
        box-shadow: 0 40px 80px rgba(0,0,0,0.02);
    }

    .arch-field-group { margin-bottom: 2.5rem; }
    .arch-label { font-size: 0.65rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.15em; display: block; margin-bottom: 1rem; }
    
    .arch-input {
        width: 100%;
        border: none;
        border-bottom: 2px solid #f1f1f1;
        padding: 1rem 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #1a1a1a;
        background: transparent;
        transition: all 0.3s;
        outline: none;
    }
    .arch-input:focus { border-color: var(--color-editorial); }
    
    select.arch-input { appearance: none; cursor: pointer; }

    .arch-upload-zone {
        background: #fdfdfd;
        border: 2px dashed #f1f1f1;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }
    .arch-upload-zone:hover { border-color: var(--color-editorial); background: #fdf2f4; }

    .btn-arch-submit { 
        background: var(--color-editorial); 
        color: white; 
        padding: 1.25rem 3.5rem; 
        border-radius: 100px; 
        border: none; 
        font-weight: 800; 
        font-size: 0.8rem; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        cursor: pointer;
        transition: transform 0.2s;
        display: block;
        margin-top: 4rem;
        width: 100%;
    }
    .btn-arch-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(128, 32, 48, 0.2); }

    .preview-arch-box { width: 200px; height: 200px; border-radius: 16px; object-fit: cover; margin: 0 auto 1.5rem auto; border: 1px solid #eee; display: none; }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('products.index') }}" style="color: #adb5bd; text-decoration: none; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">
            <i class="fas fa-arrow-left"></i> Master Catalog
        </a>
        <h1 class="form-title" style="margin-top: 1.5rem;">Create New Piece</h1>
        <p class="form-subtitle">Registering a new identity in the V’S Fashion Archive.</p>
    </div>

    <div class="arch-form-card">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="arch-field-group">
                <label class="arch-label">Piece Name *</label>
                <input type="text" name="name" class="arch-input" placeholder="e.g. Classic Silk Blazer" value="{{ old('name') }}" required>
                @error('name') <span style="color: var(--color-editorial); font-size: 0.7rem; font-weight: 700;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 2.5rem;">
                <div class="arch-field-group">
                    <label class="arch-label">Category *</label>
                    <select name="category_id" class="arch-input" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                <div class="arch-field-group">
                    <label class="arch-label">Brand</label>
                    <input type="text" name="brand" class="arch-input" placeholder="e.g. V's Exclusive" value="{{ old('brand') }}">
                </div>
                <div class="arch-field-group">
                    <label class="arch-label">Size Specification</label>
                    <input type="text" name="size" class="arch-input" placeholder="e.g. Medium / 32" value="{{ old('size') }}">
                </div>
            </div>

            <div class="arch-field-group">
                <label class="arch-label">Piece Narrative / Description</label>
                <textarea name="description" class="arch-input" rows="2" placeholder="Describe the archive piece details...">{{ old('description') }}</textarea>
            </div>

            <div class="arch-field-group">
                <label class="arch-label">Visual Representation</label>
                <div class="arch-upload-zone" id="dropzone">
                    <input type="file" name="image" id="fileInput" accept="image/*" style="display: none;">
                    <img id="imagePreview" class="preview-arch-box">
                    <div id="prompt">
                        <i class="fas fa-camera-retro" style="font-size: 2.5rem; color: #eee; margin-bottom: 1.5rem; display: block;"></i>
                        <p style="font-weight: 800; font-size: 0.8rem; color: #adb5bd; text-transform: uppercase;">Register Photography</p>
                        <p style="font-size: 0.7rem; color: #adb5bd; margin-top: 0.5rem;">Drag JPG/PNG or Click to Capture</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-arch-submit">Authenticate & Save Piece</button>
        </form>
    </div>
</div>

<script>
    const dropzone = document.getElementById('dropzone');
    const input = document.getElementById('fileInput');
    const preview = document.getElementById('imagePreview');
    const prompt = document.getElementById('prompt');

    dropzone.addEventListener('click', () => input.click());
    input.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (re) => {
                preview.src = re.target.result;
                preview.style.display = 'block';
                prompt.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
