# Security Audit - Critical Fixes Implementation Guide

## QUICK START: 5 Critical Fixes to Deploy Immediately

---

## Fix #1: Disable APP_DEBUG (CRITICAL)

**File:** `.env`  
**Impact:** Removes stack traces and sensitive info from errors

```env
# BEFORE ❌
APP_DEBUG=true

# AFTER ✅
APP_DEBUG=false
```

**Verify:**
```bash
php artisan config:clear
php artisan serve
# Visit error page - should show generic error, not stack trace
```

---

## Fix #2: Remove /debug-users Endpoint (CRITICAL)

**File:** `routes/web.php`  
**Lines:** 20-28  
**Impact:** Prevents user data exposure

```php
// REMOVE THIS ENTIRE BLOCK ❌
if (app()->environment('local')) {
    Route::get('/debug-users', function () {
        $users = \App\Models\User::all(['id', 'name', 'email', 'role', 'is_active']);
        return response()->json([
            'count' => $users->count(),
            'users' => $users,
            'test_password_admin' => \Illuminate\Support\Facades\Hash::check('password', 
                \App\Models\User::where('email', 'admin@cbs.bt')->first()?->password ?? ''),
        ]);
    });
}

// AFTER ✅
// Endpoint removed - use Laravel Telescope if debugging needed
```

---

## Fix #3: Enable Session Encryption (CRITICAL)

**File:** `config/session.php`  
**Line:** 49  
**Impact:** Encrypts session data on disk

```php
// BEFORE ❌
'encrypt' => false,

// AFTER ✅
'encrypt' => true,
```

**Also Update:**
```php
// Line 29 - Reduce session lifetime from 120 to 60 minutes
'lifetime' => env('SESSION_LIFETIME', 60),

// Line 30 - Sessions end on browser close
'expire_on_close' => true,
```

---

## Fix #4: Move Files to Private Storage (CRITICAL)

**File:** `app/Http/Controllers/VehicleController.php`  

### Option A: Store in Private Disk (RECOMMENDED)

```php
// BEFORE ❌
$imagePaths[] = $file->store('vehicles', 'public');
$videoPaths[] = $file->store('vehicles/videos', 'public');

// AFTER ✅
$imagePaths[] = $file->store('vehicles', 'private');
$videoPaths[] = $file->store('vehicles/videos', 'private');
```

### Create Download Routes with Authorization:

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/vehicle/{vehicle}/image/{filename}', function (Vehicle $vehicle, $filename) {
        // Verify user has access to this vehicle
        if (!canViewVehicle(auth()->user(), $vehicle)) {
            abort(403, 'Unauthorized');
        }
        
        $path = 'vehicles/' . $filename;
        if (!\Illuminate\Support\Facades\Storage::disk('private')->exists($path)) {
            abort(404, 'Image not found');
        }
        
        return \Illuminate\Support\Facades\Storage::disk('private')->download($path);
    })->name('vehicle.image.download');
    
    Route::get('/vehicle/{vehicle}/video/{filename}', function (Vehicle $vehicle, $filename) {
        // Same authorization check
        if (!canViewVehicle(auth()->user(), $vehicle)) {
            abort(403, 'Unauthorized');
        }
        
        $path = 'vehicles/videos/' . $filename;
        if (!\Illuminate\Support\Facades\Storage::disk('private')->exists($path)) {
            abort(404, 'Video not found');
        }
        
        return \Illuminate\Support\Facades\Storage::disk('private')->download($path);
    })->name('vehicle.video.download');
});

// Helper function
function canViewVehicle($user, $vehicle) {
    return $vehicle->status === 'available' 
        || $user->hasRole(['admin', 'seller'])
        || $user->id === $vehicle->created_by;
}
```

### Update Blade Template:

```blade
<!-- BEFORE ❌ -->
<img src="{{ asset('storage/' . $image) }}" alt="...">

