<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBS') - Professional Car Broker System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo-cbs.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Figtree', 'Poppins', system-ui, sans-serif;
        }

        :root {
            --primary: #2563EB;
            --primary-dark: #1E40AF;
            --primary-light: #DBEAFE;
            --secondary: #10B981;
            --accent: #F97316;
            --dark: #0F172A;
            --light: #F8FAFC;
        }

        body {
            background: linear-gradient(135deg, #F8FAFC 0%, #E0E7FF 100%);
            color: #1F2937;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Professional shadows */
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        <a href="#main-content" class="skip-link">Skip to content</a>
        <!-- Top Navigation Bar -->
        <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200/50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo & Brand -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all">
                            <svg class="w-7 h-7" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="100" cy="100" r="95" fill="url(#gradMain)"/>
                                <defs>
                                    <linearGradient id="gradMain" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#3B82F6"/>
                                        <stop offset="100%" style="stop-color:#60A5FA"/>
                                    </linearGradient>
                                </defs>
                                <path d="M 55 85 L 65 60 L 135 60 L 145 85 M 50 85 Q 45 90 45 100 L 45 115 Q 45 125 55 125 L 145 125 Q 155 125 155 115 L 155 100 Q 155 90 150 85 Z" fill="white"/>
                                <circle cx="70" cy="125" r="12" fill="#10B981"/><circle cx="130" cy="125" r="12" fill="#10B981"/>
                            </svg>
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-lg font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">CBS</div>
                            <div class="text-xs text-gray-500 font-medium">Car Broker Pro</div>
                        </div>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }} transition">
                            Dashboard
                        </a>
                        <a href="{{ route('vehicles.shop') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('vehicles.shop') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }} transition">
                            Browse
                        </a>
                        @auth
                            <a href="{{ route('my-vehicles.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('my-vehicles.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }} transition">
                                My Listings
                            </a>
                        @endauth
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        @auth
                            <div class="hidden sm:flex items-center gap-4">
                                <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="hidden md:block">
                                        <p class="text-xs font-medium text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium transition">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="hidden sm:flex items-center gap-3">
                                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                    Sign In
                                </a>
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-lg transition shadow-sm">
                                    Get Started
                                </a>
                            </div>
                        @endauth

                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" type="button" aria-controls="mobile-menu" aria-expanded="false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 bg-white" aria-hidden="true">
                <div class="px-4 py-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition">
                        Dashboard
                    </a>
                    <a href="{{ route('vehicles.shop') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition">
                        Browse Vehicles
                    </a>
                    @auth
                        <a href="{{ route('my-vehicles.index') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition">
                            My Listings
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t">
                            @csrf
                            <button class="w-full px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 text-center transition">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main id="main-content" tabindex="-1" class="relative z-10 flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Page Header -->
            @if(View::hasSection('title') && request()->routeIs('dashboard', 'my-vehicles.*', 'vehicles.*'))
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">@yield('title', 'Dashboard')</h1>
                <p class="text-gray-600">@yield('subtitle', 'Manage your platform')</p>
            </div>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-900 flex items-start gap-3 shadow-sm animation-slideDown">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="flex-1">
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-gradient-to-r from-red-50 via-rose-50 to-red-50 p-4 sm:p-5 text-red-900 shadow-sm animation-slideDown" role="alert" aria-live="polite">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold uppercase tracking-wide text-red-700">Access denied</p>
                            <p class="mt-1 text-sm sm:text-base font-medium">{{ session('error') }}</p>
                        </div>
                        <button type="button" onclick="this.closest('[role=alert]').remove()" class="rounded-lg p-2 text-red-500 hover:bg-red-100 hover:text-red-700" aria-label="Dismiss alert">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-900 shadow-sm animation-slideDown">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <ul class="space-y-1 flex-1">
                            @foreach($errors->all() as $error)
                                <li class="font-semibold text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Professional Footer -->
        @include('components.footer')
    </div>

    <script>
        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        
        if (btn && menu) {
            btn.addEventListener('click', () => {
                const isHidden = menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', String(!isHidden));
                menu.setAttribute('aria-hidden', String(isHidden));
            });
            
            document.querySelectorAll('#mobile-menu a, #mobile-menu button').forEach(el => {
                el.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                    menu.setAttribute('aria-hidden', 'true');
                });
            });
        }

        // Close alerts smoothly
        document.querySelectorAll('[onclick="this.parentElement.remove()"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.style.opacity = '0';
                this.parentElement.style.transform = 'translateY(-10px)';
                setTimeout(() => this.parentElement.remove(), 300);
            });
        });
    </script>
</body>
</html>
