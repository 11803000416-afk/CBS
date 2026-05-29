<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API endpoints for vehicle details - Rate limited to prevent abuse
Route::middleware('throttle:60,1')->get('/vehicles/{vehicle}', function (\App\Models\Vehicle $vehicle) {
    return response()->json([
        'id' => $vehicle->id,
        'brand' => $vehicle->brand,
        'model' => $vehicle->model,
        'year' => $vehicle->year,
        'price' => $vehicle->price,
        'mileage' => $vehicle->mileage,
        'fuel_type' => $vehicle->fuel_type,
        'transmission' => $vehicle->transmission,
        'color' => $vehicle->color,
        'engine_capacity' => $vehicle->engine_capacity,
        'condition' => $vehicle->condition,
        'status' => $vehicle->status,
        'images' => $vehicle->images ?? [],
        'seller' => $vehicle->seller ? [
            'id' => $vehicle->seller->id,
            'name' => $vehicle->seller->name,
        ] : null,
    ]);
});
