@extends('layouts.app')

@section('title', $vehicle->exists ? 'Edit Vehicle' : ($isUserListing ?? false ? 'List Your Car for Sale' : 'Add Vehicle'))
@section('subtitle', $vehicle->exists ? 'Update vehicle information' : ($isUserListing ?? false ? 'Sell or exchange your car' : 'Add new vehicle to inventory'))

@section('content')
<!-- Validation Errors Display -->
@if ($errors->any())
    <div class="bg-red-500/20 border-l-4 border-red-500 p-4 mb-6 rounded-lg max-w-5xl backdrop-blur-sm">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-red-300 mb-1">Please correct the following errors:</h3>
                <ul class="list-disc list-inside space-y-1 text-sm text-red-200">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<form method="POST" action="{{ $vehicle->exists ? ($isUserListing ?? false ? route('my-vehicles.update', $vehicle) : route('vehicles.update', $vehicle)) : ($isUserListing ?? false ? route('my-vehicles.store') : route('vehicles.store')) }}" enctype="multipart/form-data" class="max-w-5xl">
    @csrf
    @if($vehicle->exists)
        @method('PUT')
    @endif

    <div class="glass-card rounded-xl overflow-hidden">
        <div class="bg-white/10 px-6 py-4 border-b border-white/10">
            <h3 class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Vehicle Information
            </h3>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(!($isUserListing ?? false))
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Seller *</label>
                <select name="seller_id" class="w-full bg-white/10 border @error('seller_id') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    <option value="" class="bg-gray-800">Select seller</option>
                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected(old('seller_id', $vehicle->seller_id) == $seller->id) class="bg-gray-800">{{ $seller->name }}</option>
                    @endforeach
                </select>
                @error('seller_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div class="md:col-span-2 bg-blue-500/20 border border-blue-400/30 rounded-lg p-4 backdrop-blur-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-300">Your Listing</p>
                        <p class="text-sm text-blue-200 mt-1">This vehicle will be listed under your name: <strong>{{ auth()->user()->name }}</strong></p>
                    </div>
                </div>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Brand *</label>
                <input name="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full bg-white/10 border @error('brand') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                @error('brand')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Model *</label>
                <input name="model" value="{{ old('model', $vehicle->model) }}" class="w-full bg-white/10 border @error('model') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                @error('model')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Year *</label>
                <input name="year" type="number" value="{{ old('year', $vehicle->year) }}" class="w-full bg-white/10 border @error('year') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                @error('year')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Mileage (km) *</label>
                <input name="mileage" type="number" value="{{ old('mileage', $vehicle->mileage) }}" class="w-full bg-white/10 border @error('mileage') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                @error('mileage')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Price (Nu.) *</label>
                <input name="price" type="number" step="0.01" value="{{ old('price', $vehicle->price) }}" class="w-full bg-white/10 border @error('price') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                @error('price')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-white/10 border @error('description') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('description', $vehicle->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Status *</label>
                <select name="status" class="w-full bg-white/10 border @error('status') border-red-500 @else border-white/20 @enderror rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                    @if($isUserListing ?? false)
                        <option value="available" @selected(old('status', $vehicle->status ?: 'available') === 'available') class="bg-gray-800">Available for Sale/Exchange</option>
                        <option value="reserved" @selected(old('status', $vehicle->status) === 'reserved') class="bg-gray-800">Reserved</option>
                        <option value="sold" @selected(old('status', $vehicle->status) === 'sold') class="bg-gray-800">Sold</option>
                    @else
                        @foreach(['available', 'reserved', 'sold'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $vehicle->status ?: 'available') === $status) class="bg-gray-800">{{ ucfirst($status) }}</option>
                        @endforeach
                    @endif
                </select>
                @error('status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
                @if($isUserListing ?? false)
                    <p class="text-xs text-gray-400 mt-1">Set to "Available" to list for sale or exchange</p>
                @endif
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Vehicle Images</label>
                @error('images')<p class="text-red-400 text-xs mb-2">{{ $message }}</p>@enderror
                @error('images.*')<p class="text-red-400 text-xs mb-2">{{ $message }}</p>@enderror
                
                <!-- Existing Images Preview (for edit mode) -->
                @if($vehicle->exists && $vehicle->images && count($vehicle->images) > 0)
                    <div class="mb-4">
                        <p class="text-sm text-gray-400 mb-2">Current Images:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="existingImagesContainer">
                            @foreach($vehicle->images as $index => $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Vehicle image" class="w-full h-32 object-cover rounded-lg border-2 border-white/20">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all rounded-lg flex items-center justify-center">
                                        <button type="button" onclick="removeExistingImage({{ $index }})" class="opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- File Upload Input -->
                <div class="border-2 border-dashed border-white/20 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer bg-white/5" onclick="document.getElementById('imageInput').click()">
                    <input id="imageInput" name="images[]" type="file" multiple accept="image/*" class="hidden" onchange="previewImages(event)">
                    <div class="space-y-2">
                        <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-gray-200 font-medium">Click to upload images</p>
                            <p class="text-xs text-gray-400 mt-1">or drag and drop</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB each (multiple files)</p>
                        </div>
                    </div>
                </div>

                <!-- New Images Preview -->
                <div id="imagePreviewContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden"></div>
            </div>
        </div>
        
        <div class="bg-white/5 px-6 py-4 border-t border-white/10 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition-all font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $vehicle->exists ? 'Update Vehicle' : ($isUserListing ?? false ? 'List My Car' : 'Save Vehicle') }}
            </button>
            <a href="{{ ($isUserListing ?? false) ? route('my-vehicles.index') : route('vehicles.index') }}" class="px-6 py-2.5 rounded-lg border border-white/20 hover:bg-white/10 transition font-medium text-gray-300">Cancel</a>
        </div>
    </div>
</form>

<script>
let selectedFiles = [];

function previewImages(event) {
    const files = Array.from(event.target.files);
    const container = document.getElementById('imagePreviewContainer');
    
    if (files.length === 0) {
        container.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');
    container.innerHTML = '';
    selectedFiles = files;
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover rounded-lg border-2 border-white/20">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all rounded-lg flex items-center justify-center">
                    <button type="button" onclick="removeNewImage(${index})" class="opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1 text-center truncate">${file.name}</p>
            `;
            container.appendChild(div);
        };
        
        reader.readAsDataURL(file);
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

function removeExistingImage(index) {
    if (confirm('Are you sure you want to remove this image?')) {
        const container = document.getElementById('existingImagesContainer');
        const imageElements = container.children;
        if (imageElements[index]) {
            imageElements[index].remove();
        }
        
        // Add hidden input to track removed images (you may need to handle this in your controller)
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'removed_images[]';
        input.value = index;
        container.appendChild(input);
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('[onclick*="imageInput"]');
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
            dropZone.classList.add('border-blue-500', 'bg-blue-500/20');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-500/20');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('imageInput').files = files;
        previewImages({ target: { files: files } });
    }, false);
}
</script>
@endsection
