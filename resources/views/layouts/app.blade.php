<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #1e3a8a;
            /* Biru navy */
            --primary-dark: #1e40af;
            /* Biru gelap */
            --primary-light: #3b82f6;
            /* Biru terang */
            --secondary-color: #60a5fa;
            /* Biru sekunder */
            --accent-color: #0ea5e9;
            /* Aksen biru muda */
            --sidebar-bg: #172554;
            /* Latar sidebar gelap */
            --sidebar-hover: #1e3a8a;
            /* Hover sidebar */
            --sidebar-active: #3b82f6;
            /* Sidebar aktif */
            --light-bg: #f0f9ff;
            /* Latar belakang terang */
            --dark-text: #0f172a;
            /* Teks gelap */
            --light-text: #f8fafc;
            /* Teks terang */
            --muted-text: #64748b;
            /* Teks redup */
            --border-color: #e2e8f0;
            /* Warna garis batas */
            --border-radius: 0.4rem;
            --border-radius-sm: 0.25rem;
            --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            --box-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            --box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --transition: all 0.2s ease-in-out;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 64px;
        }



        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            transition: var(--transition);
            font-size: 0.95rem;
            line-height: 1.5;
        }


        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            background: none;
            border: none;
            color: #333;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s ease;
        }

        .user-dropdown-btn:hover {
            background-color: #f8f9fa;
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            z-index: 1000;
            display: none;
            margin-top: 4px;
        }

        .user-dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #dee2e6;
            margin: 4px 0;
        }

        .d-none {
            display: none;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--light-text);
            z-index: 1000;
            transition: var(--transition);
            box-shadow: var(--box-shadow-md);
            overflow-y: auto;
        }

        .sidebar-collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-height);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-brand {
            color: var(--light-text) !important;
            font-weight: 600;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-brand i {
            font-size: 1.25rem;
            margin-right: 10px;
            color: var(--primary-light);
        }

        .sidebar-toggle {
            color: rgba(255, 255, 255, 0.7);
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: var(--transition);
            border-radius: var(--border-radius-sm);
        }

        .sidebar-toggle:hover {
            color: var(--light-text);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu-item {
            margin-bottom: 0.125rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: var(--transition);
            border-radius: 0;
            font-weight: 500;
            white-space: nowrap;
            position: relative;
            margin: 0 0.5rem;
            border-radius: var(--border-radius-sm);
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1rem;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-link.active {
            background-color: var(--sidebar-active);
            color: #fff;
            box-shadow: var(--box-shadow);
        }

        .sidebar-collapsed .sidebar-brand span,
        .sidebar-collapsed .sidebar-link span {
            display: none;
        }

        .sidebar-collapsed .sidebar-brand i {
            margin-right: 0;
        }

        .sidebar-collapsed .sidebar-link {
            padding: 0.75rem 0;
            justify-content: center;
            margin: 0 0.75rem;
        }

        .sidebar-collapsed .sidebar-link i {
            margin-right: 0;
            font-size: 1.125rem;
        }

        .sidebar-collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* Dropdown menu in sidebar */
        .sidebar-dropdown {
            background-color: rgba(0, 0, 0, 0.1);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin: 0 0.5rem;
            border-radius: var(--border-radius-sm);
        }

        .sidebar-dropdown.show {
            max-height: 500px;
            margin-top: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-dropdown-item {
            padding: 0.6rem 1rem 0.6rem 3rem;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 400;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .sidebar-dropdown-item:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-dropdown-item.active {
            background-color: var(--sidebar-active);
            color: #fff;
        }

        .sidebar-collapsed .sidebar-dropdown-item {
            padding-left: 0;
            justify-content: center;
        }

        /* Main content area */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header Bar */
        .header {
            height: var(--header-height);
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark-text);
            cursor: pointer;
            margin-right: 1rem;
            padding: 0.375rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .mobile-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .user-menu {
            display: flex;
            align-items: center;
        }

        .user-menu .btn {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: none;
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            background-color: transparent;
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            color: var(--dark-text);
        }

        .user-dropdown-btn:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .user-dropdown-btn i.fa-user-circle {
            font-size: 1.1rem;
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .user-dropdown-btn i.fa-chevron-down {
            font-size: 0.75rem;
            margin-left: 0.5rem;
            color: var(--muted-text);
            transition: var(--transition);
        }

        .user-dropdown-content {
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            min-width: 220px;
            z-index: 1000;
            display: none;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .user-dropdown-content.show {
            display: block;
            animation: fadeInDown 0.2s ease-out forwards;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-dropdown-item {
            padding: 0.75rem 1.25rem;
            color: var(--dark-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: var(--transition);
            font-weight: 500;
        }

        .user-dropdown-item:hover {
            background-color: rgba(41, 182, 137, 0.08);
            color: var(--primary-color);
        }

        .user-dropdown-item i {
            margin-right: 10px;
            font-size: 1rem;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }

        .user-dropdown-item.text-danger i {
            color: #dc3545;
        }

        .user-dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.08);
            color: #dc3545;
        }

        .user-dropdown-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 0;
        }

        /* Main area */
        .page-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* Footer */
        .footer {
            background-color: #fff;
            padding: 1rem 1.75rem;
            border-top: 1px solid var(--border-color);
            margin-top: auto;
            color: var(--muted-text);
            font-size: 0.875rem;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-md);
            transition: var(--transition);
            overflow: hidden;
            background-color: #fff;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: var(--box-shadow-lg);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--dark-text);
            padding: 1rem 1.25rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Button styling */
        .btn {
            border-radius: var(--border-radius);
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: none;
            font-size: 0.9rem;
        }

        .btn:active,
        .btn:focus {
            box-shadow: none !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-light {
            background-color: #fff;
            border-color: var(--border-color);
            color: var(--dark-text);
        }

        .btn-light:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Form controls */
        .form-control,
        .form-select {
            border-radius: var(--border-radius);
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(41, 182, 137, 0.15);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--dark-text);
        }

        /* Tables */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table th {
            background-color: #f9fafb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--muted-text);
            border-top: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
            color: var(--dark-text);
            font-size: 0.9rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.01);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(41, 182, 137, 0.04);
        }

        /* Badges */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-primary {
            background-color: rgba(41, 182, 137, 0.15);
            color: var(--primary-color);
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .badge-warning {
            background-color: rgba(247, 164, 0, 0.15);
            color: var(--accent-color);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        /* Animation for loading and transitions */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Alert styling */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #0d9668;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .alert i {
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .alert .btn-close {
            padding: 0.75rem;
            margin: -0.5rem -0.5rem -0.5rem auto;
        }

        /* Toast notifications */
        .toast {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            border: none;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.02);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.2);
        }

        /* Stats Card */
        .stats-card {
            display: flex;
            align-items: center;
            padding: 1.25rem;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
            color: white;
        }

        .stats-icon.primary {
            background-color: var(--primary-color);
        }

        .stats-icon.success {
            background-color: var(--secondary-color);
        }

        .stats-icon.warning {
            background-color: var(--accent-color);
        }

        .stats-info {
            flex: 1;
        }

        .stats-label {
            color: var(--muted-text);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .stats-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0;
        }

        .stats-trend {
            font-size: 0.8125rem;
            display: flex;
            align-items: center;
        }

        .stats-trend.up {
            color: #10b981;
        }

        .stats-trend.down {
            color: #ef4444;
        }

        .stats-trend i {
            font-size: 0.75rem;
            margin-right: 0.25rem;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content-collapsed {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .sidebar-collapsed {
                width: var(--sidebar-width);
                transform: translateX(-100%);
            }

            .sidebar-collapsed.show {
                transform: translateX(0);
            }

            .sidebar-collapsed .sidebar-brand span,
            .sidebar-collapsed .sidebar-link span {
                display: inline;
            }

            .sidebar-collapsed .sidebar-brand i {
                margin-right: 12px;
            }

            .sidebar-collapsed .sidebar-link i {
                margin-right: 12px;
                font-size: 1.1rem;
            }

            .sidebar-collapsed .sidebar-link {
                padding: 0.75rem 1.25rem;
                justify-content: flex-start;
                margin: 0 0.5rem;
            }

            .page-content {
                padding: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .user-dropdown-btn span {
                display: none;
            }

            .user-dropdown-btn i.fa-user-circle {
                margin-right: 0;
            }

            .user-dropdown-btn {
                padding: 0.5rem;
                width: 36px;
                height: 36px;
                justify-content: center;
            }

            .user-dropdown-btn i.fa-chevron-down {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .page-content {
                padding: 1rem;
            }

            .header {
                padding: 0 1rem;
            }

            .footer {
                padding: 0.75rem 1rem;
                text-align: center;
            }
        }
    </style>

    @stack('styles')

</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a class="sidebar-brand" href="{{ url('/') }}">
                    <i class="fas fa-prescription-bottle-medical"></i>
                    <span>Apotek Berkah Ibu</span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                @guest
                    @if (Route::has('login'))
                        <li class="sidebar-menu-item">
                            <a class="sidebar-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>{{ __('Login') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="sidebar-menu-item">
                            <a class="sidebar-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i>
                                <span>{{ __('Register') }}</span>
                            </a>
                        </li>
                    @endif
                @else
                    <li class="sidebar-menu-item">
                        <a class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>

                    @role('kasir')
                        <li class="sidebar-menu-item">
                            <a class="sidebar-link {{ request()->routeIs('transaksi.create') ? 'active' : '' }}"
                                href="{{ route('transaksi.create') }}">
                                <i class="fas fa-cash-register"></i>
                                <span>{{ __('Kasir') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-link {{ request()->routeIs('obat*') ? 'active' : '' }}"
                                href="{{ route('obat.index') }}">
                                <i class="fas fa-pills"></i>
                                <span>{{ __('Manajemen Obat') }}</span>
                            </a>
                        </li>
                    @endrole

                    @if (Auth::user()->hasRole('kasir') || Auth::user()->hasRole('pemilik'))
                        <li class="sidebar-menu-item">
                            <a class="sidebar-link {{ request()->routeIs('laporan.penjualan*') ? 'active' : '' }}"
                                href="{{ route('laporan.penjualan.index') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>{{ __('Laporan Penjualan') }}</span>
                            </a>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>

        <div class="main-content" id="mainContent">
            <!-- Header -->
            <div class="header">
                <div class="header-content">
                    <div>
                        <button class="mobile-toggle" id="mobileToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <div class="user-menu">
                        @guest
                            <div class="d-flex">
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="user-dropdown">
                                <button class="user-dropdown-btn" id="headerUserDropdown">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <!-- Dropdown Menu -->
                                <div class="user-dropdown-menu" id="userDropdownMenu">
                                    <a href="{{ route('profil.edit') }}" class="dropdown-item">
                                        <i class="fas fa-user-edit me-2"></i>{{ __('Edit Profil') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('logout') }}" class="dropdown-item"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content fade-in">
                <div class="container">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0">&copy; {{ date('Y') }} Apotek Berkah Ibu. All rights reserved.</p>
                        <div class="d-none d-md-block">
                            <span>Melayani dengan sepenuh hati</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Sidebar functionality
            initializeSidebar();

            // User dropdown functionality
            initializeUserDropdown();
        });

        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileToggle = document.getElementById('mobileToggle');

            if (!sidebar || !mainContent) return;

            // Check for stored sidebar state
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Set initial state
            if (sidebarCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.add('main-content-collapsed');
            }

            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-collapsed');
                    mainContent.classList.toggle('main-content-collapsed');

                    // Store state
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
                });
            }

            // Mobile sidebar toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickInsideToggle = mobileToggle && mobileToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickInsideToggle &&
                    sidebar.classList.contains('show') &&
                    window.innerWidth < 992) {
                    sidebar.classList.remove('show');
                }
            });
        }

        function initializeUserDropdown() {
            const dropdownBtn = document.getElementById('headerUserDropdown');
            const dropdownMenu = document.getElementById('userDropdownMenu');

            if (!dropdownBtn || !dropdownMenu) return;

            // Toggle dropdown on button click
            dropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });

            // Close dropdown when pressing Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
