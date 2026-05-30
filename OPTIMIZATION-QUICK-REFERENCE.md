# ⚡ CBS Optimization - Daily Reference Guide

## Quick Copy-Paste Code Snippets

### Common Patterns You'll Use Every Day

---

## 🎯 BUTTONS

### Primary Button
```blade
@include('components.button-optimized', [
    'text' => 'Save',
    'variant' => 'primary',
    'onclick' => 'saveForm()'
])
```

### Success Button
```blade
@include('components.button-optimized', [
    'text' => 'Publish',
    'variant' => 'success'
])
```

### Danger Button
```blade
@include('components.button-optimized', [
    'text' => 'Delete',
    'variant' => 'danger',
    'onclick' => 'if(!confirm("Sure?")) return false;'
])
```

### Button as Link
```blade
@include('components.button-optimized', [
    'text' => 'View Vehicle',
    'href' => route('vehicles.show', $vehicle->id),
    'variant' => 'outline'
])
```

### Loading Button
```blade
<button id="submitBtn" onclick="submitForm()" class="btn btn-primary">
    <span class="btn-text">Submit</span>
    <div class="spinner" style="display: none; width: 1rem; height: 1rem;"></div>
</button>

<script>
function submitForm() {
    const btn = document.getElementById('submitBtn');
    btn.classList.add('is-loading');
    btn.disabled = true;
    // Submit form...
}
</script>
```

---

## 📝 FORMS

### Text Input
```blade
@include('components.form-input-optimized', [
    'name' => 'full_name',
    'label' => 'Full Name',
    'required' => true,
    'hint' => 'Enter your full legal name'
])
```

### Email Input
```blade
@include('components.form-input-optimized', [
    'name' => 'email',
    'label' => 'Email Address',
    'type' => 'email',
    'required' => true
])
```

### Dropdown Select
```blade
@include('components.form-input-optimized', [
    'name' => 'brand',
    'label' => 'Vehicle Brand',
    'type' => 'select',
    'options' => ['toyota' => 'Toyota', 'bmw' => 'BMW', 'honda' => 'Honda'],
    'required' => true
])
```

### Textarea
```blade
@include('components.form-input-optimized', [
    'name' => 'description',
    'label' => 'Description',
    'type' => 'textarea',
    'rows' => 5,
    'placeholder' => 'Enter details...'
])
```

### Form with Validation Error
```blade
<!-- Errors automatically show from $errors bag -->
@include('components.form-input-optimized', [
    'name' => 'phone',
    'label' => 'Phone Number',
    'type' => 'tel',
    'required' => true
])
<!-- If validation fails, error automatically displays -->
```

### Complete Form
```blade
<form method="POST" action="{{ route('vehicles.store') }}">
    @csrf

    @include('components.form-input-optimized', [
        'name' => 'brand',
        'label' => 'Brand',
        'required' => true
    ])

    @include('components.form-input-optimized', [
        'name' => 'price',
        'label' => 'Price',
        'type' => 'number',
        'required' => true
    ])

    @include('components.form-input-optimized', [
        'name' => 'description',
        'label' => 'Description',
        'type' => 'textarea'
    ])

    @include('components.button-optimized', [
        'text' => 'Add Vehicle',
        'type' => 'submit',
        'variant' => 'primary'
    ])
</form>
```

---

## 🔔 ALERTS & NOTIFICATIONS

### Success Alert
```blade
@include('components.alert-optimized', [
    'message' => 'Vehicle saved successfully!',
    'type' => 'success'
])
```

### Error Alert
```blade
@include('components.alert-optimized', [
    'message' => 'Failed to save vehicle',
    'type' => 'danger'
])
```

### Warning Alert
```blade
@include('components.alert-optimized', [
    'message' => 'This action cannot be undone',
    'type' => 'warning'
])
```

### Alert with Title
```blade
@include('components.alert-optimized', [
    'title' => 'Configuration Error',
    'message' => 'Please check your settings',
    'type' => 'danger'
])
```

