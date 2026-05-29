@extends('layouts.app-pro')

@section('title', 'Welcome to CBS')
@section('subtitle', 'Professional Car Broker System')

@section('content')

<!-- Hero/Search Section -->
<div class="mb-16 bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-3xl p-8 md:p-16 border border-blue-200 shadow-lg">
    <div class="max-w-4xl mx-auto text-center mb-8">
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-4 leading-tight">
            Find Your <span class="gradient-text">Perfect Car</span>
        </h1>
        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            Browse thousands of carefully curated vehicles and connect directly with sellers
        </p>
        
        <!-- Search Bar -->
        <div class="flex gap-3 flex-col sm:flex-row">
            <input type="text" placeholder="Search by brand, model, price..." 
                   class="flex-1 px-6 py-4 rounded-xl border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition text-lg shadow-sm">
            <button class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl transition shadow-lg hover:shadow-xl text-lg">
                Search
            </button>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('valuation.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white text-blue-700 font-bold border-2 border-blue-200 hover:border-blue-400 hover:shadow-lg transition">
                Used Car Valuation
            </a>
            <p class="text-sm text-gray-500">Estimate selling price with 0.5% admin commission.</p>
        </div>
    </div>
</div>

<!-- Browse by Brand Section -->
<div class="mb-16">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Browse by Brand</h2>
            <p class="text-gray-600 text-lg">Explore our extensive collection of vehicles</p>
        </div>
        <a href="{{ route('vehicles.browse') }}" class="hidden md:flex text-blue-600 hover:text-blue-700 font-semibold items-center gap-2 hover:gap-3 transition-all">
            View All Brands
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- Brand Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        @php
            $brands = [
                ['name' => 'Maruti Suzuki', 'icon' => '🎯', 'color' => 'blue'],
                ['name' => 'Hyundai', 'icon' => '🏎️', 'color' => 'purple'],
                ['name' => 'Mahindra', 'icon' => '🚙', 'color' => 'emerald'],
                ['name' => 'Tata', 'icon' => '⭐', 'color' => 'indigo'],
                ['name' => 'Honda', 'icon' => '🏆', 'color' => 'red'],
                ['name' => 'Toyota', 'icon' => '🚗', 'color' => 'orange'],
            ];
        @endphp
        
        @foreach($brands as $brand)
            <a href="{{ route('vehicles.browse') }}" 
               class="flex flex-col items-center justify-center p-8 rounded-2xl border-2 border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50 hover:shadow-lg transition-all duration-300 group">
                <div class="text-5xl mb-4 group-hover:scale-125 transition-transform duration-300">{{ $brand['icon'] }}</div>
                <div class="text-center">
                    <p class="font-bold text-gray-900 text-sm">{{ $brand['name'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Why CBS Section - Trust Indicators -->
<div class="mb-16">
    <h2 class="text-4xl font-bold text-gray-900 mb-16 text-center">Why Choose CBS?</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-200 hover:shadow-lg hover:border-blue-300 transition-all group">
            <div class="w-16 h-16 mb-6 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform">👥</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">10,000+ Users</h3>
            <p class="text-gray-600 text-lg leading-relaxed">Trusted by thousands of satisfied sellers and buyers in our community</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-200 hover:shadow-lg hover:border-green-300 transition-all group">
            <div class="w-16 h-16 mb-6 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform">🔒</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">100% Secure</h3>
            <p class="text-gray-600 text-lg leading-relaxed">Your data is protected with enterprise-level encryption and security protocols</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-200 hover:shadow-lg hover:border-orange-300 transition-all group">
            <div class="w-16 h-16 mb-6 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform">⚡</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Fast & Easy</h3>
            <p class="text-gray-600 text-lg leading-relaxed">List your car in just 2 simple steps and reach buyers within minutes</p>
        </div>
    </div>
</div>

<!-- Sell Your Car CTA Section -->
<div class="relative mb-16 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 rounded-3xl overflow-hidden shadow-xl">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative z-10 px-8 md:px-16 py-20 text-white">
        <div class="max-w-3xl">
            <h2 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">Ready to Sell Your Car?</h2>
            <p class="text-xl text-blue-100 mb-12 leading-relaxed">Get the best price with our simple 2-step process. Connect with serious buyers directly and close deals faster.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/20 border-2 border-white flex items-center justify-center text-white font-bold text-lg">1</div>
                    <div>
                        <h4 class="font-bold text-lg mb-1">Post Your Ad</h4>
                        <p class="text-blue-100">Your car listing goes live to thousands of buyers instantly</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/20 border-2 border-white flex items-center justify-center text-white font-bold text-lg">2</div>
                    <div>
                        <h4 class="font-bold text-lg mb-1">Sell Your Car</h4>
                        <p class="text-blue-100">Connect with serious buyers and get the best price</p>
                    </div>
                </div>
            </div>
            
            @auth
                <a href="{{ route('my-vehicles.create') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-blue-700 font-bold rounded-xl hover:bg-gray-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                    Start Selling Now
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-blue-700 font-bold rounded-xl hover:bg-gray-50 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                    Create Account to Sell
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Featured Vehicles Section -->
<div class="mb-16">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Featured Vehicles</h2>
            <p class="text-gray-600 text-lg">Premium collection of the best vehicles available</p>
        </div>
        <a href="{{ route('vehicles.browse') }}" class="hidden md:flex text-blue-600 hover:text-blue-700 font-semibold items-center gap-2 hover:gap-3 transition-all">
            View All Listings
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if(($featuredVehicles ?? collect())->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(($featuredVehicles ?? collect()) as $vehicle)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl hover:border-blue-300 transition-all overflow-hidden group card-hover">
                    <!-- Image -->
                    <div class="relative h-64 bg-gray-200 overflow-hidden">
                        @if($vehicle->images && count($vehicle->images) > 0)
                            <img src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">No Image</span>
                            </div>
                        @endif
                        
                        <!-- Rating -->
                        <div class="absolute top-4 right-4 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg font-bold">
                            5.0
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ $vehicle->year }} • {{ $vehicle->mileage ?? 0 }} km</p>

                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <div>
                                <p class="text-blue-600 text-2xl font-bold">Nu. {{ number_format($vehicle->price) }}</p>
                            </div>
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                ✓ Available
                            </span>
                        </div>

                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-center block">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <p class="text-gray-600 text-lg">No vehicles available at the moment</p>
        </div>
    @endif
</div>

<!-- FAQ Section -->
<div class="mb-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Frequently Asked Questions</h2>
    
    <div class="max-w-4xl mx-auto space-y-4">
        <!-- FAQ 1 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button onclick="toggleFAQ(this)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <h3 class="text-lg font-semibold text-gray-900 text-left">Why should I sell car on CBS?</h3>
                <svg class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </button>
            <div class="hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-t text-gray-600">
                    <p>CBS provides a secure platform to sell your car to thousands of buyers. You get the best price possible by reaching a wide audience, and our platform ensures verified transactions with complete evidence documentation.</p>
                </div>
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button onclick="toggleFAQ(this)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <h3 class="text-lg font-semibold text-gray-900 text-left">Is CBS the best site to sell car?</h3>
                <svg class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </button>
            <div class="hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-t text-gray-600">
                    <p>Yes! CBS is specifically designed for the local market with features tailored to seller needs. We offer simple listing, verified buyers, secure transactions, and professional support throughout the selling process.</p>
                </div>
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button onclick="toggleFAQ(this)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <h3 class="text-lg font-semibold text-gray-900 text-left">How can I sell car to CBS?</h3>
                <svg class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </button>
            <div class="hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-t text-gray-600">
                    <p>Step 1: Create an account on CBS<br>
                    Step 2: Click "List Your Car" and fill in the details<br>
                    Step 3: Upload quality photos and videos<br>
                    Step 4: Set your asking price<br>
                    Step 5: Your listing goes live instantly to all buyers</p>
                </div>
            </div>
        </div>

        <!-- FAQ 4 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button onclick="toggleFAQ(this)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <h3 class="text-lg font-semibold text-gray-900 text-left">What documents are required?</h3>
                <svg class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </button>
            <div class="hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-t text-gray-600">
                    <p>• Ownership Certificate<br>
                    • Identity Proof<br>
                    • Vehicle Registration<br>
                    • Insurance Details<br>
                    • (Optional) Service Records<br>
                    • (Optional) Inspection Report</p>
                </div>
            </div>
        </div>

        <!-- FAQ 5 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button onclick="toggleFAQ(this)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <h3 class="text-lg font-semibold text-gray-900 text-left">How long does it take to sell?</h3>
                <svg class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </button>
            <div class="hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-t text-gray-600">
                    <p>The time to sell depends on various factors like vehicle condition, price competitiveness, and market demand. On average, most cars are sold within 2-4 weeks of listing. Your car goes live immediately after certification.</p>
                </div>
            </div>
        </div>

        <!-- FAQ 6 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button onclick="toggleFAQ(this)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                <h3 class="text-lg font-semibold text-gray-900 text-left">Is my data safe on CBS?</h3>
                <svg class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </button>
            <div class="hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-6 py-4 border-t text-gray-600">
                    <p>Absolutely! We use enterprise-grade encryption to protect your personal and financial information. All transactions are verified and documented. Your data is never shared with third parties without your consent.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const svg = button.querySelector('svg');
    
    // Close other FAQs
    document.querySelectorAll('.bg-white.rounded-xl').forEach(item => {
        if (item !== button.parentElement) {
            item.querySelector('div:not(button)').classList.add('hidden');
            item.querySelector('svg').classList.remove('rotate-180');
        }
    });
    
    // Toggle current FAQ
    content.classList.toggle('hidden');
    svg.classList.toggle('rotate-180');
    
    if (!content.classList.contains('hidden')) {
        content.style.maxHeight = content.scrollHeight + 'px';
    } else {
        content.style.maxHeight = '0';
    }
}
</script>

@endsection
