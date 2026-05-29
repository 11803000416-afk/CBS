@extends('layouts.app')

@section('title', 'Design System & Components')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-12 animate-slideUp">
        <h1 class="text-5xl font-bold gradient-text mb-4">Design System Showcase</h1>
        <p class="text-xl text-gray-600">Professional "Ice on Fire" Components</p>
    </div>

    <!-- Animations Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">✨ Animations</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="dashboard-card animate-fadeIn">
                <p class="text-center font-semibold">fadeIn</p>
            </div>
            <div class="dashboard-card animate-slideUp">
                <p class="text-center font-semibold">slideUp</p>
            </div>
            <div class="dashboard-card animate-slideDown">
                <p class="text-center font-semibold">slideDown</p>
            </div>
            <div class="dashboard-card animate-scaleIn">
                <p class="text-center font-semibold">scaleIn</p>
            </div>
        </div>
    </section>

    <!-- Buttons Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">🔘 Buttons</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <button class="btn-primary">
                <svg class="w-5 h-5 inline mr-2"><!-- Icon --></svg>
                Primary
            </button>
            <button class="btn-secondary">
                <svg class="w-5 h-5 inline mr-2"><!-- Icon --></svg>
                Secondary
            </button>
            <button class="btn-success">
                <svg class="w-5 h-5 inline mr-2"><!-- Icon --></svg>
                Success
            </button>
            <button class="btn-outline">
                Outline
            </button>
            <button class="btn-primary opacity-50 cursor-not-allowed">
                Disabled
            </button>
        </div>
    </section>

    <!-- Cards Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">🎴 Cards</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card-gradient dashboard-card-hover">
                <h3 class="text-xl font-bold text-primary-900 mb-2">Primary Card</h3>
                <p class="text-primary-700">Beautiful gradient card with hover effects</p>
            </div>
            <div class="card-success dashboard-card-hover">
                <h3 class="text-xl font-bold text-success-900 mb-2">Success Card</h3>
                <p class="text-success-700">Green gradient for positive actions</p>
            </div>
            <div class="card-accent dashboard-card-hover">
                <h3 class="text-xl font-bold text-accent-900 mb-2">Accent Card</h3>
                <p class="text-accent-700">Purple gradient for secondary actions</p>
            </div>
        </div>
    </section>

    <!-- Stat Cards Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">📊 Stat Cards</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stat-card-modern card-blue dashboard-card-hover group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-700 text-sm font-semibold mb-2">Total</p>
                        <p class="text-4xl font-bold text-blue-900">1,234</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 group-hover:scale-110 transition-transform"></div>
                </div>
            </div>
            <div class="stat-card-modern card-emerald dashboard-card-hover group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-emerald-700 text-sm font-semibold mb-2">Success</p>
                        <p class="text-4xl font-bold text-emerald-900">567</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 group-hover:scale-110 transition-transform"></div>
                </div>
            </div>
            <div class="stat-card-modern card-purple dashboard-card-hover group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-purple-700 text-sm font-semibold mb-2">Pending</p>
                        <p class="text-4xl font-bold text-purple-900">89</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 group-hover:scale-110 transition-transform"></div>
                </div>
            </div>
            <div class="stat-card-modern card-cyan dashboard-card-hover group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-cyan-700 text-sm font-semibold mb-2">Users</p>
                        <p class="text-4xl font-bold text-cyan-900">432</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 group-hover:scale-110 transition-transform"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Badges Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">🏷️ Badges</h2>
        <div class="flex flex-wrap gap-4">
            <span class="badge-primary">Primary</span>
            <span class="badge-success">Success</span>
            <span class="badge-warning">Warning</span>
            <span class="badge-danger">Danger</span>
            <span class="badge-info">Info</span>
        </div>
    </section>

    <!-- Alerts Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">🚨 Alerts</h2>
        <div class="space-y-4">
            <div class="alert-success flex items-center gap-2">
                <svg class="w-5 h-5"><!-- Icon --></svg>
                Success message displayed here
            </div>
            <div class="alert-warning flex items-center gap-2">
                <svg class="w-5 h-5"><!-- Icon --></svg>
                Warning message displayed here
            </div>
            <div class="alert-danger flex items-center gap-2">
                <svg class="w-5 h-5"><!-- Icon --></svg>
                Error message displayed here
            </div>
            <div class="alert-info flex items-center gap-2">
                <svg class="w-5 h-5"><!-- Icon --></svg>
                Info message displayed here
            </div>
        </div>
    </section>

    <!-- Form Inputs Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">✍️ Form Inputs</h2>
        <form class="space-y-6 max-w-2xl">
            <div>
                <label class="label-primary">Text Input</label>
                <input type="text" class="input-field" placeholder="Enter text...">
            </div>
            <div>
                <label class="label-primary">Email Input</label>
                <input type="email" class="input-field" placeholder="Enter email...">
            </div>
            <div>
                <label class="label-primary">Select</label>
                <select class="input-field">
                    <option>Option 1</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                </select>
            </div>
            <div>
                <label class="label-primary">Textarea</label>
                <textarea class="input-field h-32" placeholder="Enter message..."></textarea>
            </div>
        </form>
    </section>

    <!-- Colors Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">🎨 Color Palette</h2>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="text-center">
                <div class="h-16 bg-primary-600 rounded-lg mb-2"></div>
                <p class="text-sm font-semibold">Primary</p>
            </div>
            <div class="text-center">
                <div class="h-16 bg-accent-600 rounded-lg mb-2"></div>
                <p class="text-sm font-semibold">Accent</p>
            </div>
            <div class="text-center">
                <div class="h-16 bg-success-600 rounded-lg mb-2"></div>
                <p class="text-sm font-semibold">Success</p>
            </div>
            <div class="text-center">
                <div class="h-16 bg-warning-600 rounded-lg mb-2"></div>
                <p class="text-sm font-semibold">Warning</p>
            </div>
            <div class="text-center">
                <div class="h-16 bg-danger-600 rounded-lg mb-2"></div>
                <p class="text-sm font-semibold">Danger</p>
            </div>
            <div class="text-center">
                <div class="h-16 bg-info-600 rounded-lg mb-2"></div>
                <p class="text-sm font-semibold">Info</p>
            </div>
        </div>
    </section>

    <!-- Glassmorphism Feature -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">🔮 Glassmorphism</h2>
        <div class="relative h-64 rounded-2xl overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-600"></div>
            <div class="absolute inset-0 dashboard-card backdrop-blur-xl bg-white/40 flex items-center justify-center">
                <p class="text-center">
                    <span class="text-3xl font-bold text-white">Premium Glass Effect</span>
                    <br>
                    <span class="text-white/80">Modern, Professional, "Ice on Fire" Design</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Tips Section -->
    <section class="mb-16 animate-slideUp">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">💡 Usage Tips</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card-gradient">
                <h3 class="font-bold text-primary-900 mb-2">Animations</h3>
                <p class="text-primary-700 text-sm">Use animate-* classes for smooth entrance effects (fadeIn, slideUp, scaleIn)</p>
            </div>
            <div class="card-success">
                <h3 class="font-bold text-success-900 mb-2">Hover Effects</h3>
                <p class="text-success-700 text-sm">dashboard-card-hover adds lift effect. Use group-hover for icon scaling</p>
            </div>
            <div class="card-accent">
                <h3 class="font-bold text-accent-900 mb-2">Forms</h3>
                <p class="text-accent-700 text-sm">input-field provides focus ring, shadow, and smooth transitions</p>
            </div>
            <div class="dashboard-card">
                <h3 class="font-bold text-gray-900 mb-2">Colors</h3>
                <p class="text-gray-600 text-sm">Six-color system: Primary, Accent, Success, Warning, Danger, Info</p>
            </div>
        </div>
    </section>
</div>
@endsection