### Toast Notification (JavaScript)
```javascript
// Show success
showToast.success('Vehicle added!');

// Show error
showToast.error('Something went wrong');

// Show warning
showToast.warning('Please review changes');

// Show info
showToast.info('New updates available');

// With custom duration (ms)
window.Toast.show('Custom message', 'info', 3000);
```

---

## 🎨 MODALS

### Simple Modal
```blade
<!-- Modal Definition -->
@include('components.modal-optimized', [
    'id' => 'confirmDelete',
    'title' => 'Confirm Delete',
    'message' => 'Are you sure you want to delete this vehicle?'
])

<!-- Trigger Button -->
@include('components.button-optimized', [
    'text' => 'Delete',
    'data-modal-open' => '#confirmDelete-backdrop',
    'variant' => 'danger'
])
```

### Modal with Actions
```blade
@include('components.modal-optimized', [
    'id' => 'editVehicle',
    'title' => 'Edit Vehicle',
    'size' => 'lg',
    'slot' => 'Your form content here',
    'actions' => '
        <button class="btn btn-secondary" data-modal-close="#editVehicle">Cancel</button>
        <button class="btn btn-primary" onclick="saveVehicle()">Save</button>
    '
])
```

### Open Modal from JavaScript
```javascript
// Open a modal
Modal.open('#myModal');

// Close a modal
Modal.close('#myModal');
```

---

## ⏳ LOADING & SPINNERS

### Loading Spinner
```blade
@include('components.spinner-optimized', [
    'size' => 'md',
    'message' => 'Loading vehicles...'
])
```

### Large Spinner
```blade
@include('components.spinner-optimized', [
    'size' => 'lg',
    'message' => 'Processing...'
])
```

### Show Spinner While Loading
```blade
<div id="loadingSpinner" style="display: none;">
    @include('components.spinner-optimized', [
        'message' => 'Loading...'
    ])
</div>

<div id="content">
    <!-- Content here -->
</div>

<script>
function loadVehicles() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('content').style.display = 'none';
    
    fetch('/api/vehicles')
        .then(r => r.json())
        .then(data => {
            showVehicles(data);
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        });
}
</script>
```

---

## 📱 RESPONSIVE GRIDS

### 1-2-4 Column Grid
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($vehicles as $vehicle)
        <div class="card">
            <h3>{{ $vehicle->name }}</h3>
            <p>{{ $vehicle->price }}</p>
        </div>
    @endforeach
</div>
```

### 2 Column Grid
```blade
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($items as $item)
        <div>{{ $item->name }}</div>
    @endforeach
</div>
```

### Responsive Images
```blade
<img 
    srcset="vehicle-320.jpg 320w, vehicle-640.jpg 640w, vehicle-1024.jpg 1024w"
    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
    src="vehicle.jpg"
    alt="Vehicle"
    loading="lazy">
```

---

## 🔍 PERFORMANCE

### Debounced Search
```javascript
// In your JavaScript
const searchRequest = debounce((query) => {
    fetch(`/api/vehicles/search?q=${query}`)
        .then(r => r.json())
        .then(results => renderResults(results));
}, 300);

document.getElementById('searchInput')
    .addEventListener('input', (e) => {
        searchRequest(e.target.value);
    });
```

### Throttled Scroll
```javascript
const handleScroll = throttle(() => {
    // This will fire max once every 300ms
    console.log('User scrolled');
}, 300);

window.addEventListener('scroll', handleScroll);
```

### Event Delegation
```javascript
// Instead of attaching click to each button:
delegate('.vehicle-list .delete-btn', 'click', function(e) {
    const vehicleId = this.dataset.vehicleId;
    deleteVehicle(vehicleId);
});
```

### Preload Assets
```javascript
// Preload for current page
preloadAsset('/css/custom.css', 'style');
preloadAsset('/fonts/IosevkaTerm-Bold.woff2', 'font');

