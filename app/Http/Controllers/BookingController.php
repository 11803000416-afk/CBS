<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\BookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['vehicle', 'buyer', 'seller'])
            ->where(function ($query) {
                if (auth()->user()->hasRole(User::ROLE_SELLER)) {
                    $query->where('seller_id', auth()->id());
                } elseif (auth()->user()->hasRole(User::ROLE_BUYER)) {
                    $query->where('buyer_id', auth()->id());
                }
            })
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create(Vehicle $vehicle): View
    {
        if (auth()->user()->hasRole(User::ROLE_BUYER) && $vehicle->status !== 'available') {
            abort(403, 'This vehicle is not available for booking');
        }

        return view('bookings.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle): JsonResponse
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'buyer_message' => 'nullable|string|max:500',
        ]);

        // Check for double-booking: ensure no confirmed bookings exist for this vehicle at the same time
        $existingBooking = Booking::where('vehicle_id', $vehicle->id)
            ->where('status', 'confirmed')
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'This vehicle is already booked at this time. Please choose a different date or time.'
            ], 409);
        }

        $seller = $vehicle->seller ? $vehicle->seller->user_id : $vehicle->created_by;

        if (!$seller) {
            return response()->json([
                'success' => false,
                'message' => 'Booking failed: seller contact information is missing. Please contact support or try a different vehicle.'
            ], 400);
        }

        $booking = Booking::create([
            'vehicle_id' => $vehicle->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $seller,
            'status' => 'pending',
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'total_amount' => 0, // No fee for test drive
            'buyer_message' => $request->buyer_message,
        ]);

        // Format the datetime for display
        $bookingDateTime = \Carbon\Carbon::parse($request->booking_date . ' ' . $request->booking_time)->format('M d, Y - h:i A');

        // Notify seller about the booking
        $sellerUser = User::find($seller);
        if ($sellerUser) {
            $sellerUser->notify(new BookingConfirmed($booking, 'seller'));
        }

        // Notify admin users about the booking
        $admins = User::where('role', 'admin')->get();
        
        if ($admins->count() > 0) {
            Notification::send($admins, new BookingConfirmed($booking, 'admin'));
        }

        return response()->json([
            'success' => true,
            'message' => '🎉 Booking successful! Please go to the seller at your registered time: ' . $bookingDateTime . '. The seller has been notified and will be expecting you.',
            'booking' => $booking->load(['vehicle', 'buyer', 'seller'])
        ]);
    }

    public function update(Request $request, Booking $booking): JsonResponse
    {
        $this->authorize('update', $booking);

        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
            'seller_message' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => $request->status,
            'seller_message' => $request->seller_message,
        ]);

        // Send confirmation emails if booking is confirmed
        if ($request->status === 'confirmed') {
            // Send email to seller
            $booking->seller->notify(new BookingConfirmed($booking, 'seller'));

            // Send email to buyer
            $booking->buyer->notify(new BookingConfirmed($booking, 'buyer'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking ' . $request->status . ' successfully! Confirmation emails have been sent to both parties.',
            'booking' => $booking->load(['vehicle', 'buyer', 'seller'])
        ]);
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        return view('bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $this->authorize('delete', $booking);

        if ($booking->status === 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel a confirmed booking'
            ], 403);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully!'
        ]);
    }
}
