<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuyerController extends Controller
{
    public function index(): View
    {
        $buyers = Buyer::latest()->paginate(10);

        return view('buyers.index', compact('buyers'));
    }

    public function create(): View
    {
        return view('buyers.form', ['buyer' => new Buyer()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:buyers,email'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Buyer::create($data);

        return redirect()->route('buyers.index')->with('success', 'Buyer created successfully.');
    }

    public function edit(Buyer $buyer): View
    {
        return view('buyers.form', compact('buyer'));
    }

    public function update(Request $request, Buyer $buyer): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:buyers,email,' . $buyer->id],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $buyer->update($data);

        return redirect()->route('buyers.index')->with('success', 'Buyer updated successfully.');
    }

    public function destroy(Buyer $buyer): RedirectResponse
    {
        $buyer->delete();

        return redirect()->route('buyers.index')->with('success', 'Buyer deleted successfully.');
    }
}
