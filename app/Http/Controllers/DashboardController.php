<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Inquiry;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        try {
            // Only load scalar counts - absolutely no model instances
            $stats = [
                'vehicles' => Vehicle::count(),
                'buyers' => Buyer::count(),
                'sellers' => Seller::count(),
                'transactions' => Transaction::count(),
                'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
                'available_vehicles' => Vehicle::where('status', 'available')->count(),
                'sold_vehicles' => Vehicle::where('status', 'sold')->count(),
            ];

            return view('dashboard.index', [
                'stats' => $stats,
                'vehicleStatusData' => [],
                'inquiryStatusData' => [],
            ]);
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
}
