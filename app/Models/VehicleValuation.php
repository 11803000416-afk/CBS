<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleValuation extends Model
{
    use HasFactory;

    protected $table = 'vehicle_valuations';

    protected $fillable = [
        'vehicle_id',
        'estimated_value',
        'base_price',
        'depreciation_adjustment',
        'mileage_adjustment',
        'age_adjustment',
        'confidence_score',
        'valuation_factors',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'base_price' => 'decimal:2',
        'depreciation_adjustment' => 'decimal:2',
        'mileage_adjustment' => 'decimal:2',
        'age_adjustment' => 'decimal:2',
        'confidence_score' => 'integer',
        'valuation_factors' => 'array',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Calculate AI-based vehicle valuation
     * Formula: Base Price - (Mileage × Depreciation) - Age Depreciation
     */
    public static function calculateValuation(Vehicle $vehicle)
    {
        $currentYear = now()->year;
        $vehicleAge = $currentYear - $vehicle->year;
        
        // Base market values for different brands
        $brandValues = [
            'Toyota' => 1200000,
            'Honda' => 1100000,
            'BMW' => 1800000,
            'Mercedes' => 2000000,
            'Ford' => 900000,
            'Dacia' => 600000,
            'Hyundai' => 800000,
            'Chevrolet' => 1000000,
        ];

        $basePrice = $brandValues[ucfirst($vehicle->brand)] ?? 1000000;

        // Mileage adjustment: 50 per km
        $mileageAdjustment = $vehicle->mileage * 0.00005; // ~50 per km

        // Age depreciation: 8% per year
        $ageAdjustment = $basePrice * ($vehicleAge * 0.08);

        // Estimated value
        $estimatedValue = $basePrice - $mileageAdjustment - $ageAdjustment;

        // Adjust for transmission & fuel type (premium for automatic)
        if ($vehicle->transmission === 'Automatic') {
            $estimatedValue *= 1.15; // 15% premium
        }

        // Adjust for fuel type
        if (in_array($vehicle->fuel, ['Hybrid', 'Electric'])) {
            $estimatedValue *= 1.10; // 10% premium
        }

        // Ensure value doesn't go negative
        $estimatedValue = max($estimatedValue, $basePrice * 0.3);

        // Calculate confidence score based on data completeness
        $confidenceScore = 85;
        if (empty($vehicle->description)) $confidenceScore -= 5;
        if ($vehicle->mileage === 0) $confidenceScore -= 10;

        // Create valuation record
        return self::updateOrCreate(
            ['vehicle_id' => $vehicle->id],
            [
                'estimated_value' => round($estimatedValue, 2),
                'base_price' => $basePrice,
                'depreciation_adjustment' => round($ageAdjustment, 2),
                'mileage_adjustment' => round($mileageAdjustment, 2),
                'age_adjustment' => round($ageAdjustment, 2),
                'confidence_score' => $confidenceScore,
                'valuation_factors' => [
                    'brand' => $vehicle->brand,
                    'year' => $vehicle->year,
                    'mileage' => $vehicle->mileage,
                    'transmission' => $vehicle->transmission,
                    'fuel' => $vehicle->fuel,
                    'age_years' => $vehicleAge,
                ],
            ]
        );
    }
}
