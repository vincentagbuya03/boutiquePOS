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
            min-width: 0;
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
        .navbar-search-results {
            position: absolute;
            top: calc(100% + 0.7rem);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #f0f1f4;
            border-radius: 22px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            padding: 0.65rem;
            display: none;
            z-index: 150;
        }
        .navbar-search-results.is-visible {
            display: block;
        }
        .navbar-search-empty {
            padding: 0.95rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #6b7280;
        }
        .navbar-search-result {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            width: 100%;
            border: none;
            background: transparent;
            border-radius: 16px;
            padding: 0.95rem 1rem;
            text-align: left;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .navbar-search-result:hover,
        .navbar-search-result.is-active {
            background: #f8f5f6;
            transform: translateY(-1px);
        }
        .navbar-search-result-title {
            display: block;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1a1a1a;
        }
        .navbar-search-result-meta {
            display: block;
            margin-top: 0.2rem;
            font-size: 0.74rem;
            font-weight: 600;
            color: #8b95a7;
            letter-spacing: 0.01em;
        }
        .navbar-search-result-shortcut {
            font-size: 0.72rem;
            font-weight: 700;
            color: #9ca3af;
            white-space: nowrap;
        }

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

        .container-fluid {
            width: 100%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .row > [class*="col-"] {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            width: 100%;
        }

        .col-12 { flex: 0 0 100%; max-width: 100%; }

        @media (min-width: 768px) {
            .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
            .col-md-6 { flex: 0 0 50%; max-width: 50%; }
            .col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
        }

        @media (min-width: 992px) {
            .col-lg-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
            .col-lg-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
            .col-lg-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
            .col-lg-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
        }

        .g-4 { row-gap: 1.5rem; }
        .g-5 { row-gap: 3rem; }
        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .align-items-end { align-items: flex-end; }
        .justify-content-between { justify-content: space-between; }
        .justify-content-end { justify-content: flex-end; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .text-muted { color: #999; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .px-4 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .pt-3 { padding-top: 1rem; }
        .border-top { border-top: 1px solid #f1f1f1; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }
        .me-2 { margin-right: 0.5rem; }
        .small, small { font-size: 0.8em; }

        .card {
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f1f1;
            background: #fafafa;
        }

        .card-body {
            padding: 1.25rem;
        }

        .shadow { box-shadow: 0 12px 30px rgba(0,0,0,0.04); }
        .bg-info { background: #3c5e5e !important; }
        .bg-success { background: #166534 !important; }
        .bg-danger { background: #991b1b !important; }
        .text-white { color: white !important; }
        .text-danger { color: #991b1b !important; }
        .text-success { color: #166534 !important; }
        .text-info { color: #3c5e5e !important; }
        .opacity-25 { opacity: 0.25; }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f1f1;
            text-align: left;
            vertical-align: middle;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-weight: 800;
            font-size: 0.82rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary { background: var(--color-editorial); color: white; }
        .btn-secondary { background: #f1f3f5; color: #495057; }
        .btn-warning { background: #fffbeb; color: #92400e; }
        .btn-danger { background: #991b1b; color: white; }
        .btn-sm { padding: 0.45rem 0.75rem; font-size: 0.72rem; }
        .w-100 { width: 100%; }

        .list-group-item {
            padding: 0.8rem 0;
            border-bottom: 1px solid #f1f1f1;
        }

        img, video, canvas, svg {
            max-width: 100%;
        }

        input, select, textarea, button {
            max-width: 100%;
        }

        .arch-table-card,
        .arch-table-shell,
        .archive-table-shell,
        .returns-table-wrapper,
        .orders-section-card {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .arch-table,
        .archive-table,
        .returns-table {
            min-width: 720px;
        }

        .action-group {
            flex-wrap: wrap;
        }

        .btn-arch-primary,
        .btn-arch-secondary,
        .btn-arch-danger,
        .btn-arch-view,
        .seasonal-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            body {
                height: auto;
                background: #f4f5f7;
            }

            .app-container {
                flex-direction: column;
                min-height: 100vh;
                background: #f4f5f7;
            }

            .sidebar {
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #eceef1;
                position: static;
                background: #fff;
                box-shadow: 0 1px 0 rgba(16, 24, 40, 0.04);
            }

            .sidebar-brand {
                padding: 0.9rem 1rem 0.35rem;
            }

            .brand-title {
                font-size: 1.25rem;
                letter-spacing: -0.01em;
            }

            .brand-subtitle {
                font-size: 0.52rem;
                letter-spacing: 0.16em;
                margin-top: 0.45rem;
            }

            .sidebar-nav {
                display: flex;
                gap: 0.45rem;
                padding: 0.55rem 1rem 0.8rem;
                overflow-x: auto;
                overflow-y: hidden;
                scrollbar-width: none;
                scroll-snap-type: x proximity;
            }

            .sidebar-nav::-webkit-scrollbar {
                display: none;
            }

            .nav-section-label {
                display: none;
            }

            .nav-link {
                margin-bottom: 0;
                flex: 0 0 74px;
                height: 58px;
                white-space: normal;
                padding: 0.55rem 0.45rem;
                font-size: 0.62rem;
                line-height: 1.1;
                border-radius: 14px;
                flex-direction: column;
                justify-content: center;
                text-align: center;
                gap: 0.35rem;
                color: #747782;
                scroll-snap-align: start;
            }

            .nav-link i {
                width: auto;
                margin-right: 0;
                font-size: 1rem;
                opacity: 0.55;
            }

            .nav-link .nav-text {
                display: block;
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .nav-link.active {
                border-right: 1px solid rgba(128, 32, 48, 0.1);
                border-bottom: 3px solid var(--color-editorial);
                border-radius: 14px;
                background: #fdf2f4;
                box-shadow: none;
            }

            .sidebar-footer {
                display: none;
            }

            .footer-action,
            .user-mini-profile {
                display: none;
            }

            .main-workspace {
                margin-left: 0;
                min-width: 0;
                width: 100%;
                overflow: visible;
                background: #f4f5f7;
            }

            .top-navbar {
                height: auto;
                padding: 0.8rem 1rem;
                align-items: center;
                gap: 0.75rem;
                flex-direction: row;
                background: #fff;
                border-bottom: 1px solid #eceef1;
            }

            .navbar-left,
            .navbar-right {
                width: auto;
            }

            .navbar-left {
                flex: 1;
                min-width: 0;
                gap: 0;
            }

            .navbar-right {
                flex: 0 0 auto;
                justify-content: flex-end;
                gap: 0.5rem;
            }

            .page-brand-context {
                display: none;
            }

            .navbar-search,
            .navbar-search:focus-within {
                width: 100%;
                height: 42px;
                padding: 0.55rem 0.9rem;
                border-radius: 12px;
                background: #f5f6f8;
                box-shadow: none;
            }

            .search-icon-nav {
                margin-right: 0.65rem;
                font-size: 0.82rem;
            }

            .search-input-nav {
                font-size: 0.78rem;
            }
            .navbar-search-results {
                top: calc(100% + 0.5rem);
                border-radius: 16px;
                padding: 0.45rem;
            }
            .navbar-search-result {
                padding: 0.85rem 0.9rem;
            }

            .nav-logout-btn,
            .nav-avatar-circle {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                flex-shrink: 0;
            }

            .workspace-scroll {
                padding: 1.25rem 1rem 5rem;
                overflow: visible;
            }

            .view-content-title {
                font-size: 1.9rem;
                line-height: 1.08;
            }

            .view-content-subtitle {
                margin-bottom: 1.5rem;
                line-height: 1.45;
            }

            .pos-card,
            .form-card,
            .arch-form-card,
            .arch-table-card,
            .arch-table-shell,
            .archive-table-shell,
            .returns-table-wrapper,
            .orders-section-card,
            .chart-perf-card,
            .arch-content-card,
            .focus-card,
            .detail-card,
            .glass-section-card,
            .card,
            .piece-identity-card,
            .stock-controller-card,
            .admin-section,
            .action-card {
                border-radius: 18px !important;
                padding: 1rem !important;
                box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04) !important;
            }

            .index-header,
            .report-header,
            .profit-header,
            .archive-header,
            .adjust-header,
            .claim-header,
            .form-header,
            .view-header,
            .reports-header,
            .section-header-flex,
            .chart-perf-header,
            .card-title-flex,
            .actions-footer,
            .report-footer {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 1rem !important;
                margin-bottom: 1.5rem !important;
            }

            .index-title,
            .overview-title,
            .view-content-title,
            .form-title,
            .product-title,
            .reports-title,
            .adjust-title {
                font-size: 1.7rem !important;
                line-height: 1.15 !important;
                letter-spacing: 0 !important;
            }

            .index-subtitle,
            .overview-subtitle,
            .form-subtitle,
            .reports-subtitle,
            .adjust-subtitle {
                font-size: 0.95rem !important;
                line-height: 1.45 !important;
                letter-spacing: 0.04em !important;
            }

            .action-group {
                width: 100%;
                display: grid !important;
                grid-template-columns: 1fr;
                gap: 0.75rem !important;
            }

            .btn-arch-primary,
            .btn-arch-secondary,
            .btn-arch-danger,
            .btn-arch-view,
            .seasonal-action-btn {
                width: 100%;
                padding: 0.85rem 1rem !important;
                white-space: normal;
            }

            .arch-product-ribbon,
            .stats-main-grid,
            .stats-editorial,
            .stats-editorial-ribbon,
            .stats-grid,
            .analysis-grid,
            .performance-grid,
            .dashboard-content-grid,
            .focus-grid,
            .adjust-grid,
            .dossier-grid,
            .admin-grid,
            .form-layout-split,
            .form-row,
            .form-grid,
            .role-grid,
            form.filter-ribbon,
            .reports-grid,
            .kpi-grid,
            .quick-links,
            .charts-section {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }

            .row {
                display: flex !important;
                flex-direction: column !important;
                flex-wrap: nowrap !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                gap: 1rem;
            }

            .row > [class*="col-"] {
                max-width: 100% !important;
                width: 100% !important;
                flex: 0 0 auto !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .d-flex {
                flex-wrap: wrap;
            }

            .justify-content-between,
            .justify-content-end {
                justify-content: flex-start !important;
            }

            .text-end {
                text-align: left !important;
            }

            .card-header,
            .card-body {
                padding: 1rem !important;
            }

            .piece-details {
                padding: 1.25rem !important;
            }

            .piece-meta,
            .product-rank-item,
            .customer-profile {
                gap: 0.75rem !important;
            }

            .piece-price {
                font-size: 1.35rem !important;
            }

            .stat-arch-card,
            .seasonal-archive-card {
                border-radius: 16px !important;
                padding: 1rem !important;
                min-height: 0 !important;
            }

            .stat-arch-value {
                font-size: 1.75rem !important;
                margin-bottom: 0.55rem !important;
                line-height: 1.05 !important;
                overflow-wrap: anywhere;
            }

            .stat-arch-label {
                margin-bottom: 0.7rem !important;
                font-size: 0.58rem !important;
                letter-spacing: 0.12em !important;
            }

            .stat-arch-meta {
                font-size: 0.68rem !important;
                line-height: 1.25 !important;
            }

            .stat-ghost-icon {
                display: none !important;
            }

            .seasonal-title,
            .chart-perf-title,
            .section-title {
                font-size: 1.35rem !important;
                line-height: 1.15 !important;
            }

            .arch-bars-container {
                height: 180px !important;
                min-width: 520px;
            }

            .chart-perf-card {
                overflow-x: auto;
            }

            .overview-header {
                margin-bottom: 1.25rem !important;
            }

            .chart-perf-container {
                height: 220px !important;
            }

            .chart-toggle-pill {
                width: 100%;
                justify-content: space-between;
            }

            .toggle-pill-item {
                flex: 1;
                text-align: center;
                padding: 0.55rem 0.7rem !important;
            }

            .seasonal-archive-card {
                min-height: 220px;
            }

            .arch-content-card table,
            .orders-section-card table,
            .arch-table-card table,
            .arch-table-shell table,
            .returns-table-wrapper table,
            .archive-table-shell table,
            .card table {
                font-size: 0.8rem;
            }

            .product-details-container,
            .form-container,
            .edit-product-container,
            .create-user-container,
            .create-claim-container,
            .settings-container,
            .claim-show-container,
            .returns-container,
            .receipt-wrapper {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
            }

            .product-image-wrapper {
                height: auto !important;
                aspect-ratio: 4 / 3;
                margin-bottom: 1rem !important;
            }

            .detail-row,
            .data-row,
            .mini-stat,
            .product-rank-item,
            .stock-item-row,
            .feed-item {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.45rem !important;
            }

            .detail-value,
            .data-value,
            .receipt-value {
                max-width: 100% !important;
                text-align: left !important;
                overflow-wrap: anywhere;
            }

            .action-group,
            .form-footer-actions,
            .btn-action-group,
            .settings-actions,
            .receipt-header-actions {
                width: 100% !important;
                display: grid !important;
                grid-template-columns: 1fr !important;
                justify-content: stretch !important;
                gap: 0.75rem !important;
            }

            .btn-premium,
            .btn-main,
            .btn-outline,
            .btn-save,
            .btn-print,
            .btn-primary,
            .btn-secondary,
            .btn-submit,
            .btn-cancel,
            .btn-create,
            .btn-approve,
            .btn-reject,
            .open-pos-btn,
            .quick-link {
                width: 100% !important;
                justify-content: center !important;
                text-align: center !important;
            }

            .arch-input {
                font-size: 1rem !important;
                padding: 0.85rem 0 !important;
            }

            .arch-upload-zone,
            .upload-zone,
            .matrix-container {
                padding: 1.25rem !important;
                border-radius: 16px !important;
            }

            .matrix-container,
            .arch-table-card,
            .arch-table-shell,
            .archive-table-shell,
            .returns-table-wrapper,
            .chart-card,
            .card:has(table) {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .date-range-picker,
            form.filter-ribbon,
            .filter-form {
                width: 100% !important;
                display: grid !important;
                grid-template-columns: 1fr !important;
                align-items: stretch !important;
                padding: 1rem !important;
                gap: 0.75rem !important;
                border-radius: 16px !important;
            }

            .date-range-picker input,
            .filter-input,
            form.filter-ribbon input,
            form.filter-ribbon select,
            .filter-form input,
            .filter-form select {
                width: 100% !important;
                max-width: 100% !important;
            }

            .quick-access,
            .kpi-card,
            .report-card,
            .chart-card,
            .stat-card {
                border-radius: 18px !important;
                padding: 1rem !important;
            }

            .quick-access::before,
            .report-card::before,
            .kpi-card::before {
                display: none !important;
            }

            .kpi-icon,
            .report-icon {
                width: 44px !important;
                height: 44px !important;
                border-radius: 12px !important;
                font-size: 1.15rem !important;
                margin-bottom: 1rem !important;
            }

            .kpi-value {
                font-size: 1.55rem !important;
                overflow-wrap: anywhere;
            }

            .report-description {
                margin-bottom: 1.25rem !important;
            }

            .chart-placeholder {
                height: 190px !important;
                min-width: 420px;
                padding: 1rem !important;
            }

            .piece-identity-card {
                position: static !important;
            }

            .current-stock-pill {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 0.45rem !important;
                padding: 1rem !important;
                border-radius: 16px !important;
            }

            .current-stock-pill > div {
                margin-left: 0 !important;
            }

            .matrix-table,
            .table,
            .arch-table,
            .archive-table,
            .returns-table {
                min-width: 680px;
            }

            .arch-receipt-card {
                max-width: 100% !important;
                padding: 1.25rem !important;
            }

            .receipt-grid > div,
            .summary-row,
            .total-row {
                gap: 1rem;
            }

            .fab-plus-arch {
                position: fixed !important;
                right: 1rem !important;
                bottom: 1rem !important;
                width: 54px !important;
                height: 54px !important;
                z-index: 50;
                box-shadow: 0 12px 30px rgba(128, 32, 48, 0.32) !important;
            }

            .filter-ribbon {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            [style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }

            [style*="max-width"] {
                max-width: 100% !important;
            }

            [style*="min-width: 250px"],
            [style*="width: 220px"] {
                min-width: 0 !important;
                width: 100% !important;
            }
        }

        @media (max-width: 430px) {
            .stats-main-grid,
            .stats-editorial-ribbon,
            .stats-editorial,
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }

            .stat-arch-card {
                padding: 0.9rem !important;
            }

            .stat-arch-value {
                font-size: 1.42rem !important;
            }

            .stat-arch-meta {
                align-items: flex-start !important;
            }

            .nav-link {
                flex-basis: 68px;
            }

            .workspace-scroll {
                padding-inline: 0.85rem;
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
                    <i class="fas fa-th-large"></i> <span class="nav-text">Dashboard</span>
                </a>

                {{-- Product & Supply Chain --}}
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isStaff())
                <div class="nav-section-label">Inventory & Supply</div>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                    <i class="fas fa-swatchbook"></i> <span class="nav-text">Products</span>
                </a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> <span class="nav-text">Categories</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}">
                    <i class="fas fa-archive"></i> <span class="nav-text">Stock</span>
                </a>
                <a href="{{ route('batches.index') }}" class="nav-link {{ request()->is('batches*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> <span class="nav-text">Batches</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                    <i class="fas fa-truck-loading"></i> <span class="nav-text">Suppliers</span>
                </a>
                <a href="{{ route('archives.index') }}" class="nav-link {{ request()->is('archives*') ? 'active' : '' }}">
                    <i class="fas fa-box-archive"></i> <span class="nav-text">Archive</span>
                </a>
                @endif

                {{-- Operations --}}
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isCashier())
                <div class="nav-section-label">Operations</div>
                <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i> <span class="nav-text">POS</span>
                </a>
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> <span class="nav-text">Sales</span>
                </a>
                <a href="{{ route('returns.index') }}" class="nav-link {{ request()->is('returns*') ? 'active' : '' }}">
                    <i class="fas fa-undo-alt"></i> <span class="nav-text">Returns</span>
                </a>
                @endif
                
                {{-- Administrative --}}
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                <div class="nav-section-label">Administration</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> <span class="nav-text">Personnel</span>
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> <span class="nav-text">Reports</span>
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
                    <div class="navbar-search" id="globalNavbarSearch">
                        <i class="fas fa-search search-icon-nav"></i>
                        <input
                            type="text"
                            id="globalSearchInput"
                            class="search-input-nav"
                            placeholder="Search pages, tools, and reports..."
                            autocomplete="off">
                        <div class="navbar-search-results" id="globalSearchResults" aria-live="polite"></div>
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
            const searchWrapper = document.getElementById('globalNavbarSearch');
            const searchInput = document.getElementById('globalSearchInput');
            const searchResults = document.getElementById('globalSearchResults');

            if (!searchWrapper || !searchInput || !searchResults) {
                return;
            }

            const searchTargets = [
                {
                    title: 'Dashboard',
                    meta: 'Overview and branch insights',
                    keywords: ['home', 'overview', 'analytics'],
                    url: "{{ route('dashboard') }}"
                },
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isStaff())
                {
                    title: 'Products',
                    meta: 'Inventory & Supply',
                    keywords: ['items', 'catalog', 'fashion'],
                    url: "{{ route('products.index') }}"
                },
                {
                    title: 'Categories',
                    meta: 'Inventory & Supply',
                    keywords: ['tags', 'grouping', 'collections'],
                    url: "{{ route('categories.index') }}"
                },
                {
                    title: 'Stock',
                    meta: 'Inventory & Supply',
                    keywords: ['inventory', 'warehouse', 'levels'],
                    url: "{{ route('inventory.index') }}"
                },
                {
                    title: 'Batches',
                    meta: 'Inventory & Supply',
                    keywords: ['deliveries', 'purchase batches'],
                    url: "{{ route('batches.index') }}"
                },
                {
                    title: 'Suppliers',
                    meta: 'Inventory & Supply',
                    keywords: ['vendors', 'partners', 'providers'],
                    url: "{{ route('suppliers.index') }}"
                },
                @endif
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isCashier())
                {
                    title: 'POS',
                    meta: 'Operations',
                    keywords: ['checkout', 'register', 'new sale'],
                    url: "{{ route('sales.create') }}"
                },
                {
                    title: 'Sales',
                    meta: 'Operations',
                    keywords: ['transactions', 'history', 'orders'],
                    url: "{{ route('sales.index') }}"
                },
                {
                    title: 'Returns',
                    meta: 'Operations',
                    keywords: ['refunds', 'exchanges'],
                    url: "{{ route('returns.index') }}"
                },
                @endif
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                {
                    title: 'Personnel',
                    meta: 'Administration',
                    keywords: ['users', 'staff', 'employees'],
                    url: "{{ route('users.index') }}"
                },
                {
                    title: 'Reports',
                    meta: 'Administration',
                    keywords: ['analytics', 'sales report', 'inventory report'],
                    url: "{{ route('reports.index') }}"
                },
                @endif
                {
                    title: 'Settings',
                    meta: 'Account and system preferences',
                    keywords: ['profile', 'preferences', 'configuration'],
                    url: "{{ route('settings.index') }}"
                }
            ];

            let filteredTargets = [];
            let activeIndex = -1;

            function normalize(value) {
                return value.trim().toLowerCase();
            }

            function hideResults() {
                searchResults.classList.remove('is-visible');
                searchResults.innerHTML = '';
                filteredTargets = [];
                activeIndex = -1;
            }

            function renderResults(items, query) {
                if (!query) {
                    hideResults();
                    return;
                }

                if (!items.length) {
                    searchResults.innerHTML = '<div class="navbar-search-empty">No matching pages found.</div>';
                    searchResults.classList.add('is-visible');
                    activeIndex = -1;
                    return;
                }

                searchResults.innerHTML = items.map(function(item, index) {
                    return `
                        <button type="button" class="navbar-search-result${index === activeIndex ? ' is-active' : ''}" data-search-url="${item.url}" data-search-index="${index}">
                            <span>
                                <span class="navbar-search-result-title">${item.title}</span>
                                <span class="navbar-search-result-meta">${item.meta}</span>
                            </span>
                            <span class="navbar-search-result-shortcut">${index === 0 ? 'Enter' : 'Open'}</span>
                        </button>
                    `;
                }).join('');

                searchResults.classList.add('is-visible');
            }

            function updateResults() {
                const query = normalize(searchInput.value);

                if (!query) {
                    hideResults();
                    return;
                }

                filteredTargets = searchTargets.filter(function(target) {
                    const haystack = [
                        target.title,
                        target.meta,
                        ...(target.keywords || [])
                    ].join(' ').toLowerCase();

                    return haystack.includes(query);
                });

                activeIndex = filteredTargets.length ? 0 : -1;
                renderResults(filteredTargets, query);
            }

            function openActiveResult(index) {
                const target = filteredTargets[index];

                if (!target) {
                    return;
                }

                window.location.href = target.url;
            }

            searchInput.addEventListener('input', updateResults);

            searchInput.addEventListener('focus', function() {
                if (searchInput.value.trim()) {
                    updateResults();
                }
            });

            searchInput.addEventListener('keydown', function(event) {
                if (!filteredTargets.length) {
                    if (event.key === 'Enter' && searchInput.value.trim()) {
                        event.preventDefault();
                    }
                    return;
                }

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    activeIndex = (activeIndex + 1) % filteredTargets.length;
                    renderResults(filteredTargets, normalize(searchInput.value));
                }

                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    activeIndex = (activeIndex - 1 + filteredTargets.length) % filteredTargets.length;
                    renderResults(filteredTargets, normalize(searchInput.value));
                }

                if (event.key === 'Enter') {
                    event.preventDefault();
                    openActiveResult(activeIndex >= 0 ? activeIndex : 0);
                }

                if (event.key === 'Escape') {
                    hideResults();
                    searchInput.blur();
                }
            });

            searchResults.addEventListener('click', function(event) {
                const button = event.target.closest('.navbar-search-result');

                if (!button) {
                    return;
                }

                window.location.href = button.dataset.searchUrl;
            });

            document.addEventListener('click', function(event) {
                if (!searchWrapper.contains(event.target)) {
                    hideResults();
                }
            });
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
