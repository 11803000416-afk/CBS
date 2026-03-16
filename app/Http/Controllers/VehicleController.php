<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $vehicles = Vehicle::with(['seller', 'broker'])
            ->when($request->filled('brand'), fn ($query) => $query->where('brand', 'like', '%' . $request->string('brand') . '%'))
            ->when($request->filled('model'), fn ($query) => $query->where('model', 'like', '%' . $request->string('model') . '%'))
            ->when($request->filled('year'), fn ($query) => $query->where('year', $request->integer('year')))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->input('max_price')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles'));
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
            ->latest()
            ->paginate(10);

        return view('vehicles.my-vehicles', compact('vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $isUserListing = $request->routeIs('my-vehicles.*');
        
        $rules = [
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1980', 'max:' . now()->year],
            'mileage' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,reserved,sold'],
            'images.*' => ['nullable', 'image', 'max:2048'],
        ];

        // Only require seller_id for admin/agent listings
        if (!$isUserListing) {
            $rules['seller_id'] = ['required', 'exists:sellers,id'];
        }

        $data = $request->validate($rules);

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
            $data['seller_id'] = $seller->id;
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('vehicles', 'public');
            }
        }

        Vehicle::create([
            ...$data,
            'created_by' => $request->user()->id,
            'images' => $imagePaths,
        ]);

        $redirectRoute = $isUserListing ? 'my-vehicles.index' : 'vehicles.index';
        return redirect()->route($redirectRoute)->with('success', 'Vehicle listed successfully.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['seller', 'broker', 'inquiries.buyer', 'transaction']);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle): View
    {
        $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();
        $isUserListing = request()->routeIs('my-vehicles.*');

        // Check if user owns this vehicle (for my-vehicles routes)
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
        $data = $request->validate([
            'seller_id' => ['required', 'exists:sellers,id'],
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1980', 'max:' . now()->year],
            'mileage' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,reserved,sold'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'removed_images.*' => ['nullable', 'integer'],
        ]);

        $imagePaths = $vehicle->images ?? [];
        
        // Handle removed images
        if ($request->has('removed_images')) {
            $removedIndices = $request->input('removed_images', []);
            foreach ($removedIndices as $index) {
                if (isset($imagePaths[$index])) {
                    Storage::disk('public')->delete($imagePaths[$index]);
                    unset($imagePaths[$index]);
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

        $vehicle->update([
            ...$data,
            'images' => $imagePaths,
        ]);

        $redirectRoute = $request->routeIs('my-vehicles.*') ? 'my-vehicles.index' : 'vehicles.index';
        return redirect()->route($redirectRoute)->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        // Check ownership for my-vehicles routes or if user is not admin/agent
        if (request()->routeIs('my-vehicles.*') || !auth()->user()->hasRole(['admin', 'agent'])) {
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
