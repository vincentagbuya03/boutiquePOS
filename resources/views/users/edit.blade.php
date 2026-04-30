@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact -->
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                   id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" required>{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
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

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Change Card -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Change Password</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <small>Note: Users can change their own password from their profile. This section is for admin reset if needed.</small>
                    </p>
                    <a href="#" class="btn btn-warning btn-sm" disabled>
                        <i class="fas fa-key"></i> Reset User Password
                    </a>
                    <small class="text-muted">Coming soon - users can reset via email or security questions</small>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> User Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7"><small>{{ $user->created_at->format('M d, Y g:i A') }}</small></dd>

                        <dt class="col-sm-5">Last Updated:</dt>
                        <dd class="col-sm-7"><small>{{ $user->updated_at->format('M d, Y g:i A') }}</small></dd>

                        @if($user->deleted_at)
                            <dt class="col-sm-5">Archived:</dt>
                            <dd class="col-sm-7"><small class="text-danger">{{ $user->deleted_at->format('M d, Y g:i A') }}</small></dd>
                        @endif
                    </dl>

                    <hr>

                    <h6>Current Status:</h6>
                    <p class="mb-0">
                        @if($user->deleted_at)
                            <span class="badge bg-danger">Inactive</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Archive Zone -->
            @if(!$user->trashed() && (auth()->user()->isOwner() || auth()->user()->isAdmin()) && auth()->user()->id !== $user->id)
                <div class="card shadow mt-3 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-archive"></i> Archive User</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><small>Archive this user account</small></p>
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
