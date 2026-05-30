<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'metric_type',
        'metric_name',
        'count',
        'date',
        'user_id',
        'revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'revenue' => 'decimal:2',
    ];

    public static function recordMetric(
        string $metricType,
        string $metricName,
        ?int $userId = null,
        ?float $revenue = null,
    ) {
        $today = now()->toDateString();

        return self::updateOrCreate(
            [
                'metric_type' => $metricType,
                'metric_name' => $metricName,
                'date' => $today,
                'user_id' => $userId,
            ],
            [
                'count' => \DB::raw('count + 1'),
                'revenue' => $revenue,
            ]
        );
    }

    public static function getMetricSummary(string $metricType, ?int $days = 30)
    {
        return self::where('metric_type', $metricType)
            ->where('date', '>=', now()->subDays($days))
            ->groupBy('metric_name')
            ->selectRaw('metric_name, SUM(count) as total_count, SUM(COALESCE(revenue, 0)) as total_revenue')
            ->get();
    }

    public static function getDashboardStats()
    {
        return [
            'total_sales' => self::where('metric_type', 'sales')
                ->where('date', '>=', now()->subDays(30))
                ->sum('count'),
            'total_revenue' => self::where('metric_type', 'sales')
                ->where('date', '>=', now()->subDays(30))
                ->sum('revenue'),
            'total_inquiries' => self::where('metric_type', 'inquiries')
                ->where('date', '>=', now()->subDays(30))
                ->sum('count'),
            'total_views' => self::where('metric_type', 'vehicle_views')
                ->where('date', '>=', now()->subDays(30))
                ->sum('count'),
        ];
    }
}
