@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <h1 class="h3 mb-0 text-gray-800">{{ $user->name }}</h1>
                <div>
                    @if(auth()->user()->canManageUsers())
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Details -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Full Name</h6>
                            <p class="h5">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Email Address</h6>
                            <p class="h5">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Contact Number</h6>
                            <p class="h5">{{ $user->contact }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Branch</h6>
                            <p class="h5">{{ $user->branch }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Role</h6>
                            <p>
                                <span class="badge bg-{{ $user->getRoleBadgeColor() }} fs-6">
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status</h6>
                            <p>
                                @if($user->deleted_at)
                                    <span class="badge bg-danger fs-6">Inactive</span>
                                @else
                                    <span class="badge bg-success fs-6">Active</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Address</h5>
                </div>
                <div class="card-body">
                    <p>{{ $user->address }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Account Info -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Account Timeline</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Account Created:</dt>
                        <dd class="col-sm-6"><small>{{ $user->created_at->format('M d, Y') }}</small></dd>

                        <dt class="col-sm-6">Last Updated:</dt>
                        <dd class="col-sm-6"><small>{{ $user->updated_at->format('M d, Y') }}</small></dd>

                        @if($user->deleted_at)
                            <dt class="col-sm-6">Deactivated:</dt>
                            <dd class="col-sm-6"><small class="text-danger">{{ $user->deleted_at->format('M d, Y') }}</small></dd>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Permissions -->
            <div class="card shadow mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Permissions</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Features Access:</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Products Management</span>
                            @if($user->canManageProducts())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Stock Management</span>
                            @if($user->canManageInventory())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>POS / Sales</span>
                            @if($user->canAccessPOS())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Returns & Refunds</span>
                            @if($user->canManageReturns())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Orders Management</span>
                            @if($user->canManageOrders())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>User Management</span>
                            @if($user->canManageUsers())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>View Reports</span>
                            @if($user->canViewReports())
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-times text-muted"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
