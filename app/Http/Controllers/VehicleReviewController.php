<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\VehicleReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VehicleReviewController extends Controller
{
    public function store(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $user = $request->user();
        $buyer = Buyer::where('user_id', $user->id)->first();

        if (! $buyer) {
            abort(403, 'Only verified buyers can review vehicles.');
        }

        $transaction = Transaction::where('vehicle_id', $vehicle->id)
            ->where('buyer_id', $buyer->id)
            ->where('status', 'completed')
            ->firstOrFail();

        if (VehicleReview::where('transaction_id', $transaction->id)->exists()) {
            return back()->withErrors(['review' => 'You have already reviewed this purchase.']);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'pros' => ['nullable', 'string', 'max:1000'],
            'cons' => ['nullable', 'string', 'max:1000'],
            'would_recommend' => ['nullable', 'boolean'],
        ]);

        VehicleReview::create([
            'vehicle_id' => $vehicle->id,
            'transaction_id' => $transaction->id,
            'reviewer_id' => $user->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $transaction->seller_id,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'comment' => $data['comment'] ?? null,
            'pros' => $data['pros'] ?? null,
            'cons' => $data['cons'] ?? null,
            'would_recommend' => $request->boolean('would_recommend', true),
            'status' => 'published',
        ]);

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Your review has been published successfully.');
    }
}