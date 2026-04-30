@extends('layouts.app')

@section('title', 'Create New User')

@section('styles')
<style>
    .create-user-container {
        max-width: 1200px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-section-card {
        background: white;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        padding: 2.5rem;
        height: 100%;
        transition: transform 0.3s ease;
    }

    .section-title {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--color-editorial);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        font-size: 1.1rem;
        opacity: 0.8;
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #eee;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
        background-color: #fcfcfc;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--color-editorial);
        box-shadow: 0 0 0 4px rgba(128, 32, 48, 0.05);
        background-color: white;
    }

    .input-group-text {
        background: transparent;
        border: 1px solid #eee;
        border-radius: 12px;
        color: #999;
    }

    .role-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .role-card {
        border: 2px solid #f1f1f1;
        border-radius: 16px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .role-card:hover {
        border-color: #e0e0e0;
        background: #fafafa;
    }

    .role-card.active {
        border-color: var(--color-editorial);
        background: #fdf2f4;
    }

    .role-card input {
        position: absolute;
        opacity: 0;
    }

    .role-name {
        display: block;
        font-weight: 700;
        font-size: 0.9rem;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .role-desc {
        display: block;
        font-size: 0.7rem;
        color: #777;
        line-height: 1.3;
    }

    .role-card.active .role-name { color: var(--color-editorial); }

    .permission-matrix {
        background: #fdf2f4;
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .matrix-table {
        width: 100%;
        font-size: 0.75rem;
    }

    .matrix-table th {
        font-weight: 800;
        color: var(--color-editorial);
        padding-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .matrix-table td {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(128, 32, 48, 0.05);
    }

    .btn-submit {
        background: var(--color-editorial);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 1rem 2rem;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.02em;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 20px rgba(128, 32, 48, 0.15);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(128, 32, 48, 0.25);
        color: white;
        opacity: 0.9;
    }

    .btn-cancel {
        background: #f8f9fa;
        color: #777;
        border: 1px solid #eee;
        border-radius: 14px;
        padding: 1rem 2rem;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: white;
        color: #1a1a1a;
        border-color: #ddd;
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        z-index: 10;
        padding: 0.5rem;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--color-editorial);
    }

    .error-feedback {
        font-size: 0.7rem;
        font-weight: 600;
        color: #dc3545;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="create-user-container">
    <header class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-2">
            <a href="{{ route('users.index') }}" class="btn-cancel py-2 px-3" style="font-size: 0.8rem;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="view-content-title mb-0">Create Personnel</h1>
        </div>
        <p class="view-content-subtitle">Register a new team member and define their access privileges.</p>
    </header>

    <form action="{{ route('users.store') }}" method="POST" id="user-form">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Basic Information -->
            <div class="col-lg-7">
                <div class="form-section-card">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle"></i> Personal Profile
                    </h2>
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                        @error('name')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" placeholder="email@vfashion.com" required>
                            @error('email')
                                <div class="error-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control @error('contact') is-invalid @enderror" 
                                   id="contact" name="contact" value="{{ old('contact') }}" placeholder="09xxxxxxxxx" required>
                            @error('contact')
                                <div class="error-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Residential Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" placeholder="Complete address details" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h2 class="section-title mt-5">
                        <i class="fas fa-shield-alt"></i> Security
                    </h2>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Minimum 8 characters" required>
                                <button type="button" class="password-toggle" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="error-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required>
                                <button type="button" class="password-toggle" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Role & Permissions -->
            <div class="col-lg-5">
                <div class="form-section-card">
                    <h2 class="section-title">
                        <i class="fas fa-user-tag"></i> Access Role
                    </h2>
                    
                    <div class="role-grid">
                        @php
                            $roleDescriptions = [
                                'owner' => 'Full system authority and branch management.',
                                'admin' => 'Manage users, inventory, and sales operations.',
                                'staff' => 'Inventory management and product handling.',
                                'cashier' => 'POS operations, sales, and returns processing.'
                            ];
                        @endphp

                        @foreach($roles as $roleValue => $roleName)
                            <label class="role-card {{ old('role') === $roleValue ? 'active' : '' }}">
                                <input type="radio" name="role" value="{{ $roleValue }}" 
                                       {{ old('role') === $roleValue ? 'checked' : '' }} required>
                                <span class="role-name">{{ $roleName }}</span>
                                <span class="role-desc">{{ $roleDescriptions[$roleValue] ?? 'System access role' }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('role')
                        <div class="error-feedback mb-3">{{ $message }}</div>
                    @enderror

                    <div class="permission-matrix">
                        <h6 class="form-label mb-3" style="color: var(--color-editorial)">Permission Overview</h6>
                        <table class="matrix-table">
                            <thead>
                                <tr>
                                    <th>Feature</th>
                                    <th class="text-center">Admin</th>
                                    <th class="text-center">Staff</th>
                                    <th class="text-center">Cashier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Products</td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-eye text-info"></i></td>
                                </tr>
                                <tr>
                                    <td>Inventory</td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-eye text-info"></i></td>
                                </tr>
                                <tr>
                                    <td>Sales / POS</td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-minus-circle text-muted opacity-50"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Personnel</td>
                                    <td class="text-center"><i class="fas fa-check-circle text-success"></i></td>
                                    <td class="text-center"><i class="fas fa-minus-circle text-muted opacity-50"></i></td>
                                    <td class="text-center"><i class="fas fa-minus-circle text-muted opacity-50"></i></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex gap-3" style="font-size: 0.65rem; font-weight: 600; color: #777;">
                            <span><i class="fas fa-check-circle text-success"></i> Manage</span>
                            <span><i class="fas fa-eye text-info"></i> View Only</span>
                            <span><i class="fas fa-minus-circle text-muted"></i> No Access</span>
                        </div>
                    </div>

                    <div class="mt-5 d-flex flex-column gap-3">
                        <button type="submit" class="btn-submit justify-content-center">
                            <i class="fas fa-user-plus"></i> Initialize Personnel Account
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-cancel text-center">
                            Cancel Registration
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Role Selection Logic
        const roleCards = document.querySelectorAll('.role-card');
        roleCards.forEach(card => {
            card.addEventListener('click', function() {
                roleCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Password Toggle Logic
        const toggles = document.querySelectorAll('.password-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endpush
@endsection

