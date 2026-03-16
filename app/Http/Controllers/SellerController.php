<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function index(): View
    {
        $sellers = Seller::latest()->paginate(10);

        return view('sellers.index', compact('sellers'));
    }

    public function create(): View
    {
        return view('sellers.form', ['seller' => new Seller()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:sellers,email'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Seller::create($data);

        return redirect()->route('sellers.index')->with('success', 'Seller created successfully.');
    }

    public function edit(Seller $seller): View
    {
        return view('sellers.form', compact('seller'));
    }

    public function update(Request $request, Seller $seller): RedirectResponse
    {
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
        $seller->delete();

        return redirect()->route('sellers.index')->with('success', 'Seller deleted successfully.');
    }
}
