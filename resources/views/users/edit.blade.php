@extends('layouts.app')

@section('title', 'Edit User')

@section('styles')
<style>
    .edit-user-container {
        max-width: 1280px;
        margin: 0 auto;
    }

    .edit-user-header {
        margin-bottom: 2.25rem;
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

    .edit-user-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        letter-spacing: -0.03em;
        line-height: 1.1;
    }

    .edit-user-subtitle {
        color: #999;
        font-size: 1rem;
        font-weight: 500;
        margin-top: 0.45rem;
    }

    .edit-user-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.85fr);
        gap: 1.5rem;
        align-items: start;
    }

    .panel-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 26px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .panel-card-body {
        padding: 2rem;
    }

    .panel-section + .panel-section {
        margin-top: 1.75rem;
        padding-top: 1.75rem;
        border-top: 1px solid #f1f1f1;
    }

    .section-heading {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--color-editorial);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .section-heading i {
        opacity: 0.85;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 1.25rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.1rem;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 48px;
        padding: 0.9rem 1rem;
        border-radius: 14px;
        border: 1px solid #e7e7e7;
        background: #fff;
        color: #1a1a1a;
        font: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--color-editorial);
        box-shadow: 0 0 0 4px rgba(128, 32, 48, 0.06);
        background: #fff;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .is-invalid {
        border-color: #dc2626 !important;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 14px;
        padding: 0.9rem 1.2rem;
        font-weight: 800;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .btn-primary {
        background: var(--color-editorial);
        color: #fff;
        box-shadow: 0 10px 24px rgba(128, 32, 48, 0.18);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-secondary {
        background: #f1f3f5;
        color: #495057;
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        color: #1a1a1a;
    }

    .info-stack {
        display: grid;
        gap: 1rem;
    }

    .info-card-header {
        background: #3c5e5e;
        color: #fff;
    }

    .info-card-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .info-list {
        display: grid;
        gap: 0.9rem;
    }

    .info-item {
        display: grid;
        gap: 0.2rem;
    }

    .info-item-label {
        font-size: 0.8rem;
        color: #777;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .info-item-value {
        font-size: 0.95rem;
        color: #1a1a1a;
        font-weight: 600;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        width: fit-content;
    }

    .status-pill.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .archive-card {
        border-color: rgba(153, 27, 27, 0.16);
    }

    .archive-card .card-header {
        background: #991b1b;
        color: #fff;
    }

    .archive-card .card-body {
        display: grid;
        gap: 1rem;
    }

    .btn-danger {
        background: #991b1b;
        color: #fff;
        box-shadow: 0 10px 24px rgba(153, 27, 27, 0.15);
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        color: #fff;
    }

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 1rem 1.25rem;
        border-radius: 14px;
        margin-bottom: 1.5rem;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.15rem;
    }

    .muted-note {
        color: #7c7c7c;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    @media (max-width: 992px) {
        .edit-user-layout {
            grid-template-columns: 1fr;
        }

        .edit-user-title {
            font-size: 2rem;
        }
    }

    @media (max-width: 640px) {
        .panel-card-body {
            padding: 1.25rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .edit-user-title {
            font-size: 1.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="edit-user-container">
    <div class="edit-user-header">
        <a href="{{ route('users.index') }}" class="back-link">
            <i class="fas fa-chevron-left"></i> Back to Personnel
        </a>
        <h1 class="edit-user-title mt-3">Edit User</h1>
        <p class="edit-user-subtitle">Update personal details, role access, and account status.</p>
    </div>

    @if($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-user-layout">
        <div class="panel-card">
            <div class="panel-card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="panel-section">
                        <h2 class="section-heading"><i class="fas fa-id-card"></i> Personal Details</h2>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                    id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror"
                                    id="role" name="role" required>
                                    @foreach($roles as $roleValue => $roleName)
                                        <option value="{{ $roleValue }}"
                                            {{ old('role', $user->role) === $roleValue ? 'selected' : '' }}>
                                            {{ $roleName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="action-row">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="info-stack">
            <div class="card shadow">
                <div class="card-header info-card-header">
                    <h5 class="info-card-title"><i class="fas fa-user-circle"></i> User Information</h5>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-item-label">Created</span>
                            <span class="info-item-value">{{ $user->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label">Last Updated</span>
                            <span class="info-item-value">{{ $user->updated_at->format('M d, Y g:i A') }}</span>
                        </div>
                        @if($user->deleted_at)
                            <div class="info-item">
                                <span class="info-item-label">Archived</span>
                                <span class="info-item-value text-danger">{{ $user->deleted_at->format('M d, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <div class="info-item">
                        <span class="info-item-label">Current Status</span>
                        <span class="status-pill {{ $user->deleted_at ? 'inactive' : 'active' }}">
                            {{ $user->deleted_at ? 'Inactive' : 'Active' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Change Password</h5>
                </div>
                <div class="card-body">
                    <p class="muted-note mb-0">
                        Users can change their own password from their profile. This section is reserved for an admin reset workflow.
                    </p>
                    <button type="button" class="btn btn-warning btn-sm" disabled style="opacity: 0.7; cursor: not-allowed;">
                        <i class="fas fa-key"></i> Reset User Password
                    </button>
                    <small class="text-muted">Coming soon - users can reset via email or security questions</small>
                </div>
            </div>

            @if(!$user->trashed() && (auth()->user()->isOwner() || auth()->user()->isAdmin()) && auth()->user()->id !== $user->id)
                <div class="card shadow archive-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-archive"></i> Archive User</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0"><small>Archive this user account</small></p>
                        <form id="archiveEditUserForm{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="showArchiveModal(document.getElementById('archiveEditUserForm{{ $user->id }}'), 'Archive User?', 'This user will not be able to log in until they are unarchived.')">
                                <i class="fas fa-archive"></i> Archive User
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