<!-- AFTER ✅ -->
<img src="{{ route('vehicle.image.download', ['vehicle' => $vehicle->id, 'filename' => basename($image)]) }}" alt="...">
```

---

## Fix #5: Implement Exception Handler Logging (CRITICAL)

**File:** `app/Exceptions/Handler.php`

```php
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions
            Log::error('Application Exception', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'user_id' => auth()->id() ?? 'guest',
            ]);
            
            // Alert on critical errors
            if (in_array($e->getCode(), [500, 503]) || get_class($e) === 'Exception') {
                try {
                    $adminEmail = config('mail.admin_email', 'admin@cbs.local');
                    // Send alert to admin (implement AlertNotification)
                    // Notification::route('mail', $adminEmail)
                    //     ->notify(new CriticalErrorAlert($e));
                } catch (\Exception $notificationError) {
                    Log::error('Failed to send error notification', [
                        'original_error' => $e->getMessage(),
                        'notification_error' => $notificationError->getMessage(),
                    ]);
                }
            }
        });
        
        // Render JSON for API requests
        $this->shouldRenderJsonWhen(fn($request) => $request->expectsJson());
    }
    
    public function render($request, Throwable $e)
    {
        // Log 404s separately
        if ($this->isHttpException($e) && $e->getStatusCode() === 404) {
            Log::warning('Page not found', [
                'url' => $request->fullUrl(),
                'referer' => $request->referer(),
            ]);
        }
        
        return parent::render($request, $e);
    }
}
```

---

## Additional HIGH Priority Fixes

### Fix #6: Add Authorization Checks to Buyer/Seller Controllers

**File:** `app/Http/Controllers/BuyerController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuyerController extends Controller
{
    public function __construct()
    {
        // Add explicit middleware
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): View
    {
        // Additional authorization check
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Only administrators can manage buyers.');
        }
        
        $buyers = Buyer::latest('id')->paginate(10);
        return view('buyers.index', compact('buyers'));
    }
    
    // ... other methods with same authorization pattern
}
```

**Repeat for:**
- [app/Http/Controllers/SellerController.php](app/Http/Controllers/SellerController.php)
- [app/Http/Controllers/TransactionController.php](app/Http/Controllers/TransactionController.php)

---

### Fix #7: Add Rate Limiting to Login

**File:** `routes/web.php`

```php
// BEFORE ❌
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// AFTER ✅
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')  // 5 attempts per 1 minute
    ->name('login.store');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:3,1')  // 3 registrations per minute
    ->name('register.store');
```

---

### Fix #8: Fix File Upload Validation

**File:** `app/Http/Controllers/VehicleController.php`

```php
// BEFORE ❌
'images.*' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,bmp,svg,webp,avif,heic,heif,tiff,tif', 'max:2048'],
'videos.*' => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm,mkv,flv,wmv,m4v', 'max:102400'],

// AFTER ✅
'images.*' => [
    'nullable',
    'file',
    'image',  // Validates actual image file
    'mimes:jpg,jpeg,png,webp',  // Only safe image formats (no SVG)
    'max:2048',  // 2MB
    'dimensions:max_width=4000,max_height=3000',
],
'videos.*' => [
    'nullable',
    'file',
    'mimes:mp4,webm',  // Only most compatible formats
    'max:102400',  // 100MB
],

// For agreements - be more restrictive
'agreement_file' => [
    'nullable',
    'file',
    'mimes:pdf',  // PDF ONLY
    'max:5120',  // 5MB
],
```

---

### Fix #9: Add Failed Login Logging

**File:** `app/Http/Controllers/AuthController.php`

```php
public function login(Request $request): RedirectResponse
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        // Log failed attempt
        \Log::warning('Failed login attempt', [
            'email' => strtolower($request->email),
            'ip' => $request->ip(),
            'user_agent' => substr($request->userAgent(), 0, 255),
            'timestamp' => now()->toDateTimeString(),
        ]);
        
        return back()
            ->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email'));
    }

    $request->session()->regenerate(true);

    if (!Auth::user()->hasVerifiedEmail()) {
        Auth::logout();
        return redirect()->route('verification.notice');
    }

    // Log successful login
    \Log::info('User logged in', [
        'user_id' => Auth::id(),
        'email' => Auth::user()->email,
        'ip' => $request->ip(),
    ]);

    return redirect()->route('dashboard');
}
```

---

### Fix #10: Add Missing Authorization to Inquiries

**File:** `app/Http/Controllers/InquiryController.php`

```php
// Create InquiryPolicy first
namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function update(User $user, Inquiry $inquiry): bool
    {
        // User can update their own inquiry, or admin can update any
        return $user->id === $inquiry->user_id || $user->hasRole('admin');
    }
    
    public function delete(User $user, Inquiry $inquiry): bool
    {
        // Same as update
        return $user->id === $inquiry->user_id || $user->hasRole('admin');
    }
}

