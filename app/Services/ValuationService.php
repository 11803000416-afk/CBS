<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\VehicleValuation;

class ValuationService
{
    /**
     * Calculate vehicle value based on market data
     */
    public function calculateValuation(Vehicle $vehicle)
    {
        return VehicleValuation::calculateValuation($vehicle);
    }

    /**
     * Get valuation for vehicle
     */
    public function getValuation(int $vehicleId)
    {
        return VehicleValuation::where('vehicle_id', $vehicleId)->first();
    }

    /**
     * Get valuation with confidence percentage
     */
    public function getValuationWithConfidence(int $vehicleId)
    {
        $valuation = $this->getValuation($vehicleId);

        if (! $valuation) {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $valuation = $this->calculateValuation($vehicle);
        }

        return [
            'estimated_value' => $valuation->estimated_value,
            'confidence_score' => $valuation->confidence_score,
            'factors' => $valuation->valuation_factors,
            'depreciation' => $valuation->depreciation_adjustment,
            'mileage_impact' => $valuation->mileage_adjustment,
        ];
    }

    /**
     * Compare vehicle value with market average
     */
    public function compareWithMarket(int $vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $valuation = $this->getValuation($vehicleId);

        // Get market average for same brand/model
        $marketAverage = Vehicle::where('brand', $vehicle->brand)
            ->where('model', $vehicle->model)
            ->whereIn('status', ['active', 'sold'])
            ->avg('price');

        $listingPrice = $vehicle->price;
        $difference = $listingPrice - ($valuation->estimated_value ?? $marketAverage);
        $percentageDiff = ($difference / ($valuation->estimated_value ?? $marketAverage)) * 100;

        return [
            'listing_price' => $listingPrice,
            'estimated_value' => $valuation->estimated_value ?? $marketAverage,
            'market_average' => $marketAverage,
            'difference' => round($difference, 2),
            'percentage_difference' => round($percentageDiff, 2),
            'status' => $percentageDiff > 5 ? 'overpriced' : ($percentageDiff < -5 ? 'underpriced' : 'fair_price'),
        ];
    }

    /**
     * Get depreciation forecast
     */
    public function getDepreciationForecast(Vehicle $vehicle): array
    {
        $valuation = $this->getValuation($vehicle->id) ?? $this->calculateValuation($vehicle);

        $forecast = [];
        $currentValue = $valuation->estimated_value;

        for ($year = 1; $year <= 5; $year++) {
            $depreciation = $currentValue * 0.08; // 8% per year
            $currentValue -= $depreciation;

            $forecast[] = [
                'year' => $year,
                'estimated_value' => round($currentValue, 2),
                'depreciation' => round($depreciation, 2),
            ];
        }

        return $forecast;
    }
}
