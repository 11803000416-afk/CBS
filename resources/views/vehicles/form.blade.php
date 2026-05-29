@extends('layouts.app')

@section('title', $vehicle->exists ? 'Edit Vehicle' : ($isUserListing ?? false ? 'List Your Car for Sale' : 'Add Vehicle'))
@section('subtitle', $vehicle->exists ? 'Update vehicle information' : ($isUserListing ?? false ? 'Sell or exchange your car' : 'Add new vehicle to inventory'))

@section('content')
<!-- Page Header with Unified Navigation -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle->exists ? 'Edit Vehicle' : ($isUserListing ?? false ? 'List Your Car for Sale' : 'Add Vehicle') }}</h1>
            <p class="text-gray-600 mt-2">{{ $vehicle->exists ? 'Update vehicle information' : ($isUserListing ?? false ? 'Sell or exchange your car' : 'Add new vehicle to inventory') }}</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012 2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Manage Vehicles
                </a>
            @endif
            @if(auth()->check() && !auth()->user()->hasRole(['admin','broker']))
                <a href="{{ route('my-vehicles.index') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    My Listings
                </a>
            @endif
        </div>
    </div>
</div>
<!-- Validation Errors Display -->
@if (isset($errors) && $errors->any())
    <div class="bg-red-50 border-2 border-red-300 p-6 mb-6 rounded-xl shadow-sm">
        <div class="flex items-start gap-4">
            <svg class="w-6 h-6 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h3 class="font-bold text-red-800 mb-2 text-lg">Please correct the following errors:</h3>
                <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li class="ml-2">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

