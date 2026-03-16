<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Inquiry;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inquiry::with(['vehicle', 'buyer', 'assignedBroker'])->latest();

        if ($request->user()->role === 'buyer') {
            $buyerId = optional($request->user()->buyerProfile)->id;
            $query->where('buyer_id', $buyerId ?: 0);
        }

        $inquiries = $query->paginate(10);

        return view('inquiries.index', compact('inquiries'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::where('status', 'available')->orderBy('brand')->limit(100)->get();
        $buyers = Buyer::where('status', 'active')->orderBy('name')->limit(100)->get();

        return view('inquiries.form', ['inquiry' => new Inquiry(), 'vehicles' => $vehicles, 'buyers' => $buyers]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'message' => ['required', 'string'],
            'meeting_location' => ['nullable', 'string', 'max:255'],
            'preferred_time' => ['nullable', 'date'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
        ];

        if ($request->user()->role !== 'buyer') {
            $rules['buyer_id'] = ['required', 'exists:buyers,id'];
        }

        $data = $request->validate($rules);

        if ($request->user()->role === 'buyer') {
            $buyer = Buyer::firstOrCreate(
                ['user_id' => $request->user()->id],
                [
                    'name' => $request->user()->name,
                    'phone' => $request->user()->phone ?? 'N/A',
                    'email' => $request->user()->email,
                    'address' => $request->user()->address,
                    'status' => 'active',
                ]
            );

            $data['buyer_id'] = $buyer->id;
        }

        Inquiry::create([
            ...$data,
            'assigned_to' => null,
            'status' => 'pending',
        ]);

        return redirect()->route('inquiries.index')->with('success', 'Inquiry submitted successfully.');
    }

    public function edit(Inquiry $inquiry): View
    {
        if (request()->user()->role === 'buyer' && request()->user()->buyerProfile?->id !== $inquiry->buyer_id) {
            abort(403, 'Unauthorized action.');
        }

        $vehicles = Vehicle::orderBy('brand')->limit(100)->get();
        $buyers = Buyer::where('status', 'active')->orderBy('name')->limit(100)->get();

        return view('inquiries.form', compact('inquiry', 'vehicles', 'buyers'));
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        if ($request->user()->role === 'buyer' && $request->user()->buyerProfile?->id !== $inquiry->buyer_id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'message' => ['required', 'string'],
            'meeting_location' => ['nullable', 'string', 'max:255'],
            'preferred_time' => ['nullable', 'date'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
            'response' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,responded,closed'],
        ]);

        $data['assigned_to'] = $request->user()->role === 'buyer' ? $inquiry->assigned_to : $request->user()->id;

        $inquiry->update($data);

        return redirect()->route('inquiries.index')->with('success', 'Inquiry updated successfully.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        if (request()->user()->role === 'buyer' && request()->user()->buyerProfile?->id !== $inquiry->buyer_id) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->delete();

        return redirect()->route('inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}
