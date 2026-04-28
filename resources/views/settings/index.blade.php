@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="settings-container">
    <div class="view-header">
        <h1 class="view-content-title">Account Settings</h1>
        <p class="view-content-subtitle">Manage your profile and security preferences</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pos-card">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="settings-section">
                <h3 class="section-title"><i class="fas fa-user-circle"></i> Personal Information</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" name="contact" id="contact" class="form-control" value="{{ old('contact', $user->contact) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly disabled style="background: #f8f9fa;">
                        <small style="color: #999;">Role cannot be changed by yourself.</small>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="address">Residential Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <hr class="section-divider">

            <div class="settings-section">
                <h3 class="section-title"><i class="fas fa-lock"></i> Security & Password</h3>
                <p class="section-desc">Leave password fields blank if you don't want to change your current password.</p>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="settings-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-check-circle"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .settings-container {
        max-width: 900px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-editorial);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        opacity: 0.8;
    }

    .section-desc {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .section-divider {
        border: 0;
        border-top: 1px solid #f1f1f1;
        margin: 2.5rem 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #444;
    }

    .form-control {
        padding: 0.85rem 1.25rem;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.9rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-editorial);
        box-shadow: 0 0 0 4px rgba(128, 32, 48, 0.05);
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-size: 0.9rem;
    }

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #dc2626;
    }

    .settings-actions {
        margin-top: 3rem;
        display: flex;
        justify-content: flex-end;
    }

    .btn-save {
        background: var(--color-editorial);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(128, 32, 48, 0.2);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(128, 32, 48, 0.3);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
