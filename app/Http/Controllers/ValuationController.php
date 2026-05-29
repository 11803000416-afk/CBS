<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ValuationController extends Controller
{
    public function index(Request $request): View
    {
        $cars = [
            'maruti_alto' => ['name' => 'Maruti Alto', 'base_price' => 420000],
            'swift' => ['name' => 'Maruti Swift', 'base_price' => 780000],
            'i20' => ['name' => 'Hyundai i20', 'base_price' => 920000],
            'creta' => ['name' => 'Hyundai Creta', 'base_price' => 1650000],
            'city' => ['name' => 'Honda City', 'base_price' => 1350000],
            'seltos' => ['name' => 'Kia Seltos', 'base_price' => 1520000],
            'scorpio' => ['name' => 'Mahindra Scorpio', 'base_price' => 1820000],
            'fortuner' => ['name' => 'Toyota Fortuner', 'base_price' => 4600000],
            'xuv700' => ['name' => 'Mahindra XUV700', 'base_price' => 2450000],
            'custom' => ['name' => 'Custom / Other', 'base_price' => 1000000],
        ];

        $cities = [
            'Mumbai' => 1.05,
            'Delhi' => 1.04,
            'Bangalore' => 1.03,
            'Hyderabad' => 1.02,
            'Pune' => 1.02,
            'Kolkata' => 0.99,
            'Ahmedabad' => 1.0,
            'Other' => 1.0,
        ];

        $owners = [
            'first' => 'First owner',
            'second' => 'Second owner',
            'third' => 'Third owner',
            'four_plus' => 'Four or more',
        ];

        $year = (int) $request->input('year', now()->year);
        $selectedCar = $request->input('car', 'swift');
        $city = $request->input('city', 'Mumbai');
        $kilometers = max(0, (int) $request->input('kilometers', 0));
        $owner = $request->input('owner', 'first');
        $role = $request->input('role', 'seller');

        $car = $cars[$selectedCar] ?? $cars['custom'];
        $basePrice = (float) $car['base_price'];

        $currentYear = (int) now()->year;
        $age = max(0, $currentYear - $year);
        $yearFactor = max(0.45, 1 - ($age * 0.06));

        $kmPenalty = max(0, $kilometers - 20000);
        $kmFactor = max(0.55, 1 - (($kmPenalty / 10000) * 0.0125));

        $ownerFactors = [
            'first' => 1.00,
            'second' => 0.97,
            'third' => 0.93,
            'four_plus' => 0.88,
        ];

        $cityFactor = $cities[$city] ?? 1.0;
        $ownerFactor = $ownerFactors[$owner] ?? 1.0;

        $calculatedPrice = round($basePrice * $yearFactor * $kmFactor * $ownerFactor * $cityFactor);
        $adminCommission = round($calculatedPrice * 0.005, 2);
        $sellerPayout = round($calculatedPrice - $adminCommission, 2);

        return view('valuation.index', [
            'cars' => $cars,
            'cities' => $cities,
            'owners' => $owners,
            'year' => $year,
            'selectedCar' => $selectedCar,
            'city' => $city,
            'kilometers' => $kilometers,
            'owner' => $owner,
            'role' => $role,
            'car' => $car,
            'basePrice' => $basePrice,
            'calculatedPrice' => $calculatedPrice,
            'adminCommission' => $adminCommission,
            'sellerPayout' => $sellerPayout,
            'yearFactor' => $yearFactor,
            'kmFactor' => $kmFactor,
            'ownerFactor' => $ownerFactor,
            'cityFactor' => $cityFactor,
        ]);
    }
}
