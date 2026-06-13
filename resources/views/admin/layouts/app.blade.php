<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'অ্যাডমিন প্যানেল') | {{ config('app.name', 'খরচ হিসাব') }}</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-white shadow-sm">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="fa-solid fa-bars"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ route('home') }}" class="nav-link">হোম</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="fa-regular fa-circle-user me-1"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> লগআউট
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <aside class="app-sidebar shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-link text-decoration-none">
                    <img src="{{ asset('images/expense-logo.svg') }}" alt="খরচ হিসাব লোগো" class="brand-image rounded">
                    <span class="brand-text fw-bold ms-2">Expense Management</span>
                </a>
            </div>

            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-gauge-high"></i>
                                <p>ড্যাশবোর্ড</p>
                            </a>
                        </li>
                        <li class="nav-header">খরচ</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-receipt"></i>
                                <p>খরচ তালিকা</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-chart-pie"></i>
                                <p>রিপোর্ট</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-wallet"></i>
                                <p>বাজেট</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">@yield('page-title', 'ড্যাশবোর্ড')</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                                <li class="breadcrumb-item active">@yield('page-title', 'ড্যাশবোর্ড')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        <footer class="app-footer">
            <strong>{{ config('app.name', 'খরচ হিসাব') }}</strong>
            <span class="float-end d-none d-sm-inline">খরচ ড্যাশবোর্ড</span>
        </footer>

        <nav class="mobile-bottom-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>ড্যাশবোর্ড</span>
            </a>
            <a href="{{ route('admin.expenses.index') }}" class="{{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i>
                <span>খরচ</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-chart-pie"></i>
                <span>রিপোর্ট</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-wallet"></i>
                <span>বাজেট</span>
            </a>
        </nav>
    </div>
</body>
</html>
