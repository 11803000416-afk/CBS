<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\Seller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class VehicleService
{
    /**
     * Get paginated vehicles with filters
     */
    public function getVehicles($filters = [], $perPage = 10): Paginator
    {
        $query = Vehicle::with(['seller', 'broker']);

        if (isset($filters['brand']) && !empty($filters['brand'])) {
            $query->where('brand', 'like', '%' . $filters['brand'] . '%');
        }

        if (isset($filters['model']) && !empty($filters['model'])) {
            $query->where('model', 'like', '%' . $filters['model'] . '%');
        }

        if (isset($filters['year']) && !empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['min_price']) && !empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && !empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Create a new vehicle
     */
    public function createVehicle(array $data): Vehicle
    {
        // Get or create seller if user listing
        if (isset($data['is_user_listing']) && $data['is_user_listing']) {
            $seller = $this->getOrCreateSellerForUser(auth()->user());
            $data['seller_id'] = $seller->id;
        }

        // Handle image uploads
        if (isset($data['images'])) {
            $data['images'] = $this->uploadImages($data['images']);
        }

        $data['created_by'] = auth()->id();

        return Vehicle::create($data);
    }

    /**
     * Update existing vehicle
     */
    public function updateVehicle(Vehicle $vehicle, array $data): bool
    {
        // Handle image uploads
        if (isset($data['images']) && is_array($data['images'])) {
            $existingImages = $vehicle->images ?? [];
            $newImages = $this->uploadImages($data['images']);
            $data['images'] = array_merge($existingImages, $newImages);
        }

        return $vehicle->update($data);
    }

    /**
     * Delete vehicle
     */
    public function deleteVehicle(Vehicle $vehicle): bool
    {
        // Delete images
        if ($vehicle->images && is_array($vehicle->images)) {
            foreach ($vehicle->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        return $vehicle->delete();
    }

    /**
     * Get user's personal vehicles
     */
    public function getUserVehicles(int $perPage = 10): Paginator
    {
        $seller = $this->getOrCreateSellerForUser(auth()->user());
        
        return Vehicle::with(['seller', 'broker'])
            ->where('seller_id', $seller->id)
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Get or create seller for current user
     */
    public function getOrCreateSellerForUser($user): Seller
    {
        return Seller::firstOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
                'status' => 'active',
            ]
        );
    }

    /**
     * Upload vehicle images
     */
    public function uploadImages(array $files): array
    {
        $uploadedPaths = [];

        foreach ($files as $file) {
            if ($file->isValid()) {
                $path = $file->store('vehicles', 'public');
                $uploadedPaths[] = $path;
            }
        }

        return $uploadedPaths;
    }

    /**
     * Get vehicle statistics
     */
    public function getStatistics(): array
    {
        return [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('status', 'available')->count(),
            'reserved' => Vehicle::where('status', 'reserved')->count(),
            'sold' => Vehicle::where('status', 'sold')->count(),
            'avg_price' => Vehicle::avg('price'),
            'total_value' => Vehicle::sum('price'),
        ];
    }
}
