<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use App\Notifications\NewSellerRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Seller::class);

        $sellers = Seller::latest('id')->paginate(10);

        return view('sellers.index', compact('sellers'));
    }

    public function create(): View
    {
        $this->authorize('create', Seller::class);

        return view('sellers.form', ['seller' => new Seller()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Seller::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:sellers,email'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $seller = Seller::create($data);

        // Send notification to admin for approval
        $adminUsers = User::where('role', 'admin')->get();
        Notification::send($adminUsers, new NewSellerRegistration($seller));

        return redirect()->route('sellers.index')->with('success', 'Seller created successfully. Admin has been notified for approval.');
    }

    public function edit(Seller $seller): View
    {
        $this->authorize('update', $seller);

        return view('sellers.form', compact('seller'));
    }

    public function update(Request $request, Seller $seller): RedirectResponse
    {
        $this->authorize('update', $seller);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:sellers,email,' . $seller->id],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $seller->update($data);

        return redirect()->route('sellers.index')->with('success', 'Seller updated successfully.');
    }

    public function destroy(Seller $seller): RedirectResponse
    {
        $this->authorize('delete', $seller);

        $seller->delete();

        return redirect()->route('sellers.index')->with('success', 'Seller deleted successfully.');
    }
}
