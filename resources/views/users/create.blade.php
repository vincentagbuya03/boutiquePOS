@extends('layouts.app')

@section('title', 'Create Personnel')

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.4);
        --accent-soft: rgba(128, 32, 48, 0.05);
    }

    .create-user-container {
        max-width: 1200px;
        margin: 0 auto;
        animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-section-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 28px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 15px 35px rgba(0,0,0,0.03);
        padding: 3rem;
        height: 100%;
        transition: all 0.4s ease;
    }

    .glass-section-card:hover {
        box-shadow: 0 20px 45px rgba(128, 32, 48, 0.06);
        border-color: rgba(128, 32, 48, 0.1);
    }

    .section-header {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.75rem;
        font-weight: 900;
        color: var(--color-editorial);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .section-subtitle {
        font-size: 0.75rem;
        font-weight: 600;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .input-field-group {
        position: relative;
        margin-bottom: 1.75rem;
    }

    .field-icon {
        position: absolute;
        left: 1.25rem;
        top: 2.5rem;
        color: #adb5bd;
        font-size: 0.9rem;
        transition: color 0.3s;
    }

    .form-label {
        font-size: 0.7rem;
        font-weight: 800;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.6rem;
        padding-left: 0.25rem;
    }

    .premium-input {
        width: 100%;
        border-radius: 16px;
        border: 1px solid #eee;
        padding: 0.85rem 1rem 0.85rem 2.8rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #f9f9f9;
        color: #1a1a1a;
    }

    .premium-input:focus {
        border-color: var(--color-editorial);
        background-color: white;
        box-shadow: 0 10px 25px rgba(128, 32, 48, 0.08);
        outline: none;
    }

    .premium-input:focus + .field-icon {
        color: var(--color-editorial);
    }

    .role-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .role-card {
        background: white;
        border: 2px solid #f1f1f1;
        border-radius: 20px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .role-card:hover {
        transform: translateY(-5px);
        border-color: #e0e0e0;
        box-shadow: 0 10px 20px rgba(0,0,0,0.02);
    }

    .role-card.active {
        border-color: var(--color-editorial);
        background: #fdf2f4;
        box-shadow: 0 15px 30px rgba(128, 32, 48, 0.1);
    }

    .role-card input {
        position: absolute;
        opacity: 0;
    }

    .role-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: #adb5bd;
        transition: all 0.3s;
    }

    .role-card.active .role-badge {
        background: var(--color-editorial);
        color: white;
    }

    .role-name {
        font-weight: 800;
        font-size: 0.95rem;
        color: #1a1a1a;
    }

    .role-desc {
        font-size: 0.7rem;
        color: #777;
        line-height: 1.4;
    }

    .matrix-container {
        background: rgba(128, 32, 48, 0.03);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(128, 32, 48, 0.05);
    }

    .matrix-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.75rem;
    }

    .matrix-table th {
        font-size: 0.65rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 0 1rem;
    }

    .matrix-table td {
        padding: 1rem;
        background: white;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .matrix-table td:first-child { border-radius: 12px 0 0 12px; }
    .matrix-table td:last-child { border-radius: 0 12px 12px 0; text-align: center; }

    .btn-action-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 3rem;
    }

    .btn-main {
        background: var(--color-editorial);
        color: white;
        border: none;
        border-radius: 18px;
        padding: 1.1rem;
        font-weight: 800;
        font-size: 0.95rem;
        letter-spacing: 0.02em;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 0 10px 25px rgba(128, 32, 48, 0.2);
    }

    .btn-main:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 35px rgba(128, 32, 48, 0.3);
        color: white;
    }

    .btn-outline {
        background: transparent;
        color: #adb5bd;
        border: 2px solid #eee;
        border-radius: 18px;
        padding: 1.1rem;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
    }

    .btn-outline:hover {
        border-color: #ddd;
        color: #1a1a1a;
        background: white;
    }

    .password-toggle {
        position: absolute;
        right: 1.25rem;
        top: 2.5rem;
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.2s;
    }

    .password-toggle:hover { color: var(--color-editorial); }

    .error-tag {
        font-size: 0.65rem;
        font-weight: 700;
        color: #dc3545;
        margin-top: 0.4rem;
        display: block;
    }

    @media (max-width: 768px) {
        .glass-section-card { padding: 2rem 1.5rem; }
        .role-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="create-user-container">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="view-content-title mb-1">Personnel Registration</h1>
            <p class="view-content-subtitle mb-0">Expand the V’S Fashion collective with elite curators.</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-outline py-2 px-4" style="font-size: 0.75rem; border-radius: 12px;">
            <i class="fas fa-arrow-left me-2"></i> Return to Directory
        </a>
    </div>

    <form action="{{ route('users.store') }}" method="POST" id="user-form">
        @csrf
        <div class="row g-5">
            <!-- Left: Identity -->
            <div class="col-lg-7">
                <div class="glass-section-card">
                    <div class="section-header">
                        <span class="section-subtitle">Personal Details</span>
                        <h2 class="section-title"><i class="far fa-id-card"></i> Identity Profile</h2>
                    </div>
                    
                    <div class="input-field-group">
                        <label for="name" class="form-label">Legal Name</label>
                        <input type="text" class="premium-input @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" placeholder="Full name as per ID" required>
                        <i class="far fa-user field-icon"></i>
                        @error('name') <span class="error-tag">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-field-group">
                                <label for="email" class="form-label">Corporate Email</label>
                                <input type="email" class="premium-input @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" placeholder="curator@vfashion.com" required>
                                <i class="far fa-envelope field-icon"></i>
                                @error('email') <span class="error-tag">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-field-group">
                                <label for="contact" class="form-label">Contact Link</label>
                                <input type="text" class="premium-input @error('contact') is-invalid @enderror" 
                                       id="contact" name="contact" value="{{ old('contact') }}" placeholder="+63 9xx xxx xxxx" required>
                                <i class="fas fa-mobile-alt field-icon"></i>
                                @error('contact') <span class="error-tag">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="input-field-group">
                        <label for="address" class="form-label">Permanent Residence</label>
                        <textarea class="premium-input @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" style="padding-left: 1.25rem;" placeholder="Unit, Street, City, Province" required>{{ old('address') }}</textarea>
                        @error('address') <span class="error-tag">{{ $message }}</span> @enderror
                    </div>

                    <div class="section-header mt-5">
                        <span class="section-subtitle">System Access</span>
                        <h2 class="section-title"><i class="fas fa-fingerprint"></i> Authentication</h2>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-field-group">
                                <label for="password" class="form-label">Access Key</label>
                                <input type="password" class="premium-input @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="••••••••" required>
                                <i class="fas fa-key field-icon"></i>
                                <button type="button" class="password-toggle" data-target="password">
                                    <i class="far fa-eye"></i>
                                </button>
                                @error('password') <span class="error-tag">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-field-group">
                                <label for="password_confirmation" class="form-label">Confirm Key</label>
                                <input type="password" class="premium-input" 
                                       id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                <i class="fas fa-shield-alt field-icon"></i>
                                <button type="button" class="password-toggle" data-target="password_confirmation">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Authority -->
            <div class="col-lg-5">
                <div class="glass-section-card">
                    <div class="section-header">
                        <span class="section-subtitle">Privileges</span>
                        <h2 class="section-title"><i class="fas fa-user-shield"></i> Authority Level</h2>
                    </div>
                    
                    <div class="role-grid">
                        @php
                            $roleData = [
                                'owner' => ['icon' => 'fa-crown', 'desc' => 'Ultimate system control & strategy.'],
                                'admin' => ['icon' => 'fa-user-cog', 'desc' => 'Full administrative management.'],
                                'staff' => ['icon' => 'fa-box', 'desc' => 'Inventory & catalog oversight.'],
                                'cashier' => ['icon' => 'fa-cash-register', 'desc' => 'Point of Sale operations.']
                            ];
                        @endphp

                        @foreach($roles as $roleValue => $roleName)
                            <label class="role-card {{ old('role') === $roleValue ? 'active' : '' }}">
                                <input type="radio" name="role" value="{{ $roleValue }}" 
                                       {{ old('role') === $roleValue ? 'checked' : '' }} required>
                                <div class="role-badge">
                                    <i class="fas {{ $roleData[$roleValue]['icon'] ?? 'fa-user' }}"></i>
                                </div>
                                <span class="role-name">{{ $roleName }}</span>
                                <span class="role-desc">{{ $roleData[$roleValue]['desc'] ?? 'System access level' }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('role') <span class="error-tag mb-4">{{ $message }}</span> @enderror

                    <div class="matrix-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="form-label m-0" style="color: var(--color-editorial)">Capability Matrix</span>
                            <i class="fas fa-info-circle text-muted" style="font-size: 0.8rem;"></i>
                        </div>
                        <table class="matrix-table">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Admin</th>
                                    <th>Staff</th>
                                    <th>POS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Products</td>
                                    <td><i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i></td>
                                    <td><i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i></td>
                                    <td><i class="far fa-circle text-info" style="font-size: 0.5rem;"></i></td>
                                </tr>
                                <tr>
                                    <td>Stock</td>
                                    <td><i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i></td>
                                    <td><i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i></td>
                                    <td><i class="far fa-circle text-info" style="font-size: 0.5rem;"></i></td>
                                </tr>
                                <tr>
                                    <td>Sales</td>
                                    <td><i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i></td>
                                    <td><i class="fas fa-times text-muted opacity-25" style="font-size: 0.5rem;"></i></td>
                                    <td><i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex gap-3 justify-content-center" style="font-size: 0.6rem; font-weight: 700; color: #adb5bd;">
                            <span>● FULL</span>
                            <span>○ VIEW</span>
                            <span>✕ NONE</span>
                        </div>
                    </div>

                    <div class="btn-action-group">
                        <button type="submit" class="btn-main">
                            <i class="fas fa-user-plus"></i> Finalize Registration
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-outline">
                            Cancel curated access
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Role Selection
        const roleCards = document.querySelectorAll('.role-card');
        roleCards.forEach(card => {
            card.addEventListener('click', function() {
                roleCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Password Toggle
        const toggles = document.querySelectorAll('.password-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('far', 'fas');
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fas', 'far');
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    });
</script>
@endsection


