<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard statistics for 1 hour
        $stats = Cache::remember('admin_dashboard_stats', 60 * 60, function () {
            return [
                'total_users' => User::count(),
                'total_passengers' => User::where('user_type', 'passenger')->count(),
                'total_drivers' => User::where('user_type', 'driver')->count(),
                'total_trips' => Trip::count(),
                'completed_trips' => Trip::where('status', 'completed')->count(),
                'pending_trips' => Trip::where('status', 'pending')->count(),
                'canceled_trips' => Trip::where('status', 'canceled')->count(),
                'total_revenue' => Payment::successful()->sum('amount'),
                'recent_users' => User::latest()->take(5)->get(),
                'recent_trips' => Trip::with(['passenger', 'driver'])->latest()->take(5)->get(),
            ];
        });

        return view('admin.dashboard', compact('stats'));
    }

    public function statistics()
    {
        // Monthly trip statistics for the last 12 months
        $monthlyTrips = Cache::remember('admin_monthly_trips', 60 * 60, function () {
            return DB::table('trips')
                ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, status'))
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month', 'status')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        });

        // Revenue statistics
        $monthlyRevenue = Cache::remember('admin_monthly_revenue', 60 * 60, function () {
            return DB::table('payments')
                ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total'))
                ->where('status', 'succeeded')
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        });

        // User growth
        $userGrowth = Cache::remember('admin_user_growth', 60 * 60, function () {
            return DB::table('users')
                ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, user_type'))
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month', 'user_type')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        });

        return view('admin.statistics', compact('monthlyTrips', 'monthlyRevenue', 'userGrowth'));
    }
}