<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleComparison;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show comparison page
     */
    public function index()
    {
        $comparisons = VehicleComparison::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('comparisons.index', ['comparisons' => $comparisons]);
    }

    /**
     * Create new comparison
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'vehicle_ids' => 'required|array|min:2|max:4',
            'vehicle_ids.*' => 'exists:vehicles,id',
            'title' => 'nullable|string|max:255',
        ]);

        $comparison = VehicleComparison::createComparison(
            auth()->id(),
            $validated['vehicle_ids'],
            $validated['title'] ?? null
        );

        return response()->json([
            'success' => true,
            'comparison' => $comparison,
            'redirect' => route('comparisons.show', $comparison->id),
        ]);
    }

    /**
     * Show comparison details
     */
    public function show(VehicleComparison $comparison)
    {
        if ($comparison->user_id !== auth()->id()) {
            return redirect()->route('comparisons.index')->with('error', 'Unauthorized');
        }

        $data = $comparison->getComparisonData();

        return view('comparisons.show', [
            'comparison' => $comparison,
            'vehicles' => $data['vehicles'],
            'title' => $data['title'],
        ]);
    }

    /**
     * Add vehicle to comparison
     */
    public function addVehicle(Request $request, VehicleComparison $comparison)
    {
        if ($comparison->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $vehicleId = $request->validate(['vehicle_id' => 'required|exists:vehicles,id'])['vehicle_id'];

        if (in_array($vehicleId, $comparison->vehicle_ids)) {
            return response()->json(['error' => 'Vehicle already in comparison'], 422);
        }

        if (count($comparison->vehicle_ids) >= 4) {
            return response()->json(['error' => 'Maximum 4 vehicles allowed'], 422);
        }

        $comparison->vehicle_ids = array_merge($comparison->vehicle_ids, [$vehicleId]);
        $comparison->save();

        return response()->json(['success' => true, 'comparison' => $comparison]);
    }

    /**
     * Remove vehicle from comparison
     */
    public function removeVehicle(Request $request, VehicleComparison $comparison)
    {
        if ($comparison->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $vehicleId = $request->validate(['vehicle_id' => 'required|integer'])['vehicle_id'];

        $comparison->vehicle_ids = array_filter(
            $comparison->vehicle_ids,
            fn($id) => $id !== $vehicleId
        );

        if (count($comparison->vehicle_ids) < 2) {
            $comparison->delete();
            return response()->json(['success' => true, 'deleted' => true]);
        }

        $comparison->save();

        return response()->json(['success' => true, 'comparison' => $comparison]);
    }

    /**
     * Delete comparison
     */
    public function delete(VehicleComparison $comparison)
    {
        if ($comparison->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comparison->delete();

        return response()->json(['success' => true, 'message' => 'Comparison deleted']);
    }

    /**
     * Quick compare 2-3 vehicles
     */
    public function quickCompare(Request $request)
    {
        $validated = $request->validate([
            'vehicle_ids' => 'required|array|min:2|max:4',
            'vehicle_ids.*' => 'exists:vehicles,id',
        ]);

        $vehicles = Vehicle::whereIn('id', $validated['vehicle_ids'])
            ->select('id', 'brand', 'model', 'year', 'price', 'mileage', 'transmission', 'fuel', 'color', 'image')
            ->get();

        return view('comparisons.quick', ['vehicles' => $vehicles]);
    }
}
