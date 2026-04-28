@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="edit-product-container">
    <div class="view-navigation">
        <a href="{{ route('products.index') }}" class="back-link">
            <i class="fas fa-chevron-left"></i> Back to Catalog
        </a>
    </div>

    <div class="view-header">
        <h1 class="view-content-title">Edit Product</h1>
        <p class="view-content-subtitle">Update your collection details with precision</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pos-card product-card-form">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-layout-split">
                <div class="form-main-content">
                    <div class="form-section">
                        <h3 class="section-label">General Specifications</h3>
                        
                        <div class="form-group">
                            <label for="name">Product Identity *</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required placeholder="e.g. Silk Evening Gown">
                            <p class="input-hint"><i class="fas fa-info-circle"></i> Must be unique across the entire catalog.</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="category_id">Category Segment *</label>
                                <select id="category_id" name="category_id" class="form-control select-custom" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if(old('category_id', $product->category_id) == $category->id) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="brand">Designer / Brand</label>
                                <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" placeholder="e.g. V's Atelier">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="size">Sizing Detail</label>
                            <input type="text" id="size" name="size" class="form-control" value="{{ old('size', $product->size) }}" placeholder="e.g. Small / 38 / One Size">
                        </div>

                        <div class="form-group">
                            <label for="description">Creative Description</label>
                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Describe the materials, fit, and style...">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-sidebar-content">
                    <div class="form-section">
                        <h3 class="section-label">Visual Asset</h3>
                        
                        <div class="image-management-area">
                            @if($product->image_path)
                                <div class="current-image-wrapper">
                                    <span class="badge-overlay">Active View</span>
                                    <img id="currentImagePreview" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-image-preview">
                                </div>
                            @endif

                            <div class="upload-zone {{ $product->image_path ? 'has-current' : '' }}" id="imageDropZone">
                                <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                                <div id="imagePreview" style="display: none;">
                                    <img id="imagePreviewImg" src="" alt="Preview" class="product-image-preview new-preview">
                                    <div class="preview-tag">New Asset Selected</div>
                                </div>
                                <div id="imagePrompt" class="upload-prompt">
                                    <div class="icon-circle">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <p class="primary-text">Replace Visual Asset</p>
                                    <p class="secondary-text">Drag & Drop or Click to Browse</p>
                                    <p class="specs-text">JPG, PNG, GIF • Max 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-footer-actions">
                <a href="{{ route('products.show', $product) }}" class="btn-secondary">
                    Cancel Changes
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check-circle"></i> Synchronize Updates
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-product-container {
        max-width: 1100px;
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

    .product-card-form {
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }

    .form-layout-split {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 4rem;
    }

    .section-label {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--color-editorial);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #fdf2f4;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.6rem;
    }

    .form-control {
        width: 100%;
        padding: 1rem 1.25rem;
        background: #fdfdfd;
        border: 1px solid #eee;
        border-radius: 14px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: #1a1a1a;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        outline: none;
        background: #fff;
        border-color: var(--color-editorial);
        box-shadow: 0 8px 24px rgba(128, 32, 48, 0.06);
    }

    .select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23adb5bd'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.25rem center;
        background-size: 1.25rem;
    }

    .input-hint {
        font-size: 0.75rem;
        color: #adb5bd;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    /* Image Management */
    .image-management-area {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .current-image-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #eee;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .product-image-preview {
        width: 100%;
        height: auto;
        display: block;
        max-height: 400px;
        object-fit: cover;
    }

    .badge-overlay {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(128, 32, 48, 0.9);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 100px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        backdrop-filter: blur(4px);
    }

    .upload-zone {
        background: #fcfcfc;
        border: 2px dashed #eee;
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .upload-zone:hover {
        background: #fff;
        border-color: var(--color-editorial);
    }

    .upload-zone.has-current {
        padding: 2rem;
    }

    .upload-prompt .icon-circle {
        width: 60px;
        height: 60px;
        background: #fdf2f4;
        color: var(--color-editorial);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1.25rem;
        transition: all 0.3s;
    }

    .upload-zone:hover .icon-circle {
        transform: scale(1.1);
        background: var(--color-editorial);
        color: #fff;
    }

    .upload-prompt .primary-text {
        font-weight: 800;
        color: #1a1a1a;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .upload-prompt .secondary-text {
        color: #999;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .upload-prompt .specs-text {
        font-size: 0.7rem;
        color: #ccc;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .preview-tag {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 0.5rem;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        backdrop-filter: blur(4px);
    }

    /* Footer Actions */
    .form-footer-actions {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid #f1f1f1;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 2rem;
    }

    .btn-secondary {
        color: #adb5bd;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .btn-secondary:hover {
        color: #1a1a1a;
    }

    .btn-primary {
        background: var(--color-editorial);
        color: #fff;
        border: none;
        padding: 1.1rem 2.5rem;
        border-radius: 16px;
        font-weight: 800;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 30px rgba(128, 32, 48, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(128, 32, 48, 0.3);
    }

    .btn-primary:active {
        transform: translateY(-1px);
    }

    .alert-danger {
        background: #fdf2f4;
        border: 1px solid #f8d7da;
        color: #842029;
        padding: 1.25rem;
        border-radius: 16px;
        margin-bottom: 2.5rem;
        font-size: 0.9rem;
    }

    @media (max-width: 992px) {
        .form-layout-split {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        .form-sidebar-content {
            order: -1;
        }
        .product-card-form {
            padding: 2rem;
        }
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imageDropZone = document.getElementById('imageDropZone');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewImg = document.getElementById('imagePreviewImg');
        const imagePrompt = document.getElementById('imagePrompt');
        const currentImagePreview = document.getElementById('currentImagePreview');

        if (imageDropZone) {
            // Click to upload
            imageDropZone.addEventListener('click', () => imageInput.click());

            // Drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                imageDropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                imageDropZone.addEventListener(eventName, () => {
                    imageDropZone.style.borderColor = 'var(--color-editorial)';
                    imageDropZone.style.background = '#fdf2f4';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                imageDropZone.addEventListener(eventName, () => {
                    imageDropZone.style.borderColor = '#eee';
                    imageDropZone.style.background = '#fcfcfc';
                }, false);
            });

            imageDropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    imageInput.files = files;
                    handleImageSelect();
                }
            });

            // File input change
            imageInput.addEventListener('change', handleImageSelect);

            function handleImageSelect() {
                const file = imageInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreviewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                        imagePrompt.style.display = 'none';
                        if (currentImagePreview) {
                            currentImagePreview.parentElement.style.opacity = '0.3';
                            currentImagePreview.parentElement.style.filter = 'grayscale(1)';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    });
</script>
@endsection
