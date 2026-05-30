<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleComparison extends Model
{
    use HasFactory;

    protected $table = 'vehicle_comparisons';

    protected $fillable = [
        'user_id',
        'title',
        'vehicle_ids',
    ];

    protected $casts = [
        'vehicle_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return Vehicle::whereIn('id', $this->vehicle_ids)->get();
    }

    public function getComparisonData()
    {
        $vehicles = $this->vehicles();

        return [
            'title' => $this->title,
            'vehicles' => $vehicles->map(fn($v) => [
                'id' => $v->id,
                'brand' => $v->brand,
                'model' => $v->model,
                'year' => $v->year,
                'price' => $v->price,
                'mileage' => $v->mileage,
                'transmission' => $v->transmission,
                'fuel' => $v->fuel,
                'color' => $v->color,
                'condition' => $v->condition ?? 'Good',
                'image' => $v->image,
            ]),
        ];
    }

    public static function createComparison(int $userId, array $vehicleIds, ?string $title = null)
    {
        return self::create([
            'user_id' => $userId,
            'vehicle_ids' => $vehicleIds,
            'title' => $title ?? 'Comparison - ' . now()->format('M d'),
        ]);
    }
}
