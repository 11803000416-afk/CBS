@extends('layouts.app')

@section('title', 'Add Vehicle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add New Vehicle</h1>
                <p class="text-gray-600 mt-2">List your vehicle for sale or management</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('vehicles.unified') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Vehicles
                </a>
                @if(auth()->check() && auth()->user()->hasRole(['admin','broker']))
                    <a href="{{ route('vehicles.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Manage Vehicles
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Vehicle Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand *</label>
                    <input type="text" name="brand" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g. Toyota, BMW">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                    <input type="text" name="model" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g. Corolla, 3 Series">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                    <input type="number" name="year" required min="1900" max="{{ date('Y') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="2024">
                    @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (Nu.) *</label>
                    <input type="number" name="price" required min="0" step="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="500000">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Type</label>
                    <select name="fuel_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Fuel Type</option>
                        <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                        <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    @error('fuel_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select name="transmission" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                        <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="CVT" {{ old('transmission') == 'CVT' ? 'selected' : '' }}>CVT</option>
                    </select>
                    @error('transmission')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="text" name="color"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g. White, Black, Silver"
                           value="{{ old('color') }}">
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engine Capacity (cc)</label>
                    <input type="number" name="engine_capacity" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1800"
                           value="{{ old('engine_capacity') }}">
                    @error('engine_capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                    <select name="condition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Excellent" {{ old('condition') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                        <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('condition')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mileage</label>
                    <input type="number" name="mileage" required min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="50000"
                           value="{{ old('mileage') }}">
                    @error('mileage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Describe your vehicle's features, condition, and any special details...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Images Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Images</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-gray-600 mb-2">Click to upload or drag and drop</p>
                    <p class="text-sm text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="image-upload">
                    <button type="button" onclick="document.getElementById('image-upload').click()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Choose Files
                    </button>
                </div>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Add Vehicle
                </button>
                <a href="{{ route('vehicles.unified') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Image Preview Script -->
<script>
    document.getElementById('image-upload').addEventListener('change', function(e) {
        const files = e.target.files;
        const preview = document.createElement('div');
        preview.className = 'mt-4 grid grid-cols-2 md:grid-cols-4 gap-4';
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
        
        const existingPreview = document.querySelector('.grid');
        if (existingPreview) {
            existingPreview.parentNode.removeChild(existingPreview);
        }
        
        e.target.parentNode.appendChild(preview);
    });
</script>
@endsection
