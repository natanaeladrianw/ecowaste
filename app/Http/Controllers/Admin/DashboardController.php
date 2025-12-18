<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Waste;
use App\Models\BankSampah;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        
        $totalWaste = Waste::whereMonth('created_at', now()->month)
            ->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        
        $totalBankSampah = BankSampah::where('is_active', true)->count();
        
        $todayActivities = UserActivity::whereDate('created_at', today())->count();

        // Monthly trend data - get last 6 months
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $totalKg = Waste::whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
            
            $monthlyTrend[] = [
                'month' => $date->month,
                'month_name' => $date->format('M'),
                'total_kg' => $totalKg
            ];
        }

        // User distribution
        $activeUsers = User::whereHas('wastes', function($query) {
            $query->where('created_at', '>=', now()->subMonth());
        })->count();
        
        $newUsers = User::whereMonth('created_at', now()->month)->count();
        $inactiveUsers = max(0, $totalUsers - $activeUsers - $newUsers);

        // Recent activities
        $recentActivities = UserActivity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalWaste',
            'totalBankSampah',
            'todayActivities',
            'monthlyTrend',
            'activeUsers',
            'newUsers',
            'inactiveUsers',
            'recentActivities'
        ));
    }

    public function activities()
    {
        $activities = UserActivity::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.activities', compact('activities'));
    }
}