@php
    if ($vehicle->exists) {
        $formAction = ($isUserListing ?? false) ? route('my-vehicles.update', $vehicle) : route('vehicles.update', $vehicle);
        $formMethod = 'PUT';
    } else {
        $formAction = ($isUserListing ?? false) ? route('my-vehicles.store') : route('vehicles.store');
        $formMethod = 'POST';
    }
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="max-w-6xl mx-auto">
    @csrf
    @if($vehicle->exists)
        @method('PUT')
    @endif

    <!-- Basic Information Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 sm:px-8 py-5 border-b-4 border-blue-800">
            <h3 class="font-bold text-white text-lg flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Vehicle Information
            </h3>
        </div>
        
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            @if(!($isUserListing ?? false))
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Seller *</label>
                @if($sellers->count() > 0)
                    <select name="seller_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('seller_id') border-red-500 @enderror" required>
                        <option value="" class="text-gray-500">Select seller</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}" @selected(old('seller_id', $vehicle->seller_id) == $seller->id) class="text-gray-900">{{ $seller->name }}</option>
                        @endforeach
                    </select>
                    @error('seller_id')
                        <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                @else
                    <div class="bg-amber-50 border-2 border-amber-300 rounded-lg p-4 text-amber-700 text-sm font-medium">
                        <p>No active sellers available. Please create a seller first.</p>
                    </div>
                @endif
            </div>
            @else
            <div class="md:col-span-2 bg-blue-50 border-2 border-blue-300 rounded-lg p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold text-blue-900 text-base">Your Listing</p>
                        <p class="text-sm text-blue-800 mt-1">This vehicle will be listed under your name: <strong class="font-semibold">{{ auth()->user()->name }}</strong></p>
                    </div>
                </div>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Brand *</label>
                <input name="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('brand') border-red-500 @enderror" placeholder="e.g. Toyota, BMW, Honda" required>
                @error('brand')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Model *</label>
                <input name="model" value="{{ old('model', $vehicle->model) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('model') border-red-500 @enderror" placeholder="e.g. Civic, 3 Series, Corolla" required>
                @error('model')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Year *</label>
                <input name="year" type="number" value="{{ old('year', $vehicle->year) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('year') border-red-500 @enderror" placeholder="2024" required>
                @error('year')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Mileage (km) *</label>
                <input name="mileage" type="number" value="{{ old('mileage', $vehicle->mileage) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('mileage') border-red-500 @enderror" placeholder="50000" required>
                @error('mileage')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Price (Nu.) *</label>
                <input name="price" type="number" step="0.01" value="{{ old('price', $vehicle->price) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('price') border-red-500 @enderror" placeholder="500000" required>
                @error('price')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Status *</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('status') border-red-500 @enderror" required>
                    @if($isUserListing ?? false)
                        <option value="available" @selected(old('status', $vehicle->status ?: 'available') === 'available') class="text-gray-900">Available for Sale/Exchange</option>
                        <option value="reserved" @selected(old('status', $vehicle->status) === 'reserved') class="text-gray-900">Reserved</option>
                        <option value="sold" @selected(old('status', $vehicle->status) === 'sold') class="text-gray-900">Sold</option>
                    @else
                        @foreach(['available', 'reserved', 'sold'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $vehicle->status ?: 'available') === $status) class="text-gray-900">{{ ucfirst($status) }}</option>
                        @endforeach
                    @endif
                </select>
                @error('status')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
                @if($isUserListing ?? false)
                    <p class="text-xs text-gray-600 mt-2 italic">Set to "Available" to list for sale or exchange</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Transmission</label>
                <select name="transmission" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('transmission') border-red-500 @enderror">
                    <option value="">Select transmission type</option>
                    <option value="Manual" @selected(old('transmission', $vehicle->transmission) === 'Manual') class="text-gray-900">Manual</option>
                    <option value="Automatic" @selected(old('transmission', $vehicle->transmission) === 'Automatic') class="text-gray-900">Automatic</option>
                    <option value="Clutchless Manual" @selected(old('transmission', $vehicle->transmission) === 'Clutchless Manual') class="text-gray-900">Clutchless Manual</option>
                </select>
                @error('transmission')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Fuel Type</label>
                <select name="fuel_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('fuel_type') border-red-500 @enderror">
                    <option value="">Select fuel type</option>
                    <option value="Petrol" @selected(old('fuel_type', $vehicle->fuel_type) === 'Petrol') class="text-gray-900">Petrol</option>
                    <option value="Diesel" @selected(old('fuel_type', $vehicle->fuel_type) === 'Diesel') class="text-gray-900">Diesel</option>
                    <option value="Electric" @selected(old('fuel_type', $vehicle->fuel_type) === 'Electric') class="text-gray-900">Electric</option>
                    <option value="Hybrid" @selected(old('fuel_type', $vehicle->fuel_type) === 'Hybrid') class="text-gray-900">Hybrid</option>
                    <option value="CNG" @selected(old('fuel_type', $vehicle->fuel_type) === 'CNG') class="text-gray-900">CNG</option>
                    <option value="LPG" @selected(old('fuel_type', $vehicle->fuel_type) === 'LPG') class="text-gray-900">LPG</option>
                </select>
                @error('fuel_type')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2.5">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm @error('description') border-red-500 @enderror" placeholder="Enter detailed description of the vehicle...">{{ old('description', $vehicle->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Image Upload Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 sm:px-8 py-5 border-b-4 border-purple-800">
            <h3 class="font-bold text-white text-lg flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Vehicle Images (Multiple Upload)
            </h3>
        </div>        
        <div class="p-6 sm:p-8">
            @error('images')<p class="text-red-600 text-sm font-medium mb-3">{{ $message }}</p>@enderror
            @error('images.*')<p class="text-red-600 text-sm font-medium mb-3">{{ $message }}</p>@enderror
            
            <!-- Existing Images Preview (for edit mode) -->
            @if($vehicle->exists && $vehicle->images && count($vehicle->images) > 0)
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700">Current Images:</h4>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full font-medium">{{ count($vehicle->images) }} uploaded</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3" id="existingImagesContainer">
                        @foreach($vehicle->images as $index => $image)
                            <div class="relative group" data-image-index="{{ $index }}">
                                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 group-hover:border-red-400 transition-colors h-32">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Vehicle image" class="w-full h-full object-cover group-hover:brightness-75 transition-all" loading="lazy" decoding="async">
                                </div>
                                <button type="button" onclick="removeExistingImage('{{ $index }}', '{{ $image }}')" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-all shadow-lg transform scale-0 group-hover:scale-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <p class="text-xs text-gray-600 mt-2 text-center">Image {{ $index + 1 }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- File Upload Input -->
            <div 
                class="border-3 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-purple-500 hover:bg-purple-50 transition-all cursor-pointer bg-gray-50" 
                onclick="document.getElementById('imageInput').click()"
                id="dropZone">
                <input id="imageInput" name="images[]" type="file" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/bmp,image/webp,image/avif,image/heic,image/heif,image/tiff,image/tif" class="hidden" onchange="previewImages(event)">
                <div class="space-y-3">
                    <div class="flex justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-base">Click to upload images or drag and drop</p>
                        <p class="text-xs text-gray-600 mt-2">Supported formats: PNG, JPG, JPEG, GIF, BMP, WebP, AVIF, HEIC, HEIF, TIFF, TIF (Max 2MB per image)</p>
                        <p class="text-xs text-gray-500 mt-2 font-medium">You can upload multiple images at once</p>
                    </div>
                </div>
            </div>

            <!-- New Images Preview -->
            <div id="imagePreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mt-6 hidden"></div>
            
            <!-- Upload stats -->
            <div id="uploadStats" class="mt-4 text-xs text-gray-600 hidden">
                <p>Files selected: <span id="fileCount" class="font-semibold text-gray-800">0</span> | Total size: <span id="totalSize" class="font-semibold text-gray-800">0 MB</span></p>
            </div>
        </div>
    </div>

    <!-- Video Upload Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 sm:px-8 py-5 border-b-4 border-blue-800">
            <h3 class="font-bold text-white text-lg flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Vehicle Videos (Multiple Upload)
            </h3>
        </div>        
        <div class="p-6 sm:p-8">
            @error('videos')<p class="text-red-600 text-sm font-medium mb-3">{{ $message }}</p>@enderror
            @error('videos.*')<p class="text-red-600 text-sm font-medium mb-3">{{ $message }}</p>@enderror
            
            <!-- Existing Videos Preview (for edit mode) -->
            @if($vehicle->exists && $vehicle->videos && count($vehicle->videos) > 0)
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700">Current Videos:</h4>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full font-medium">{{ count($vehicle->videos) }} uploaded</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3" id="existingVideosContainer">
                        @foreach($vehicle->videos as $index => $video)
                            <div class="relative group" data-video-index="{{ $index }}">
                                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 group-hover:border-red-400 transition-colors h-32 bg-gray-900 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 100-16 8 8 0 000 16z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <button type="button" onclick="removeExistingVideo('{{ $index }}', '{{ $video }}')" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-all shadow-lg transform scale-0 group-hover:scale-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <p class="text-xs text-gray-600 mt-2 text-center truncate">Video {{ $index + 1 }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- File Upload Input -->
            <div 
                class="border-3 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer bg-gray-50" 
                onclick="document.getElementById('videoInput').click()"
                id="videoDropZone">
                <input id="videoInput" name="videos[]" type="file" multiple accept="video/mp4,video/mpeg,video/quicktime,video/x-msvideo,video/webm,video/x-matroska,video/x-flv,video/x-ms-wmv,video/x-m4v" class="hidden" onchange="previewVideos(event)">
                <div class="space-y-3">
                    <div class="flex justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold text-base">Click to upload videos or drag and drop</p>
                        <p class="text-xs text-gray-600 mt-2">Supported formats: MP4, MPEG, MOV, AVI, WebM, MKV, FLV, WMV, M4V (Max 100MB per video)</p>
                        <p class="text-xs text-gray-500 mt-2 font-medium">You can upload multiple videos at once</p>
                    </div>
                </div>
            </div>

            <!-- New Videos Preview -->
            <div id="videoPreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mt-6 hidden"></div>
            
            <!-- Upload stats -->
            <div id="videoUploadStats" class="mt-4 text-xs text-gray-600 hidden">
                <p>Files selected: <span id="videoFileCount" class="font-semibold text-gray-800">0</span> | Total size: <span id="videoTotalSize" class="font-semibold text-gray-800">0 MB</span></p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 sm:px-8 py-4 border-t-4 border-gray-200 flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg transition-all font-bold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $vehicle->exists ? 'Update Vehicle' : ($isUserListing ?? false ? 'List My Car' : 'Save Vehicle') }}
            </button>
            <a href="{{ ($isUserListing ?? false) ? route('my-vehicles.index') : route('vehicles.index') }}" class="flex-1 px-6 py-3 rounded-lg border-2 border-gray-300 hover:bg-gray-100 transition font-bold text-gray-700 text-center">Cancel</a>
        </div>
    </div>
</form>

<script>
let selectedFiles = [];
let selectedVideoFiles = [];

function previewImages(event) {
    const files = Array.from(event.target.files);
    const container = document.getElementById('imagePreviewContainer');
    const statsDiv = document.getElementById('uploadStats');
    
    if (files.length === 0) {
        container.classList.add('hidden');
        statsDiv.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');
    statsDiv.classList.remove('hidden');
    container.innerHTML = '';
    selectedFiles = files;
    
    // Calculate and display stats
    let totalSize = 0;
    files.forEach(file => {
        totalSize += file.size;
    });
    
    document.getElementById('fileCount').textContent = files.length;
    document.getElementById('totalSize').textContent = (totalSize / (1024 * 1024)).toFixed(2);
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 group-hover:border-red-400 transition-colors h-32">
                    <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover group-hover:brightness-75 transition-all" decoding="async">
                </div>
                <button type="button" onclick="removeNewImage(${index})" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-all shadow-lg transform scale-0 group-hover:scale-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <p class="text-xs text-gray-600 mt-2 text-center truncate">${file.name}</p>
            `;
            container.appendChild(div);
        };
        
        reader.readAsDataURL(file);
    });
}

function previewVideos(event) {
    const files = Array.from(event.target.files);
    const container = document.getElementById('videoPreviewContainer');
    const statsDiv = document.getElementById('videoUploadStats');
    
    if (files.length === 0) {
        container.classList.add('hidden');
        statsDiv.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');
    statsDiv.classList.remove('hidden');
    container.innerHTML = '';
    selectedVideoFiles = files;
    
    // Calculate and display stats
    let totalSize = 0;
    files.forEach(file => {
        totalSize += file.size;
    });
    
    document.getElementById('videoFileCount').textContent = files.length;
    document.getElementById('videoTotalSize').textContent = (totalSize / (1024 * 1024)).toFixed(2);
    
    files.forEach((file, index) => {
        const div = document.createElement('div');
        div.className = 'relative group';
        div.innerHTML = `
            <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 group-hover:border-red-400 transition-colors h-32 bg-gray-900 flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 100-16 8 8 0 000 16z" clip-rule="evenodd"/>
                </svg>
            </div>
            <button type="button" onclick="removeNewVideo(${index})" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-all shadow-lg transform scale-0 group-hover:scale-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <p class="text-xs text-gray-600 mt-2 text-center truncate">${file.name}</p>
        `;
        container.appendChild(div);
    });
}

function removeNewImage(index) {
    selectedFiles.splice(index, 1);
    
    // Create a new FileList from remaining files
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('imageInput').files = dt.files;
    
    // Trigger preview update
    const event = { target: { files: dt.files } };
    previewImages(event);
}

function removeNewVideo(index) {
    selectedVideoFiles.splice(index, 1);
    
    // Create a new FileList from remaining files
    const dt = new DataTransfer();
    selectedVideoFiles.forEach(file => dt.items.add(file));
    document.getElementById('videoInput').files = dt.files;
    
    // Trigger preview update
    const event = { target: { files: dt.files } };
    previewVideos(event);
}

function removeExistingImage(index, imagePath) {
    if (confirm('Are you sure you want to remove this image?')) {
        const container = document.getElementById('existingImagesContainer');
        const imageDiv = container.querySelector(`[data-image-index="${index}"]`);
        if (imageDiv) {
            imageDiv.remove();
        }
        
        // Add hidden input to track removed images by path
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'removed_images[]';
        input.value = imagePath;
        document.querySelector('form').appendChild(input);
    }
}

function removeExistingVideo(index, videoPath) {
    if (confirm('Are you sure you want to remove this video?')) {
        const container = document.getElementById('existingVideosContainer');
        const videoDiv = container.querySelector(`[data-video-index="${index}"]`);
        if (videoDiv) {
            videoDiv.remove();
        }
        
        // Add hidden input to track removed videos by path
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'removed_videos[]';
        input.value = videoPath;
        document.querySelector('form').appendChild(input);
    }
}

// Enhanced drag and drop functionality
const dropZone = document.getElementById('dropZone');
if (dropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-purple-500', 'bg-purple-100');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-purple-500', 'bg-purple-100');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('imageInput').files = files;
        previewImages({ target: { files: files } });
    }, false);
}

// Enhanced drag and drop functionality for videos
const videoDropZone = document.getElementById('videoDropZone');
if (videoDropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        videoDropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        videoDropZone.addEventListener(eventName, () => {
            videoDropZone.classList.add('border-blue-500', 'bg-blue-100');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        videoDropZone.addEventListener(eventName, () => {
            videoDropZone.classList.remove('border-blue-500', 'bg-blue-100');
        }, false);
    });

    videoDropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('videoInput').files = files;
        previewVideos({ target: { files: files } });
    }, false);
}
</script>
@endsection
