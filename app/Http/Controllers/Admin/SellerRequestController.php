<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use App\Models\User;
use App\Notifications\SellerRequestStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SellerRequestController extends Controller
{
    public function index(Request $request): View
    {
        $pendingSellerRequestsCount = SellerRequest::where('status', 'pending')->count();

        $requests = SellerRequest::with(['user', 'vehicle'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.seller-requests.index', compact('requests', 'pendingSellerRequestsCount'));
    }

    public function show(SellerRequest $sellerRequest): View
    {
        $sellerRequest->load(['user', 'vehicle', 'reviewer']);
        return view('admin.seller-requests.show', compact('sellerRequest'));
    }

    public function approve(Request $request, SellerRequest $sellerRequest): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $user = $sellerRequest->user;

        // Update seller request
        $sellerRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Update user role to seller
        $user->update(['role' => 'seller']);

        // Create or update seller record
        $user->seller()->updateOrCreate([], [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'address' => $user->address ?? '',
            'status' => 'active',
        ]);

        try {
            $user->notify(new SellerRequestStatusUpdated($sellerRequest));
        } catch (\Throwable $e) {
            Log::error('Failed sending seller request approval notification', [
                'seller_request_id' => $sellerRequest->id,
                'exception' => $e,
            ]);
        }

        return redirect()->route('admin.seller-requests.index')
            ->with('success', "Seller request approved for {$user->name}. User role updated to seller.");
    }

    public function reject(Request $request, SellerRequest $sellerRequest): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $sellerRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        try {
            $sellerRequest->user->notify(new SellerRequestStatusUpdated($sellerRequest));
        } catch (\Throwable $e) {
            Log::error('Failed sending seller request rejection notification', [
                'seller_request_id' => $sellerRequest->id,
                'exception' => $e,
            ]);
        }

        return redirect()->route('admin.seller-requests.index')
            ->with('success', 'Seller request rejected.');
    }
}
