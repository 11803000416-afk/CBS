<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        if ($user->hasRole(\App\Models\User::ROLE_SELLER)) {
            // Seller sees offers for their vehicles
            $offers = Offer::with(['vehicle', 'buyer'])
                ->where('seller_id', $user->id)
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole(\App\Models\User::ROLE_BUYER)) {
            // Buyer sees their own offers
            $offers = Offer::with(['vehicle', 'seller'])
                ->where('buyer_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            $offers = collect();
        }

        return view('offers.index', compact('offers'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'offer_amount' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:500',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $buyer = auth()->user();

        // Check if user already made an offer for this vehicle
        $existingOffer = Offer::where('vehicle_id', $vehicle->id)
            ->where('buyer_id', $buyer->id)
            ->where('status', 'pending')
            ->first();

        if ($existingOffer) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending offer for this vehicle'
            ]);
        }

        // Create the offer
        $offer = Offer::create([
            'vehicle_id' => $vehicle->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $vehicle->created_by,
            'offer_amount' => $request->offer_amount,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Offer submitted successfully!',
            'offer' => $offer->load(['vehicle', 'buyer', 'seller'])
        ]);
    }

    public function update(Request $request, Offer $offer): JsonResponse
    {
        $this->authorize('update', $offer);

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $offer->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => "Offer {$request->status} successfully!",
            'offer' => $offer->fresh()
        ]);
    }

    public function destroy(Offer $offer): JsonResponse
    {
        $this->authorize('delete', $offer);

        $offer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offer withdrawn successfully!'
        ]);
    }
}