// Prefetch next page
prefetchUrl('/vehicles?page=2');
```

---

## ♿ ACCESSIBILITY

### Skip Link (Auto included)
```blade
<!-- Already in layout -->
<a href="#main-content" class="skip-link">
    Skip to main content
</a>

<main id="main-content">
    <!-- Your content -->
</main>
```

### Screen Reader Announcement
```javascript
// Announce to screen readers
announceToScreenReader('Vehicle saved!', 'polite');

// For urgent announcements
announceToScreenReader('Error: Please try again', 'assertive');
```

### Accessible Navigation
```blade
<nav aria-label="Main navigation">
    <a href="/homes" aria-current="page">Home</a>
    <a href="/vehicles">Vehicles</a>
    <a href="/about">About</a>
</nav>
```

### Keyboard Navigation Menu
```blade
<div role="menubar">
    <button role="menuitem">File</button>
    <button role="menuitem">Edit</button>
    <button role="menuitem">View</button>
</div>
<!-- Keyboard auto-setup: Arrow keys navigate, Enter activates -->
```

### Form with ARIA
```blade
<label for="email" class="required">Email Address</label>
<input 
    id="email"
    name="email"
    type="email"
    required
    aria-label="Email address"
    aria-required="true"
    aria-describedby="email-hint">
<p id="email-hint" class="help-text">We'll never share your email</p>
```

---

## 📊 CARDS & CONTAINERS

### Single Card
```blade
<div class="card">
    <div class="card-header">
        <h3>Vehicle Details</h3>
    </div>
    <div class="card-body">
        <p>{{ $vehicle->description }}</p>
    </div>
    <div class="card-footer">
        @include('components.button-optimized', [
            'text' => 'Edit',
            'href' => route('vehicles.edit', $vehicle),
            'size' => 'sm'
        ])
    </div>
</div>
```

### Card Grid
```blade
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($vehicles as $vehicle)
        <div class="card hover:shadow-lg transition">
            <img src="{{ $vehicle->image }}" alt="{{ $vehicle->name }}" class="w-full h-48 object-cover">
            <div class="card-body">
                <h3 class="font-bold">{{ $vehicle->name }}</h3>
                <p class="text-gray-600">{{ formatPrice($vehicle->price) }}</p>
            </div>
        </div>
    @endforeach
</div>
```

---

## 🔗 LINKS & NAVIGATION

### Accessible Link
```blade
<!-- Good: descriptive link text -->
<a href="/vehicles/123">Toyota Camry 2023</a>

<!-- Good: with aria-label -->
<a href="/vehicles" aria-label="Browse all vehicles">View all</a>

<!-- Bad: vague link text -->
<a href="/vehicles">Click here</a>
```

### Breadcrumb Navigation
```blade
<nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
        <li><a href="/">Home</a></li>
        <li><a href="/vehicles">Vehicles</a></li>
        <li aria-current="page">{{ $vehicle->name }}</li>
    </ol>
