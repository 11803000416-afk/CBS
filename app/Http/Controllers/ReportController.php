<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $summary = [
            'total_vehicles' => Vehicle::count(),
            'vehicles_sold' => Vehicle::where('status', 'sold')->count(),
            'total_sales' => Transaction::where('status', 'completed')->sum('sale_price'),
            'total_commission' => Transaction::where('status', 'completed')->sum('broker_commission'),
        ];

        $monthlySales = Transaction::selectRaw('DATE_FORMAT(completed_at, "%Y-%m") as month, SUM(sale_price) as total')
            ->whereNotNull('completed_at')
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE_FORMAT(completed_at, "%Y-%m")'))
            ->orderBy('month')
            ->get();

        $brokerCommission = Transaction::selectRaw('users.name as broker_name, SUM(transactions.broker_commission) as commission_total')
            ->join('users', 'users.id', '=', 'transactions.broker_id')
            ->where('transactions.status', 'completed')
            ->groupBy('users.name')
            ->orderByDesc('commission_total')
            ->get();

        return view('reports.index', compact('summary', 'monthlySales', 'brokerCommission'));
    }
}
