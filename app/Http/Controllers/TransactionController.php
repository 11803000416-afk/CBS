<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = Transaction::with(['vehicle', 'buyer', 'seller', 'broker'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::where('status', '!=', 'sold')->orderBy('brand')->limit(100)->get();
        $buyers = Buyer::where('status', 'active')->orderBy('name')->limit(100)->get();
        $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();

        return view('transactions.form', ['transaction' => new Transaction(), 'vehicles' => $vehicles, 'buyers' => $buyers, 'sellers' => $sellers]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id', 'unique:transactions,vehicle_id'],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'seller_id' => ['required', 'exists:sellers,id'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'broker_commission' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:completed,cancelled'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $transaction = Transaction::create([
            ...$data,
            'broker_id' => $request->user()->id,
        ]);

        $transaction->vehicle->update([
            'status' => $transaction->status === 'completed' ? 'sold' : 'available',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction saved successfully.');
    }

    public function edit(Transaction $transaction): View
    {
        $vehicles = Vehicle::orderBy('brand')->limit(100)->get();
        $buyers = Buyer::where('status', 'active')->orderBy('name')->limit(100)->get();
        $sellers = Seller::where('status', 'active')->orderBy('name')->limit(100)->get();

        return view('transactions.form', compact('transaction', 'vehicles', 'buyers', 'sellers'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id', 'unique:transactions,vehicle_id,' . $transaction->id],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'seller_id' => ['required', 'exists:sellers,id'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'broker_commission' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:completed,cancelled'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $transaction->update($data);

        $transaction->vehicle->update([
            'status' => $transaction->status === 'completed' ? 'sold' : 'available',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $vehicle = $transaction->vehicle;

        $transaction->delete();

        if ($vehicle) {
            $vehicle->update(['status' => 'available']);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
