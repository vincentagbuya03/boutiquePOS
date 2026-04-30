<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - V’S Fashion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Inter:wght@300;400;500;600;700&family=Dancing+Script:wght@600&display=swap" rel="stylesheet">    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>    <style>
        :root {
            --color-editorial: #802030; /* Deep Maroon */
            --color-bg-light: #f8f9fa;
            --color-sidebar-bg: #fff;
            --color-text-main: #1a1a1a;
            --color-text-muted: #666;
            --color-border: #f1f1f1;
            --sidebar-width: 250px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f7f8fa; 
            color: var(--color-text-main);
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            height: 100vh;
            z-index: 1000;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar-brand {
            padding: 2.5rem 2rem;
            flex-shrink: 0;
        }

        .brand-title {
            font-family: 'Bodoni Moda', serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--color-editorial);
            line-height: 1;
        }

        .brand-subtitle {
            font-size: 0.6rem;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-top: 0.6rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 1rem;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE and Edge */
        }
        .sidebar-nav::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.9rem 1.5rem;
            text-decoration: none;
            color: #777;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 14px;
            margin-bottom: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .nav-link i {
            width: 28px;
            font-size: 1.1rem;
            margin-right: 0.75rem;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .nav-link:hover {
            background: #fff;
            color: var(--color-editorial);
            border-color: #f1f1f1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .nav-link:hover i { opacity: 1; }

        .nav-link.active {
            background: #fdf2f4;
            color: var(--color-editorial);
            border-right: 4px solid var(--color-editorial);
            border-radius: 14px 0 0 14px;
            font-weight: 700;
        }
        
        .nav-link.active i { opacity: 1; }

        .nav-section-label {
            font-size: 0.6rem;
            font-weight: 800;
            color: #adb5bd;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 1.5rem 1.25rem 0.75rem;
            margin-top: 0.5rem;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1.5rem 1rem;
            border-top: 1px solid var(--color-border);
            background: white;
            flex-shrink: 0;
        }

        .footer-action {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.25rem;
            color: #555;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            transition: color 0.2s;
        }

        .footer-action:hover, .footer-action.active { color: var(--color-editorial); }
        .footer-action.active i { opacity: 1; }
        .footer-action i { margin-right: 0.75rem; font-size: 1rem; opacity: 0.6; }

        .user-mini-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            margin-top: 1rem;
        }

        .user-mini-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-mini-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-mini-name { font-size: 0.85rem; font-weight: 800; color: #1a1a1a; }
        .user-mini-role { font-size: 0.65rem; font-weight: 600; color: #999; text-transform: uppercase; }

        /* Main Context */
        .main-workspace {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #fff;
            margin-left: var(--sidebar-width);
        }

        .top-navbar {
            height: 80px;
            background: white;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            flex-shrink: 0;
            z-index: 100;
        }

        .navbar-left { display: flex; align-items: center; gap: 2rem; }
        
        .page-brand-context { 
            font-family: 'Bodoni Moda', serif;
            font-size: 1.25rem; 
            font-weight: 800; 
            color: var(--color-editorial);
            letter-spacing: -0.01em;
        }

        .navbar-search {
            position: relative;
            background: #f8f9fa;
            border-radius: 100px;
            padding: 0.6rem 1.75rem;
            display: flex;
            align-items: center;
            width: 400px;
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-search:focus-within {
            background: white;
            border-color: #f1f1f1;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            width: 450px;
        }

        .search-icon-nav { font-size: 0.9rem; color: #adb5bd; margin-right: 1rem; }
        .search-input-nav { 
            border: none; 
            background: transparent; 
            font-size: 0.8rem; 
            font-weight: 600; 
            width: 100%; 
            outline: none; 
            color: #1a1a1a;
        }
        .search-input-nav::placeholder { color: #adb5bd; font-weight: 500; }

        .navbar-right { display: flex; align-items: center; gap: 3rem; }

        .nav-bell { 
            font-size: 1.35rem; 
            color: #adb5bd; 
            position: relative; 
            cursor: pointer; 
            transition: color 0.2s;
        }
        .nav-bell:hover { color: var(--color-editorial); }
        .nav-bell .dot { 
            position: absolute; 
            top: 2px; 
            right: 0px; 
            width: 9px; 
            height: 9px; 
            background: var(--color-editorial); 
            border-radius: 50%; 
            border: 2px solid white; 
        }

        .nav-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fdf2f4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-editorial);
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-avatar-circle:hover { transform: scale(1.05); }

        .nav-logout-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-logout-btn:hover {
            background: #fdf2f4;
            color: var(--color-editorial);
            transform: scale(1.05);
        }

        /* Content Scroll Area */
        .workspace-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 3rem;
        }

        .view-content-title { font-size: 2.75rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.5rem; }
        .view-content-subtitle { color: #999; font-size: 1rem; font-weight: 500; margin-bottom: 3rem; }

        /* General UI Polish */
        .pos-card { background: white; border-radius: 20px; border: 1px solid var(--color-border); padding: 2rem; margin-bottom: 2rem; }

        @media (max-width: 768px) {
            body {
                height: auto;
            }

            .app-container {
                flex-direction: column;
                min-height: 100vh;
            }

            .sidebar {
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--color-border);
            }

            .sidebar-brand {
                padding: 1.25rem 1rem 0.75rem;
            }

            .brand-title {
                font-size: 1.3rem;
            }

            .sidebar-nav {
                display: flex;
                gap: 0.5rem;
                padding: 0.75rem 1rem 1rem;
                overflow-x: auto;
                overflow-y: hidden;
                scrollbar-width: none;
            }

            .sidebar-nav::-webkit-scrollbar {
                display: none;
            }

            .nav-section-label {
                display: none;
            }

            .nav-link {
                margin-bottom: 0;
                white-space: nowrap;
                padding: 0.75rem 1rem;
                font-size: 0.75rem;
            }

            .sidebar-footer {
                padding: 1rem;
            }

            .footer-action,
            .user-mini-profile {
                display: none;
            }

            .main-workspace {
                min-width: 0;
            }

            .top-navbar {
                height: auto;
                padding: 1rem;
                align-items: stretch;
                gap: 1rem;
            }

            .navbar-left,
            .navbar-right {
                width: 100%;
            }

            .navbar-left {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .navbar-right {
                justify-content: flex-end;
                gap: 1rem;
            }

            .page-brand-context {
                font-size: 1rem;
            }

            .navbar-search,
            .navbar-search:focus-within {
                width: 100%;
            }

            .workspace-scroll {
                padding: 1rem;
            }

            .view-content-title {
                font-size: 1.9rem;
            }
        }
        @media print {
            .sidebar, .top-navbar, .nav-logout-btn, .nav-avatar-circle, .btn-print, .action-group, .back-link, .filter-ribbon, .pos-action-tray, .no-print { 
                display: none !important; 
            }
            .main-workspace { 
                margin-left: 0 !important; 
                width: 100% !important;
                display: block !important;
            }
            .workspace-scroll { 
                padding: 0 !important; 
                overflow: visible !important;
                display: block !important;
            }
            .app-container {
                display: block !important;
            }
            body { 
                background: white !important; 
                overflow: visible !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Helper classes for reports */
            .print-signature-block {
                margin-top: 4rem !important;
                display: flex !important;
                justify-content: flex-end !important;
                page-break-inside: avoid !important;
            }
            .print-table-row {
                page-break-inside: avoid !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-title">V’S Fashion</div>
                <div class="brand-subtitle">Boutique Management</div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                {{-- Product & Supply Chain --}}
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isStaff())
                <div class="nav-section-label">Inventory & Supply</div>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                    <i class="fas fa-swatchbook"></i> Product Catalog
                </a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}">
                    <i class="fas fa-archive"></i> Stock Levels
                </a>
                <a href="{{ route('batches.index') }}" class="nav-link {{ request()->is('batches*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Product Batches
                </a>
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                    <i class="fas fa-truck-loading"></i> Suppliers
                </a>
                <a href="{{ route('archives.index') }}" class="nav-link {{ request()->is('archives*') ? 'active' : '' }}">
                    <i class="fas fa-box-archive"></i> Archive
                </a>
                @endif

                {{-- Operations --}}
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isCashier())
                <div class="nav-section-label">Operations</div>
                <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i> POS Terminal
                </a>
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Sales Logs
                </a>
                <a href="{{ route('returns.index') }}" class="nav-link {{ request()->is('returns*') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt"></i> Returns & Refunds
                </a>
                @endif
                
                {{-- Administrative --}}
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                <div class="nav-section-label">Administration</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Personnel
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Reports & Insights
                </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('settings.index') }}" class="footer-action {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
                
                <div class="user-mini-profile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=802030&color=fff&font-size=0.4" class="user-mini-avatar" alt="User">
                    <div class="user-mini-info">
                        <span class="user-mini-name">{{ explode(' ', auth()->user()->name)[0] }} {{ explode(' ', auth()->user()->name)[1] ?? '' }}</span>
                        <span class="user-mini-role">{{ auth()->user()->role ?? 'Manager' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Viewport -->
        <main class="main-workspace">
            <header class="top-navbar">
                <div class="navbar-left">
                    <div class="page-brand-context">V’S Fashion - San Carlos</div>
                    <div class="navbar-search">
                        <i class="fas fa-search search-icon-nav"></i>
                        <input type="text" class="search-input-nav" placeholder="Search V'S Fashion...">
                    </div>
                </div>

                <div class="navbar-right">
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="button" onclick="showLogoutConfirm()" class="nav-logout-btn" title="Logout Account">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>
                    <div class="nav-avatar-circle" onclick="window.location.href='{{ route('settings.index') }}'">
                        <i class="far fa-user"></i>
                    </div>
                </div>
            </header>

            <div class="workspace-scroll">
                @if(session('success') && !request()->routeIs('sales.show'))
                    <div style="background: #f0fdf4; border: 1px solid #bbfcce; color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 600;">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // System focused on San Carlos Branch
        });
    </script>
    
    <!-- Modals -->
    @include('components.success-modal')
    @include('components.delete-modal')
    @include('components.logout-modal')
    
    <!-- Flash Message Data -->
    @if(session('success'))
        <div data-flash-message="{{ session('success') }}" data-title="Success!" style="display: none;"></div>
    @endif
    
    @endauth

    @yield('scripts')
</body>
</html>
