<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,broker');
    }

    /**
     * Dashboard analytics
     */
    public function dashboard()
    {
        $isBroker = auth()->user()->hasRole('broker');

        if ($isBroker) {
            return $this->brokerAnalytics();
        }

        return $this->adminAnalytics();
    }

    /**
     * Admin analytics
     */
    private function adminAnalytics()
    {
        $stats = Analytics::getDashboardStats();

        $dailyStats = Analytics::where('date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->selectRaw('date, SUM(count) as total_count, SUM(COALESCE(revenue, 0)) as total_revenue')
            ->orderBy('date')
            ->get();

        $topVehicles = Vehicle::withCount('inquiries')
            ->where('status', 'sold')
            ->orderByDesc('inquiries_count')
            ->limit(10)
            ->get();

        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $activitySummary = ActivityLog::getActivitySummary(30);

        return view('analytics.admin', [
            'stats' => $stats,
            'dailyStats' => $dailyStats,
            'topVehicles' => $topVehicles,
            'userGrowth' => $userGrowth,
            'activitySummary' => $activitySummary,
        ]);
    }

    /**
     * Broker analytics
     */
    private function brokerAnalytics()
    {
        $brokerId = auth()->id();

        $myVehicles = Vehicle::where('broker_id', $brokerId)->count();
        $activeListing = Vehicle::where('broker_id', $brokerId)
            ->where('status', 'active')
            ->count();
        $soldVehicles = Vehicle::where('broker_id', $brokerId)
            ->where('status', 'sold')
            ->count();

        $totalInquiries = Vehicle::where('broker_id', $brokerId)
            ->withCount('inquiries')
            ->get()
            ->sum('inquiries_count');

        $transactions = Transaction::whereHas('vehicle', fn($q) => $q->where('broker_id', $brokerId))
            ->where('status', 'completed')
            ->sum('sale_price');

        $recentTransactions = Transaction::whereHas('vehicle', fn($q) => $q->where('broker_id', $brokerId))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('analytics.broker', [
            'my_vehicles' => $myVehicles,
            'active_listings' => $activeListing,
            'sold_vehicles' => $soldVehicles,
            'total_inquiries' => $totalInquiries,
            'total_revenue' => $transactions,
            'recent_transactions' => $recentTransactions,
        ]);
    }

    /**
     * Export analytics as PDF
     */
    public function exportPDF()
    {
        $stats = Analytics::getDashboardStats();

        // You would use a PDF library like DomPDF here
        // return PDF::loadView('analytics.pdf', $stats)->download('analytics.pdf');

        return response()->json($stats); // Placeholder
    }

    /**
     * Export analytics as Excel
     */
    public function exportExcel()
    {
        $stats = Analytics::getDashboardStats();

        // You would use a library like Laravel Excel here
        // return Excel::download(new AnalyticsExport($stats), 'analytics.xlsx');

        return response()->json($stats); // Placeholder
    }

    /**
     * Get metrics by date range
     */
    public function getMetricsByRange(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'metric_type' => 'required|string',
        ]);

        $metrics = Analytics::where('metric_type', $validated['metric_type'])
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->groupBy('metric_name')
            ->selectRaw('metric_name, SUM(count) as total_count, SUM(COALESCE(revenue, 0)) as total_revenue')
            ->get();

        return response()->json($metrics);
    }

    /**
     * Activity logs
     */
    public function activityLogs()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')
            ->paginate(50);

        return view('analytics.activity-logs', ['logs' => $logs]);
    }
}