</nav>
```

---

## 📋 TABLES

### Responsive Table
```blade
<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="border-b-2 border-gray-300">
                <th class="text-left p-3">Brand</th>
                <th class="text-left p-3">Model</th>
                <th class="text-left p-3">Price</th>
                <th class="text-left p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="p-3">{{ $vehicle->brand }}</td>
                    <td class="p-3">{{ $vehicle->model }}</td>
                    <td class="p-3">{{ formatPrice($vehicle->price) }}</td>
                    <td class="p-3">
                        @include('components.button-optimized', [
                            'text' => 'Edit',
                            'href' => route('vehicles.edit', $vehicle),
                            'size' => 'sm'
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

---

## 📸 IMAGES

### Lazy Loaded Image
```blade
<img 
    loading="lazy"
    src="vehicle.jpg"
    alt="Vehicle description">
```

### Responsive Image with Srcset
```blade
<img
    srcset="
        vehicle-small.jpg 320w,
        vehicle-medium.jpg 768w,
        vehicle-large.jpg 1024w
    "
    sizes="(max-width: 768px) 100vw, 50vw"
    src="vehicle-medium.jpg"
    alt="Vehicle image"
    loading="lazy">
```

### Image with Aspect Ratio
```blade
<div style="aspect-ratio: 16 / 9; overflow: hidden;">
    <img src="vehicle.jpg" alt="Vehicle" style="width: 100%; height: 100%; object-fit: cover;">
</div>
```

---

## 🎯 COMMON PATTERNS

### Loading Data from API
```blade
<div id="result"></div>

<script>
async function loadVehicles() {
    showToast.info('Loading vehicles...');
    
    try {
        const response = await fetch('/api/vehicles');
        const data = await response.json();
        
        document.getElementById('result').innerHTML = data.map(v => 
            `<div class="card"><h3>${v.name}</h3></div>`
        ).join('');
        
        showToast.success('Vehicles loaded!');
    } catch (error) {
        showToast.error('Failed to load vehicles');
        console.error(error);
    }
}

loadVehicles();
</script>
```

### Delete Confirmation
```blade
@include('components.button-optimized', [
    'text' => 'Delete',
    'onclick' => 'if(confirm("Delete this vehicle?")) deleteVehicle(123)',
    'variant' => 'danger'
])

<script>
function deleteVehicle(id) {
    fetch(`/api/vehicles/${id}`, { method: 'DELETE' })
        .then(() => {
            showToast.success('Vehicle deleted');
            location.reload();
        })
        .catch(() => showToast.error('Failed to delete'));
}
</script>
```

### Toggle Between Views
```blade
<div class="flex gap-2 mb-4">
    @include('components.button-optimized', [
        'text' => 'Grid View',
        'onclick' => 'toggleView("grid")',
        'id' => 'gridBtn'
    ])
    @include('components.button-optimized', [
        'text' => 'List View',
        'onclick' => 'toggleView("list")',
        'id' => 'listBtn'
    ])
</div>

<div id="gridView" class="grid grid-cols-4 gap-6">
    <!-- Grid layout -->
</div>
<div id="listView" style="display: none;">
    <!-- List layout -->
</div>

<script>
function toggleView(type) {
    if (type === 'grid') {
        document.getElementById('gridView').style.display = 'grid';
        document.getElementById('listView').style.display = 'none';
    } else {
        document.getElementById('gridView').style.display = 'none';
        document.getElementById('listView').style.display = 'block';
    }
}
</script>
```

---

## 🧪 TESTING QUICK CHECKS

### Does it pass accessibility?
- [ ] Tab through all controls
- [ ] All interactive elements have focus outline
- [ ] All images have alt text
- [ ] All form inputs have labels
- [ ] Screen reader announces everything

### Does it work on mobile?
- [ ] Buttons are 44x44px minimum
- [ ] Text readable without zoom
- [ ] No horizontal scrolling
- [ ] Touch targets spaced 8px+ apart
- [ ] Forms easy to fill on phone

### Is it performant?
- [ ] Page loads in < 3s
- [ ] Lighthouse score > 90
- [ ] No console errors
- [ ] Smooth scrolling
- [ ] Animations smooth at 60fps

---

## 🆘 COMMON FIXES

**Button not responsive?**
```blade
<!-- Make sure you're using the component -->
@include('components.button-optimized', ...) ✅
<!-- Not just plain HTML button -->
<button class="btn">...</button> ❌
```

**Form not validating?**
```blade
<!-- Make sure you have name attribute -->
@include('components.form-input-optimized', [
    'name' => 'email' ✅ /* Required */
])
```

**Focus outline missing?**
```css
/* Check that focus-visible is not hidden */
input:focus-visible { outline: 2px solid #0284c7; } ✅
input:focus { outline: none; } ❌ /* Don't do this */
```

**Toast not showing?**
```javascript
// Toast container auto-created on first use
showToast.success('Hello!'); ✅ /* Works */

// Make sure optimization.js is loaded
typeof Toast !== 'undefined' ✅
```

---

**Last Updated:** May 29, 2026
**Bookmark This Page!** 🔖
