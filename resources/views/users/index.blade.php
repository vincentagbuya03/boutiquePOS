@extends('layouts.app')

@section('title', 'User Management')

@section('styles')
<style>
    .index-header { margin-bottom: 3.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .index-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; letter-spacing: -0.02em; }
    .index-subtitle { color: #999; font-size: 1.05rem; font-weight: 500; margin-top: 0.25rem; }

    .action-group { display: flex; gap: 1rem; }
    .btn-arch-primary { background: var(--color-editorial); color: white; padding: 0.9rem 2rem; border-radius: 100px; text-decoration: none; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; transition: transform 0.2s; }
    
    .btn-arch-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(128, 32, 48, 0.15); }

    /* Archive Table Shell */
    .arch-table-card {
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border);
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.02);
    }

    .arch-table { width: 100%; border-collapse: collapse; }
    .arch-table th { text-align: left; padding: 1.5rem 1rem; font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 1px solid #f8f9fa; }
    .arch-table td { padding: 1.5rem 1rem; border-bottom: 1px solid #f8f9fa; font-size: 0.85rem; font-weight: 600; vertical-align: middle; }

    .user-profile-flex { display: flex; align-items: center; gap: 1rem; }
    .user-initials { width: 36px; height: 36px; border-radius: 50%; background: #fdf2f4; color: var(--color-editorial); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; }

    .badge-arch { padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-mauve { background: #f8f3f4; color: #c299a0; }
    .badge-teal { background: #f0f6f6; color: #3c5e5e; }
    .badge-maroon { background: #fdf2f2; color: #802030; }

    .action-btn-mini { color: #adb5bd; transition: color 0.2s; margin-left: 0.75rem; cursor: pointer; background: none; border: none; }
    .action-btn-mini:hover { color: var(--color-editorial); }
</style>
@endsection

@section('content')
<div class="index-header">
    <div>
        <h1 class="index-title">V'S Fashion: Personnel</h1>
        <p class="index-subtitle">Manage the curators and staff of V’S Fashion Boutique.</p>
    </div>
    <div class="action-group">
        @if(auth()->user()->canManageUsers())
            <a href="{{ route('users.create') }}" class="btn-arch-primary">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Add Personnel
            </a>
        @endif
    </div>
</div>

<div class="arch-table-card">
    <table class="arch-table">
        <thead>
            <tr>
                <th>Curator</th>
                <th>Identity</th>
                <th>Role</th>
                <th>Contact</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="user-profile-flex">
                        @php $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join(''); @endphp
                        <div class="user-initials">{{ $initials }}</div>
                        <div style="font-weight: 800; color: #1a1a1a;">{{ $user->name }}</div>
                    </div>
                </td>
                <td style="color: #999;">{{ $user->email }}</td>
                <td><span class="badge-arch badge-mauve">{{ ucfirst($user->role) ?? 'Manager' }}</span></td>
                <td style="font-size: 0.75rem;">{{ $user->contact_number }}</td>
                <td>
                    <span class="badge-arch {{ $user->deleted_at ? 'badge-maroon' : 'badge-teal' }}">
                        {{ $user->deleted_at ? 'Inactive' : 'Active' }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; justify-content: flex-end;">
                        <a href="{{ route('users.show', $user) }}" class="action-btn-mini"><i class="fas fa-eye"></i></a>
                        
                        @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                            <a href="{{ route('users.edit', $user) }}" class="action-btn-mini"><i class="fas fa-pen-nib"></i></a>
                        @endif

                        @if((auth()->user()->isOwner() || auth()->user()->isAdmin()) && auth()->user()->id !== $user->id)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn-mini"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 5rem 0; color: #adb5bd;">
                    <i class="fas fa-user-shield" style="font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 1.5rem;"></i>
                    No curators found in this archive.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
