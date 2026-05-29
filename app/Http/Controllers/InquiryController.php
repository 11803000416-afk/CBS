<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\InquiryResponded;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inquiry::with(['vehicle', 'assignedBroker'])
            ->latest('id');

        if ($request->user()->role === 'buyer') {
            $query->where('user_email', $request->user()->email);
        }

        $inquiries = $query->paginate(10);

        return view('inquiries.index', compact('inquiries'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::where('status', 'available')->orderBy('brand')->limit(100)->get();

        return view('inquiries.form', ['inquiry' => new Inquiry(), 'vehicles' => $vehicles]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'meeting_location' => ['nullable', 'string', 'max:255'],
            'preferred_time' => ['nullable', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
        ];

        $data = $request->validate($rules);

        // Auto-reference user name and email
        $data['user_name'] = $request->user()->name;
        $data['user_email'] = $request->user()->email;

        // Convert datetime-local to proper datetime format if provided
        if (isset($data['preferred_time']) && $data['preferred_time']) {
            $data['preferred_time'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $data['preferred_time']);
        } else {
            $data['preferred_time'] = null;
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
        if (request()->user()->role === 'buyer' && request()->user()->email !== $inquiry->user_email) {
            abort(403, 'Unauthorized action.');
        }

        $vehicles = Vehicle::orderBy('brand')->limit(100)->get();

        return view('inquiries.form', compact('inquiry', 'vehicles'));
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        if ($request->user()->role === 'buyer' && $request->user()->email !== $inquiry->user_email) {
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'meeting_location' => ['nullable', 'string', 'max:255'],
            'preferred_time' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
            'response' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,responded,closed'],
        ];

        $data = $request->validate($rules);

        // Keep existing user_name and user_email for buyers
        if ($request->user()->role === 'buyer') {
            $data['user_name'] = $inquiry->user_name;
            $data['user_email'] = $inquiry->user_email;
        }

        // Convert datetime-local to proper datetime if provided
        if (isset($data['preferred_time']) && $data['preferred_time']) {
            $data['preferred_time'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $data['preferred_time']);
        } else {
            $data['preferred_time'] = null;
        }

        $data['assigned_to'] = $request->user()->role === 'buyer' ? $inquiry->assigned_to : $request->user()->id;

        $wasResponded = $inquiry->status === 'responded';
        $inquiry->update($data);

        // Notify buyer when a response is added/updated by non-buyer staff.
        if ($request->user()->role !== 'buyer' && !empty($inquiry->response) && $inquiry->status === 'responded') {
            $buyerUser = User::where('email', $inquiry->user_email)->first();
            if ($buyerUser && !$wasResponded) {
                try {
                    $buyerUser->notify(new InquiryResponded($inquiry->fresh(['vehicle'])));
                } catch (\Throwable $e) {
                    Log::error('Failed to send inquiry response notification', [
                        'inquiry_id' => $inquiry->id,
                        'buyer_user_id' => $buyerUser->id,
                        'exception' => $e,
                    ]);
                }
            }
        }

        return redirect()->route('inquiries.index')->with('success', 'Inquiry updated successfully.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        if (request()->user()->role === 'buyer' && request()->user()->email !== $inquiry->user_email) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->delete();

        return redirect()->route('inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}