// Register in AuthServiceProvider
protected $policies = [
    Inquiry::class => InquiryPolicy::class,
];

// Update controller to use policy
public function edit(Inquiry $inquiry): View
{
    // Use policy instead of email check
    $this->authorize('update', $inquiry);
    
    $vehicles = Vehicle::orderBy('brand')->limit(100)->get();
    return view('inquiries.form', compact('inquiry', 'vehicles'));
}
```

---

## Testing Checklist

```bash
# 1. Verify APP_DEBUG=false
php artisan config:show APP_DEBUG
# Output: false

# 2. Verify session encryption
php artisan config:show SESSION_ENCRYPT
# Output: true

# 3. Check no debug endpoint
curl http://localhost:8000/debug-users 2>/dev/null | grep -q "Route" && echo "REMOVED ✓" || echo "Still exists!"

# 4. Test file storage
php artisan storage:link
ls -la public/storage
# Should show symlink

# 5. Test exception logging
php artisan tinker
# > throw new \Exception("Test error");
# Check storage/logs/laravel.log for entry

# 6. Test login throttling
# Try 6 failed logins quickly
# 6th attempt should be throttled

# 7. Test authorization
# Try accessing /buyers as non-admin user
# Should get 403 Unauthorized
```

---

## Deployment Commands

```bash
# Before deployment
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# After deployment
php artisan storage:link
php artisan migrate --force
php artisan queue:work &  # If using queues

# Verify
php artisan config:show APP_DEBUG
curl https://your-domain.com/debug-users 2>&1 | grep 404
```

---

## Environment File Template

```env
APP_NAME="CBS - Car Broker System"
APP_ENV=production          # ← IMPORTANT
APP_KEY=base64:xxx
APP_DEBUG=false             # ← FIXED
APP_URL=https://cbs.local   # ← Use HTTPS

CACHE_VIEWS=true
SESSION_LIFETIME=60         # ← REDUCED from 120
SESSION_DOMAIN=cbs.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=CBS
DB_USERNAME=root
DB_PASSWORD=strong_password_here

FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=warning

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_ENCRYPTION=tls

# Add admin email for alerts
ADMIN_EMAIL=admin@cbs.local

# Disable debug routes
ALLOW_DEBUG_ROUTES=false
```

---

## Quick Verification Script

```php
<?php
// create in project root as: verify-security.php
// php verify-security.php

$issues = [];
$fixes = [];

// Check 1: APP_DEBUG
if (env('APP_DEBUG') === true) {
    $issues[] = "❌ APP_DEBUG=true - CRITICAL";
} else {
    $fixes[] = "✅ APP_DEBUG is disabled";
}

// Check 2: Debug endpoint removed
$routeFile = file_get_contents('routes/web.php');
if (strpos($routeFile, '/debug-users') !== false) {
    $issues[] = "❌ /debug-users endpoint still exists - CRITICAL";
} else {
    $fixes[] = "✅ /debug-users endpoint removed";
}

// Check 3: Session encryption
if (config('session.encrypt') ?? false) {
    $fixes[] = "✅ Session encryption enabled";
} else {
    $issues[] = "❌ Session encryption disabled - CRITICAL";
}

// Check 4: Exception handler
$handlerFile = file_get_contents('app/Exceptions/Handler.php');
if (strpos($handlerFile, '// Empty') !== false && strpos($handlerFile, 'Log::') === false) {
    $issues[] = "❌ Exception handler not logging - CRITICAL";
} else {
    $fixes[] = "✅ Exception handler implemented";
}

// Output
echo "\n=== SECURITY VERIFICATION ===\n\n";
if (!empty($issues)) {
    echo "⚠️  ISSUES FOUND:\n";
    foreach ($issues as $issue) {
        echo "  $issue\n";
    }
    echo "\n";
}

echo "✅ FIXED ITEMS:\n";
foreach ($fixes as $fix) {
    echo "  $fix\n";
}

echo "\n" . (count($issues) > 0 ? "❌ FAILED" : "✅ PASSED") . "\n";
```

---

## Next Steps

1. ✅ **Immediately Deploy:** Fixes #1-5 today
2. ✅ **Before Launch:** Fixes #6-10 this week
3. 📋 **Monitor:** Set up error logging and alerts
4. 📅 **Schedule:** Plan for medium/low priority fixes in next sprint

---

**Questions?** Refer to SECURITY-AUDIT-REPORT.md for detailed explanations of each issue.
