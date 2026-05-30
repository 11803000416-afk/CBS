<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CBS') - Car Broker System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script>
        (function () {
            try {
                const savedTheme = localStorage.getItem('cbs-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = savedTheme || (prefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.classList.toggle('dark', theme === 'dark');
            } catch (error) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- CBS Optimization Scripts -->
    <script src="{{ asset('js/optimization.js') }}" defer></script>
    <!-- Chart.js for Analytics (deferred to avoid render-block) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" defer></script>

    <!-- Preconnect & Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Sora', system-ui, sans-serif; }
    </style>

    <!-- Preload hero background for faster LCP -->
    <link rel="preload" as="image" href="{{ asset('images/vintage-chevrolet-1965.webp') }}">
</head>
<body class="bg-gray-50 text-gray-900 overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        <a href="#main-content" class="skip-link">Skip to main content</a>
        <!-- Vehica Top Bar -->
        <div class="h-1 bg-gradient-to-r from-cyan-600 via-cyan-500 to-orange-500"></div>
        
        <!-- Vehica Navigation -->
        <nav class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm" aria-label="Primary navigation">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo/Brand - Vehica Style -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group" aria-label="Car Broker System home">
                        <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-gradient-to-br from-cyan-600 to-cyan-500 shadow-lg overflow-hidden">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-lg font-bold text-gray-900">CBS Motors</div>
                            <div class="text-xs text-gray-500 font-medium">Professional Automotive Platform</div>
                        </div>
                    </a>
                    
                    <!-- Desktop Navigation - Vehica Style -->
                    <div class="hidden lg:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-cyan-50 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                            Dashboard
                        </a>
                        @if(auth()->check() && auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_BROKER]))
                            <a href="{{ route('vehicles.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('vehicles.index') ? 'bg-cyan-50 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('vehicles.index') ? 'page' : 'false' }}">
                                Vehicles
                            </a>
                            <a href="{{ route('buyers.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('buyers.*') ? 'bg-emerald-50 text-emerald-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('buyers.*') ? 'page' : 'false' }}">
                                Buyers
                            </a>
                            <a href="{{ route('sellers.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('sellers.*') ? 'bg-orange-50 text-orange-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('sellers.*') ? 'page' : 'false' }}">
                                Sellers
                            </a>
                            <a href="{{ route('transactions.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('transactions.*') ? 'bg-blue-50 text-blue-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('transactions.*') ? 'page' : 'false' }}">
                                Transactions
                            </a>
                            @if(auth()->user()->hasRole(\App\Models\User::ROLE_BROKER))
                                <a href="{{ route('broker.license.show') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('broker.license.*') ? 'bg-cyan-50 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('broker.license.*') ? 'page' : 'false' }}">
                                    License
                                </a>
                            @endif
                            <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-amber-50 text-amber-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('reports.*') ? 'page' : 'false' }}">
                                Reports
                            </a>
                            @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN))
                                <a href="{{ route('admin.broker-licenses.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.broker-licenses.*') ? 'bg-cyan-50 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('admin.broker-licenses.*') ? 'page' : 'false' }}">
                                    License Approvals
                                    @if(($pendingBrokerLicenseRequestsCount ?? 0) > 0)
                                        <span class="ml-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[11px] font-bold leading-none text-white">{{ $pendingBrokerLicenseRequestsCount }}</span>
                                    @endif
                                </a>
                            @endif
                        @else
                            <a href="{{ route('my-vehicles.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('my-vehicles.*') ? 'bg-cyan-50 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('my-vehicles.*') ? 'page' : 'false' }}">
                                My Listings
                            </a>
                        @endif
                        <a href="{{ route('broker.license.show') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('broker.license.*') ? 'bg-cyan-50 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('broker.license.*') ? 'page' : 'false' }}">
                            Become Broker
                        </a>
                        <a href="{{ route('inquiries.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('inquiries.*') ? 'bg-amber-50 text-amber-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('inquiries.*') ? 'page' : 'false' }}">
                            Inquiries
                        </a>
                    </div>
                    
                    <!-- Right Side: Notifications & User & Mobile Menu -->
                    <div class="flex items-center gap-4">
                        <div class="relative" id="notificationBellContainer">
                            <button
                                id="notificationBellBtn"
                                type="button"
                                class="relative inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white hover:bg-gray-50 p-2 text-gray-700"
                                aria-label="Open notifications"
                                aria-expanded="false"
                                aria-controls="notificationDropdown"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                                </svg>
                                <span
                                    id="notificationUnreadBadge"
                                    class="absolute -right-1 -top-1 hidden min-w-[18px] rounded-full bg-red-600 px-1.5 py-0.5 text-center text-[10px] font-bold leading-none text-white"
                                    aria-live="polite"
                                >0</span>
                            </button>

                            <div
                                id="notificationDropdown"
                                class="absolute right-0 z-50 mt-2 hidden w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xl"
                                role="dialog"
                                aria-label="Notifications panel"
                            >
                                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                                    <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                                    <button id="notificationsMarkAllRead" type="button" class="text-xs font-semibold text-cyan-600 hover:text-cyan-800">Mark all read</button>
                                </div>
                                <ul id="notificationList" class="max-h-96 overflow-y-auto" aria-live="polite">
                                    <li class="px-4 py-3 text-sm text-gray-500">Loading notifications...</li>
                                </ul>
                            </div>
                        </div>

                        <button type="button" data-theme-toggle class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-700" aria-label="Toggle color theme">
                            <svg data-theme-icon-sun class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                            <svg data-theme-icon-moon class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646a9 9 0 1011.708 11.708z"/></svg>
                        </button>

                        <!-- Live Date/Time (Desktop Only) -->
                        <div class="hidden md:flex flex-col justify-center items-end h-16 text-right border-r border-gray-200 pr-4">
                            <p class="m-0 text-xs text-gray-600 leading-tight" id="currentDate">Loading...</p>
                            <p class="m-0 text-sm font-semibold text-cyan-600 leading-none" id="currentTime">--:--:--</p>
                        </div>
                        
                        <!-- User Menu & Logout (Desktop) -->
                        <div class="hidden sm:flex items-center gap-4">
                            <div class="hidden xl:block text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
                                <p class="text-xs text-cyan-600 capitalize font-medium">{{ auth()->check() ? auth()->user()->role : 'guest' }}</p>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-cyan-600 to-cyan-500 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
                                {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="px-4 py-2 rounded-lg border border-primary-300 hover:bg-primary-50 text-primary-700 text-sm font-medium transition hover:shadow-xs">
                                    Logout
                                </button>
                            </form>
                        </div>
                        
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" type="button" aria-controls="mobile-menu" aria-expanded="false" aria-label="Open navigation menu" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden lg:hidden border-t-2 border-primary-200 bg-white" aria-hidden="true" aria-label="Mobile navigation menu">
                <div class="px-4 py-4 space-y-2 max-h-96 overflow-y-auto">
                    <!-- Date/Time (Mobile) -->
                    <div class="px-3 py-3 rounded-lg bg-gradient-to-r from-primary-50 to-info-50 mb-3 border border-primary-200">
                        <p class="m-0 text-xs text-primary-600" id="currentDateMobile">Loading...</p>
                        <p class="m-0 text-sm font-semibold text-primary-700" id="currentTimeMobile">--:--:--</p>
                    </div>

                    <button type="button" data-theme-toggle class="w-full mb-3 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100" aria-label="Toggle color theme">
                        <svg data-theme-icon-sun class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0L16.95 7.05M7.05 16.95l-1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                        <svg data-theme-icon-moon class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646a9 9 0 1011.708 11.708z"/></svg>
                        <span data-theme-label>Dark</span>
                    </button>
                    
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-primary-100 text-primary-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                        Home
                    </a>
                    @if(auth()->check() && auth()->user()->hasRole(['admin','broker']))
                        <a href="{{ route('vehicles.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('vehicles.index') ? 'bg-primary-100 text-primary-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('vehicles.index') ? 'page' : 'false' }}">
                            Manage Vehicles
                        </a>
                        <a href="{{ route('buyers.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('buyers.*') ? 'bg-success-100 text-success-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('buyers.*') ? 'page' : 'false' }}">
                            Buyers
                        </a>
                        <a href="{{ route('sellers.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('sellers.*') ? 'bg-accent-100 text-accent-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('sellers.*') ? 'page' : 'false' }}">
                            Sellers
                        </a>
                        <a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('transactions.*') ? 'bg-info-100 text-info-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('transactions.*') ? 'page' : 'false' }}">
                            Transactions
                        </a>
                        @if(auth()->user()->hasRole(\App\Models\User::ROLE_BROKER))
                            <a href="{{ route('broker.license.show') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('broker.license.*') ? 'bg-cyan-100 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('broker.license.*') ? 'page' : 'false' }}">
                                Dealer License
                            </a>
                        @endif
                        <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('reports.*') ? 'bg-warning-100 text-warning-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition">
                            Reports
                        </a>
                        @if(auth()->check() && auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN))
                            <a href="{{ route('admin.seller-requests.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.seller-requests.*') ? 'bg-purple-100 text-purple-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('admin.seller-requests.*') ? 'page' : 'false' }}">
                                Seller Requests
                                @if(($pendingSellerRequestsCount ?? 0) > 0)
                                    <span class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full">{{ $pendingSellerRequestsCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.broker-licenses.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('admin.broker-licenses.*') ? 'bg-cyan-100 text-cyan-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('admin.broker-licenses.*') ? 'page' : 'false' }}">
                                Broker Licenses
                                @if(($pendingBrokerLicenseRequestsCount ?? 0) > 0)
                                    <span class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full">{{ $pendingBrokerLicenseRequestsCount }}</span>
                                @endif
                            </a>
                        @endif
                    @else
                        <a href="/my-vehicles" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition">
                            My Listings
                        </a>
                    @endif
                    <a href="{{ route('inquiries.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('inquiries.*') ? 'bg-amber-50 text-amber-700 shadow-xs' : 'text-gray-700 hover:bg-gray-100' }} transition" aria-current="{{ request()->routeIs('inquiries.*') ? 'page' : 'false' }}">
                        Inquiries
                    </a>

                    <!-- User Info & Logout (Mobile) -->
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="px-3 py-3 rounded-lg bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
                            <p class="text-xs text-cyan-600 capitalize font-medium">{{ auth()->check() ? auth()->user()->role : 'guest' }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 rounded-lg border-2 border-cyan-300 hover:bg-cyan-50 text-cyan-700 text-sm font-medium transition hover:shadow-xs">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section w-full">
            <div class="hero-overlay" aria-hidden="true"></div>
            <div class="hero-content">
                <h2 class="hero-title">Trusted Cars. Premium Experience.</h2>
                <p class="hero-subtitle">Buy, sell or broker cars with confidence — professional listings, verified dealers, and smooth transactions.</p>
                <div class="hero-cta">
                    <a href="{{ route('vehicles.index') }}" class="btn-hero-primary">Browse Vehicles</a>
                    <a href="{{ route('my-vehicles.index') }}" class="btn-hero-secondary">List Your Car</a>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main id="main-content" tabindex="-1" class="relative z-10 flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8" aria-label="Main content">
            <!-- Page Header -->
            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1 sm:mb-2">@yield('title', 'Dashboard')</h1>
                <p class="text-gray-600 font-medium text-sm sm:text-base">@yield('subtitle', 'Manage your car trading operations')</p>
            </div>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border-2 border-emerald-300 text-emerald-900 flex items-start gap-3 shadow-md animation-fadeIn">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="flex-1">
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-gradient-to-r from-red-50 to-rose-50 p-4 text-red-900 shadow-md animation-fadeIn" role="alert" aria-live="polite">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold uppercase tracking-wide text-red-700">Access denied</p>
                            <p class="mt-1 text-sm font-semibold">{{ session('error') }}</p>
                        </div>
                        <button type="button" onclick="this.closest('[role=alert]').remove()" class="rounded-lg p-2 text-red-500 hover:bg-red-100 hover:text-red-700" aria-label="Dismiss alert">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border-2 border-red-300 text-red-900 shadow-md animation-fadeIn">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <ul class="space-y-1 flex-1">
                            @foreach($errors->all() as $error)
                                <li class="font-semibold">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-danger-600 hover:text-danger-800 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Live Date/Time & Mobile Menu Script -->
    <script>
        function updateDateTime() {
            const now = new Date();
            
            // Format date: Wednesday, March 20, 2026
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', dateOptions);
            
            // Format time: 3:50:36 PM
            const timeOptions = { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
            const timeStr = now.toLocaleTimeString('en-US', timeOptions);
            
            const dateEl = document.getElementById('currentDate');
            const timeEl = document.getElementById('currentTime');
            const dateMobileEl = document.getElementById('currentDateMobile');
            const timeMobileEl = document.getElementById('currentTimeMobile');
            
            if (dateEl) dateEl.textContent = dateStr;
            if (timeEl) timeEl.textContent = timeStr;
            if (dateMobileEl) dateMobileEl.textContent = dateStr;
            if (timeMobileEl) timeMobileEl.textContent = timeStr;
        }
        
        // Update immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Mobile Menu Toggle
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                mobileBtn.setAttribute('aria-expanded', String(!isHidden));
                mobileMenu.setAttribute('aria-hidden', String(isHidden));
            });

            // Close mobile menu when link is clicked
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    mobileBtn.setAttribute('aria-expanded', 'false');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                });
            });
        }
    </script>
</body>
</html>
