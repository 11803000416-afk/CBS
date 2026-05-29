<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = strtolower((string) ($user->role ?? ''));

        try {
            // Route to explicit role-based dashboards
            return match ($role) {
                User::ROLE_ADMIN => $this->adminDashboard(),
                User::ROLE_BROKER => $this->sellerDashboard(),
                User::ROLE_SELLER => $this->sellerDashboard(),
                User::ROLE_BUYER => $this->buyerDashboard(),
                default => $this->buyerDashboard(),
            };
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return view('dashboard.index', [
                'stats' => [
                    'vehicles' => 0,
                    'buyers' => 0,
                    'sellers' => 0,
                    'transactions' => 0,
                    'pending_inquiries' => 0,
                    'available_vehicles' => 0,
                    'sold_vehicles' => 0,
                ],
                'vehicleStatusData' => [],
                'inquiryStatusData' => [],
            ]);
        }
    }

    /**
     * Broker Dashboard - Brokerage performance and managed listings
     */
    protected function agentDashboard(): View
    {
        $user = auth()->user();

        $myVehicleQuery = Vehicle::where('created_by', $user->id);

        $stats = [
            'my_listings' => (clone $myVehicleQuery)->count(),
            'available' => (clone $myVehicleQuery)->where('status', 'available')->count(),
            'sold' => (clone $myVehicleQuery)->where('status', 'sold')->count(),
            'pending_inquiries' => Inquiry::whereHas('vehicle', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })->where('status', 'pending')->count(),
            'this_month_sales' => Transaction::where('broker_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_revenue' => Transaction::where('broker_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('broker_commission') ?? 0,
        ];

        $recentListings = (clone $myVehicleQuery)
            ->latest('id')
            ->limit(5)
            ->get();

        $recentInquiries = Inquiry::with('vehicle')
            ->whereHas('vehicle', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard.agent', [
            'stats' => $stats,
            'recentListings' => $recentListings,
            'recentInquiries' => $recentInquiries,
        ]);
    }

    /**
     * Admin Dashboard - Full system overview
     */
    protected function adminDashboard(): View
    {
        $payload = Cache::remember('dashboard:admin:v1', now()->addMinutes(2), function () {
            // Calculate total sale price from completed transactions
            $totalSalesPrice = Transaction::where('status', 'completed')->sum('sale_price') ?? 0;

            // Calculate admin revenue as 0.5% of total sales
            $adminRevenue = ($totalSalesPrice * 0.5) / 100;

            $stats = [
                'vehicles' => Vehicle::count(),
                'available_vehicles' => Vehicle::where('status', 'available')->count(),
                'sold_vehicles' => Vehicle::where('status', 'sold')->count(),
                'buyers' => Buyer::count(),
                'sellers' => Seller::count(),
                'total_transactions' => Transaction::count(),
                'completed_transactions' => Transaction::where('status', 'completed')->count(),
                'total_revenue' => $totalSalesPrice,
                'admin_revenue' => $adminRevenue,
                'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
            ];

            // Get featured vehicles
            $featuredVehicles = Vehicle::with(['seller', 'sellerRequest'])
                ->where('status', 'available')
                ->latest()
                ->take(6)
                ->get();

            return [
                'stats' => $stats,
                'featuredVehicles' => $featuredVehicles,
                'vehiclesByMonth' => $this->getVehiclesByMonth(),
                'transactionsByMonth' => $this->getTransactionsByMonth(),
                'vehicleStatusBreakdown' => $this->getVehicleStatusBreakdown(),
                'topSellers' => Seller::withCount('vehicles')->latest('vehicles_count')->limit(5)->get(),
            ];
        });

        return view('dashboard.admin', [
            'stats' => $payload['stats'],
            'featuredVehicles' => $payload['featuredVehicles'],
            'vehiclesByMonth' => $payload['vehiclesByMonth'],
            'transactionsByMonth' => $payload['transactionsByMonth'],
            'vehicleStatusBreakdown' => $payload['vehicleStatusBreakdown'],
            'topSellers' => $payload['topSellers'],
        ]);
    }

    /**
     * Seller Dashboard - Listing and transaction focus
     */
    protected function sellerDashboard(): View
    {
        $user = auth()->user();
        
        $stats = [
            'vehicles' => Vehicle::where('created_by', $user->id)->count(),
            'available_vehicles' => Vehicle::where('created_by', $user->id)->where('status', 'available')->count(),
            'sold_vehicles' => Vehicle::where('created_by', $user->id)->where('status', 'sold')->count(),
            'pending_inquiries' => Inquiry::whereHas('vehicle', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })->where('status', 'pending')->count(),
            'this_month_sales' => Transaction::whereHas('vehicle', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })->whereMonth('created_at', now()->month)->count(),
            'this_month_revenue' => Transaction::whereHas('vehicle', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })->where('status', 'completed')->whereMonth('created_at', now()->month)->sum('sale_price') ?? 0,
        ];

        $recentListings = Vehicle::where('created_by', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $recentInquiries = Inquiry::whereHas('vehicle', function($q) use ($user) {
            $q->where('created_by', $user->id);
        })->latest()->limit(5)->get();

        return view('dashboard.seller', [
            'stats' => $stats,
            'recentListings' => $recentListings,
            'recentInquiries' => $recentInquiries,
        ]);
    }

    /**
     * Buyer Dashboard - Bookings and inquiries focus
     */
    protected function buyerDashboard(): View
    {
        $user = auth()->user();
        
        // Find or create buyer record
        $buyer = Buyer::where('user_id', $user->id)->first();
        $buyerId = $buyer ? $buyer->id : null;
        
        $stats = [
            'bookings' => Booking::where('buyer_id', $user->id)->count(),
            'inquiries' => 0, // Inquiries don't track users directly
            'transactions' => $buyerId ? Transaction::where('buyer_id', $buyerId)->where('status', 'completed')->count() : 0,
        ];

        $approvedVehicles = Vehicle::with(['seller', 'sellerRequest'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        $availableVehiclesCount = Cache::remember(
            'dashboard:buyer:available_vehicle_count:v1',
            now()->addMinutes(2),
            fn () => Vehicle::where('status', 'available')->count()
        );

        return view('dashboard.buyer', [
            'stats' => $stats,
            'approvedVehicles' => $approvedVehicles,
            'availableVehiclesCount' => $availableVehiclesCount,
        ]);
    }

    /**
     * Get vehicles by month for chart
     */
    protected function getVehiclesByMonth()
    {
        $months = collect();
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Vehicle::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $months->push($date->format('M'));
            $data[] = $count;
        }

        return [
            'labels' => $months->toArray(),
            'data' => $data,
        ];
    }

    /**
     * Get transactions by month for chart
     */
    protected function getTransactionsByMonth()
    {
        $months = collect();
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('sale_price') ?? 0;
            
            $months->push($date->format('M'));
            $data[] = $revenue / 100000; // Convert to 100k units for readability
        }

        return [
            'labels' => $months->toArray(),
            'data' => $data,
        ];
    }

    /**
     * Get vehicle status breakdown
     */
    protected function getVehicleStatusBreakdown()
    {
        return [
            'labels' => ['Available', 'Sold', 'Pending'],
            'data' => [
                Vehicle::where('status', 'available')->count(),
                Vehicle::where('status', 'sold')->count(),
                Vehicle::where('status', 'pending')->count(),
            ],
        ];
    }
}
