<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerRequest;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Notifications\SellerRequestSubmitted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        // Validate filter query parameters
        $request->validate([
            'brand' => ['sometimes', 'string', 'max:120'],
            'model' => ['sometimes', 'string', 'max:120'],
            'year' => ['sometimes', 'integer', 'min:1980', 'max:' . now()->year],
            'min_price' => ['sometimes', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'numeric', 'min:0', 'gte:min_price'],
            'status' => ['sometimes', 'in:available,reserved,sold'],
            'transmission' => ['sometimes', 'in:Automatic,Manual,Clutchless Manual'],
            'fuel_type' => ['sometimes', 'in:Petrol,Diesel,Electric,Hybrid,CNG,LPG'],
        ]);

        $vehicles = Vehicle::with(['seller', 'broker', 'sellerRequest'])
            ->when($request->filled('brand'), fn ($query) => $query->where('brand', 'like', '%' . $request->string('brand') . '%'))
            ->when($request->filled('model'), fn ($query) => $query->where('model', 'like', '%' . $request->string('model') . '%'))
            ->when($request->filled('year'), fn ($query) => $query->where('year', $request->integer('year')))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->input('max_price')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('transmission'), fn ($query) => $query->where('transmission', $request->input('transmission')))
            ->when($request->filled('fuel_type'), fn ($query) => $query->where('fuel_type', $request->input('fuel_type')))
            // Show approved seller vehicles to all users, or filter by seller request status for admins
            ->when(!auth()->user()->hasRole([User::ROLE_ADMIN, User::ROLE_BROKER, User::ROLE_SELLER]), function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('status', 'available')
                        ->orWhereHas('sellerRequest', function ($sellerRequestQuery) {
                            $sellerRequestQuery->where('status', 'approved');
                        });
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles'));
    }

    public function browse(Request $request): View
    {
        $request->validate([
            'brand' => ['sometimes', 'string', 'max:120'],
            'model' => ['sometimes', 'string', 'max:120'],
            'year' => ['sometimes', 'integer', 'min:1980', 'max:' . now()->year],
            'min_price' => ['sometimes', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'numeric', 'min:0', 'gte:min_price'],
            'transmission' => ['sometimes', 'in:Automatic,Manual,Clutchless Manual'],
            'fuel_type' => ['sometimes', 'in:Petrol,Diesel,Electric,Hybrid,CNG,LPG'],
        ]);

        $vehicles = Vehicle::with(['seller', 'broker', 'sellerRequest'])
            ->where(function ($q) {
                $q->where('status', 'available')
                  ->orWhereHas('sellerRequest', function ($query) {
                      $query->where('status', 'approved');
                  });
            })
            ->when($request->filled('brand'), fn ($query) => $query->where('brand', 'like', '%' . $request->string('brand') . '%'))
            ->when($request->filled('model'), fn ($query) => $query->where('model', 'like', '%' . $request->string('model') . '%'))
            ->when($request->filled('year'), fn ($query) => $query->where('year', $request->integer('year')))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->input('max_price')))
            ->when($request->filled('transmission'), fn ($query) => $query->where('transmission', $request->input('transmission')))
            ->when($request->filled('fuel_type'), fn ($query) => $query->where('fuel_type', $request->input('fuel_type')))
            // Show seller's own vehicles when user is a seller
            ->when(auth()->user()->hasRole(User::ROLE_SELLER), function ($query) {
                $seller = Seller::where('email', auth()->user()->email)->first();
                if ($seller) {
                    $query->where('seller_id', $seller->id);
                }
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('vehicles.browse', compact('vehicles'));
    }

    public function create(): View
    {
        $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();
        $isUserListing = request()->routeIs('my-vehicles.*');

        return view('vehicles.form', [
            'vehicle' => new Vehicle(), 
            'sellers' => $sellers,
            'isUserListing' => $isUserListing
        ]);
    }

    public function myVehicles(): View
    {
        // Get or create seller profile for current user
        $seller = Seller::firstOrCreate(
            ['email' => auth()->user()->email],
            [
                'name' => auth()->user()->name,
                'phone' => auth()->user()->phone ?? '',
                'address' => auth()->user()->address ?? '',
                'status' => 'active',
            ]
        );

        $vehicles = Vehicle::with(['seller', 'broker'])
            ->where('seller_id', $seller->id)
            ->latest('id')
            ->paginate(10);

        return view('vehicles.my-vehicles', compact('vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $isUserListing = $request->routeIs('my-vehicles.*');
        
        // Auto-create/get seller for user listings
        if ($isUserListing) {
            $seller = Seller::firstOrCreate(
                ['email' => auth()->user()->email],
                [
                    'name' => auth()->user()->name,
                    'phone' => auth()->user()->phone ?? '',
                    'address' => auth()->user()->address ?? '',
                    'status' => 'active',
                ]
            );
        }

        $rules = [
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1980', 'max:' . now()->year],
            'mileage' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'transmission' => ['nullable', 'in:Automatic,Manual,Clutchless Manual'],
            'fuel_type' => ['nullable', 'in:Petrol,Diesel,Electric,Hybrid,CNG,LPG'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,reserved,sold'],
            'images.*' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,bmp,webp,avif,heic,heif,tiff,tif', 'max:2048'],
            'videos.*' => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm,mkv,flv,wmv,m4v', 'max:102400'],
        ];

        // Create seller for user listings (buyers) or get existing seller
        $user = auth()->user();
        if ($isUserListing || ($user && $user->role === 'buyer')) {
            $seller = Seller::firstOrCreate(
                ["email" => $user->email],
                [
                    "name" => $user->name,
                    "phone" => $user->phone ?? "",
                    "address" => $user->address ?? "",
                    "status" => "active",
                ]
            );
            // Add seller_id to request data before validation
            $request->merge(["seller_id" => $seller->id]);
        } else {
            // Only require seller_id for admin/seller listings
            $rules["seller_id"] = ["required", "exists:sellers,id"];
        }

        $data = $request->validate($rules);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('vehicles', 'public');
            }
        }

        $videoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('vehicles/videos', 'public');
            }
        }

        $vehicle = Vehicle::create([
            ...$data,
            'seller_id' => $seller->id ?? null,
            'created_by' => $user ? $user->id : null,
            'images' => $imagePaths,
            'videos' => $videoPaths,
        ]);

        // Create seller request if this is buyer's first vehicle listing
        if ($isUserListing && $request->user()->role === 'buyer') {
            $existingRequest = SellerRequest::where('user_id', $request->user()->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();
            
            if (!$existingRequest) {
                $sellerRequest = SellerRequest::create([
                    'user_id' => $request->user()->id,
                    'vehicle_id' => $vehicle->id,
                    'user_message' => 'Requesting seller privileges after listing first vehicle.',
                    'status' => 'pending',
                ]);

                try {
                    $admins = User::where('role', User::ROLE_ADMIN)->get();
                    if ($admins->isNotEmpty()) {
                        Notification::send($admins, new SellerRequestSubmitted($sellerRequest));
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed sending seller request notification', [
                        'seller_request_id' => $sellerRequest->id,
                        'exception' => $e,
                    ]);
                }
            }
        }

        $redirectRoute = $isUserListing ? 'my-vehicles.index' : 'vehicles.index';
        $message = $isUserListing && $request->user()->role === 'buyer' 
            ? 'Vehicle listed successfully! Your seller request has been submitted for admin review.' 
            : 'Vehicle listed successfully.';
            
        return redirect()->route($redirectRoute)->with('success', $message);
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['seller', 'broker', 'inquiries', 'transaction', 'sellerRequest.user', 'sellerRequest.reviewer', 'reviews.reviewer']);
        $user = auth()->user();
        $canEdit = false;
        $editRoute = route('vehicles.edit', $vehicle);
        $deleteRoute = route('vehicles.destroy', $vehicle);
        $canReview = false;
        $existingReview = null;
        $reviewStats = [
            'average' => 0,
            'count' => 0,
            'breakdown' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
        ];

        if ($user) {
            if ($user->hasRole([User::ROLE_ADMIN, User::ROLE_SELLER])) {
                $canEdit = true;
                if ($user->hasRole(User::ROLE_SELLER) && ! $user->hasRole(User::ROLE_ADMIN)) {
                    $editRoute = route('my-vehicles.edit', $vehicle);
                    $deleteRoute = route('my-vehicles.destroy', $vehicle);
                }
            } else {
                $userSeller = Seller::where('email', $user->email)->first();
                if ($userSeller && $vehicle->seller_id === $userSeller->id) {
                    $canEdit = true;
                    $editRoute = route('my-vehicles.edit', $vehicle);
                    $deleteRoute = route('my-vehicles.destroy', $vehicle);
                }
            }

            $buyer = Buyer::where('user_id', $user->id)->first();
            if ($buyer) {
                $completedTransaction = Transaction::where('vehicle_id', $vehicle->id)
                    ->where('buyer_id', $buyer->id)
                    ->where('status', 'completed')
                    ->first();

                if ($completedTransaction) {
                    $existingReview = $vehicle->reviews->firstWhere('transaction_id', $completedTransaction->id);
                    $canReview = ! $existingReview;
                }
            }
        }

        $publishedReviews = $vehicle->reviews->where('status', 'published');
        $reviewStats['count'] = $publishedReviews->count();
        $reviewStats['average'] = $publishedReviews->avg('rating') ? round((float) $publishedReviews->avg('rating'), 1) : 0;
        foreach ([1, 2, 3, 4, 5] as $rating) {
            $reviewStats['breakdown'][$rating] = $publishedReviews->where('rating', $rating)->count();
        }

        return view('vehicles.show', compact('vehicle', 'canEdit', 'editRoute', 'deleteRoute', 'canReview', 'existingReview', 'reviewStats'));
    }

    public function edit(Vehicle $vehicle): View
    {
        // Authorization: Check if user can edit this vehicle
        $this->authorize('update', $vehicle);
        
        $isUserListing = request()->routeIs('my-vehicles.*');
        
        // For user listings, include the current vehicle's seller even if inactive
        // For admin/seller, only show active sellers
        if ($isUserListing) {
            $sellers = Seller::where('status', 'active')
                ->orWhere('id', $vehicle->seller_id)
                ->orderBy('name')
                ->limit(100)
                ->get();
        } else {
            $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();
        }

        // Additional check if user owns this vehicle (for my-vehicles routes)
        if ($isUserListing) {
            $userSeller = Seller::where('email', auth()->user()->email)->first();
            if (!$userSeller || $vehicle->seller_id !== $userSeller->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view('vehicles.form', compact('vehicle', 'sellers', 'isUserListing'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        // Authorization check: Ensure user can update this vehicle
        $this->authorize('update', $vehicle);
        
        $isUserListing = $request->routeIs('my-vehicles.*');

        $rules = [
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1980', 'max:' . now()->year],
            'mileage' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'transmission' => ['nullable', 'in:Automatic,Manual,Clutchless Manual'],
            'fuel_type' => ['nullable', 'in:Petrol,Diesel,Electric,Hybrid,CNG,LPG'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,reserved,sold'],
            'images.*' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,bmp,webp,avif,heic,heif,tiff,tif', 'max:2048'],
            'videos.*' => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm,mkv,flv,wmv,m4v', 'max:102400'],
            'removed_images.*' => ['nullable', 'integer'],
            'removed_videos.*' => ['nullable', 'integer'],
        ];

        // Only require seller_id for admin/seller listings
        if (!$isUserListing) {
            $rules['seller_id'] = ['required', 'exists:sellers,id'];
        }

        $data = $request->validate($rules);

        // For user listings, keep the existing seller_id
        if ($isUserListing) {
            $data['seller_id'] = $vehicle->seller_id;
        }

        $imagePaths = $vehicle->images ?? [];
        
        // Handle removed images
        if ($request->has('removed_images')) {
            $removedImages = $request->input('removed_images', []);
            foreach ($removedImages as $imagePath) {
                // Remove from storage
                Storage::disk('public')->delete($imagePath);
                // Remove from array
                $key = array_search($imagePath, $imagePaths);
                if ($key !== false) {
                    unset($imagePaths[$key]);
                }
            }
            $imagePaths = array_values($imagePaths); // Re-index array
        }

        // Handle new uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('vehicles', 'public');
            }
        }

        $videoPaths = $vehicle->videos ?? [];

        // Handle removed videos
        if ($request->has('removed_videos')) {
            $removedVideos = $request->input('removed_videos', []);
            foreach ($removedVideos as $videoPath) {
                // Remove from storage
                Storage::disk('public')->delete($videoPath);
                // Remove from array
                $key = array_search($videoPath, $videoPaths);
                if ($key !== false) {
                    unset($videoPaths[$key]);
                }
            }
            $videoPaths = array_values($videoPaths); // Re-index array
        }

        // Handle new uploaded videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('vehicles/videos', 'public');
            }
        }

        $vehicle->update([
            ...$data,
            'images' => $imagePaths,
            'videos' => $videoPaths,
        ]);

        $redirectRoute = $request->routeIs('my-vehicles.*') ? 'my-vehicles.index' : 'vehicles.index';
        return redirect()->route($redirectRoute)->with('success', 'Vehicle updated successfully.');
    }

    public function unified(Request $request): View
    {
        $request->validate([
            'brand' => ['sometimes', 'string', 'max:120'],
            'model' => ['sometimes', 'string', 'max:120'],
            'year' => ['sometimes', 'integer', 'min:1980', 'max:' . now()->year],
            'min_price' => ['sometimes', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'numeric', 'min:0', 'gte:min_price'],
            'status' => ['sometimes', 'in:available,reserved,sold'],
            'transmission' => ['sometimes', 'in:Automatic,Manual,Clutchless Manual'],
            'fuel_type' => ['sometimes', 'in:Petrol,Diesel,Electric,Hybrid,CNG,LPG'],
        ]);

        $vehicles = Vehicle::with(['seller', 'broker', 'sellerRequest'])
            ->where(function ($q) {
                $q->where('status', 'available')
                  ->orWhereHas('sellerRequest', function ($query) {
                      $query->where('status', 'approved');
                  });
            })
            ->when($request->filled('brand'), fn ($query) => $query->where('brand', 'like', '%' . $request->string('brand') . '%'))
            ->when($request->filled('model'), fn ($query) => $query->where('model', 'like', '%' . $request->string('model') . '%'))
            ->when($request->filled('year'), fn ($query) => $query->where('year', $request->integer('year')))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->input('max_price')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('transmission'), fn ($query) => $query->where('transmission', $request->input('transmission')))
            ->when($request->filled('fuel_type'), fn ($query) => $query->where('fuel_type', $request->input('fuel_type')))
            // Show seller's own vehicles when user is a seller
            ->when(auth()->check() && auth()->user() && auth()->user()->hasRole(User::ROLE_SELLER), function ($query) {
                $seller = Seller::where('email', auth()->user()->email)->first();
                if ($seller) {
                    $query->where('seller_id', $seller->id);
                }
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();
        
        return view('vehicles.unified', compact('vehicles'));
    }

    public function showApi(Vehicle $vehicle)
    {
        return response()->json([
            'id' => $vehicle->id,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'year' => $vehicle->year,
            'mileage' => $vehicle->mileage,
            'price' => $vehicle->price,
            'description' => $vehicle->description,
            'images' => $vehicle->images,
            'status' => $vehicle->status,
            'seller' => [
                'id' => $vehicle->seller?->id,
                'name' => $vehicle->seller?->name,
                'email' => $vehicle->seller?->email,
                'phone' => $vehicle->seller?->phone,
            ],
            'created_at' => $vehicle->created_at,
            'updated_at' => $vehicle->updated_at,
        ]);
    }

    /**
     * AJAX API endpoint for live vehicle filtering
     */
    public function searchAjax(Request $request)
    {
        $request->validate([
            'brand' => ['sometimes', 'string', 'max:120'],
            'model' => ['sometimes', 'string', 'max:120'],
            'year' => ['sometimes', 'integer', 'min:1980', 'max:' . now()->year],
            'min_price' => ['sometimes', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:available,reserved,sold'],
            'transmission' => ['sometimes', 'in:Automatic,Manual,Clutchless Manual'],
            'fuel_type' => ['sometimes', 'in:Petrol,Diesel,Electric,Hybrid,CNG,LPG'],
            'mileage_max' => ['sometimes', 'numeric', 'min:0'],
        ]);

        $query = Vehicle::with(['seller', 'broker']);

        // Apply filters
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->string('brand') . '%');
        }
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->string('model') . '%');
        }
        if ($request->filled('year')) {
            $query->where('year', $request->integer('year'));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->input('transmission'));
        }
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->input('fuel_type'));
        }
        if ($request->filled('mileage_max')) {
            $query->where('mileage', '<=', $request->input('mileage_max'));
        }

        // Access control
        if (!auth()->user()->hasRole([User::ROLE_ADMIN, User::ROLE_SELLER])) {
            $query->where(function ($subQuery) {
                $subQuery->where('status', 'available')
                    ->orWhereHas('sellerRequest', function ($sellerRequestQuery) {
                        $sellerRequestQuery->where('status', 'approved');
                    });
            });
        }

        $vehicles = $query->latest('id')->limit(12)->get();

        return response()->json([
            'success' => true,
            'count' => count($vehicles),
            'vehicles' => $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'year' => $vehicle->year,
                    'price' => $vehicle->price,
                    'mileage' => $vehicle->mileage,
                    'status' => $vehicle->status,
                    'transmission' => $vehicle->transmission,
                    'fuel_type' => $vehicle->fuel_type,
                    'image' => $vehicle->images && count($vehicle->images) > 0 ? asset('storage/' . $vehicle->images[0]) : asset('images/placeholder.jpg'),
                    'view_url' => route('vehicles.show', $vehicle),
                    'edit_url' => route('vehicles.edit', $vehicle),
                ];
            }),
        ]);
    }

    /**
     * Suggest brands with a representative image for live brand input.
     */
    public function brandSuggest(Request $request)
    {
        $request->validate([
            'q' => ['sometimes', 'string', 'max:120'],
        ]);

        $q = $request->query('q', '');

        if (trim($q) === '') {
            return response()->json([]);
        }

        $brands = Vehicle::where('brand', 'like', '%' . $q . '%')
            ->whereNotNull('images')
            ->whereRaw('JSON_LENGTH(images) > 0')
            ->select('brand', 'images')
            ->groupBy('brand')
            ->limit(12)
            ->get();

        $result = $brands->map(function ($v) {
            $imgPath = is_array($v->images) && count($v->images) ? $v->images[0] : null;
            return [
                'brand' => $v->brand,
                'image' => $imgPath ? asset('storage/' . $imgPath) : null,
            ];
        });

        return response()->json($result->values());
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        // Check ownership for my-vehicles routes or if user is not admin/seller
        if (request()->routeIs('my-vehicles.*') || !auth()->user()->hasRole([User::ROLE_ADMIN, User::ROLE_SELLER])) {
            $userSeller = Seller::where('email', auth()->user()->email)->first();
            if (!$userSeller || $vehicle->seller_id !== $userSeller->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        foreach (($vehicle->images ?? []) as $path) {
            Storage::disk('public')->delete($path);
        }

        $vehicle->delete();

        $redirectRoute = request()->routeIs('my-vehicles.*') ? 'my-vehicles.index' : 'vehicles.index';
        return redirect()->route($redirectRoute)->with('success', 'Vehicle deleted successfully.');
    }
}
