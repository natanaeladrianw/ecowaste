<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use App\Models\Point;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Today's waste
        $todayWaste = Waste::where('user_id', $user->id)
            ->whereDate('date', today())
            ->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

        // Total points
        $totalPoints = $user->total_points;

        // Recent activities
        $recentActivities = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistics for 7 days chart - get data per day
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->format('D');
            $dayShort = [
                'Mon' => 'Sen',
                'Tue' => 'Sel',
                'Wed' => 'Rab',
                'Thu' => 'Kam',
                'Fri' => 'Jum',
                'Sat' => 'Sab',
                'Sun' => 'Min'
            ][$dayName] ?? $dayName;
            
            $last7Days->push([
                'date' => $date->format('Y-m-d'),
                'day_name' => $dayName,
                'day_short' => $dayShort,
                'total_kg' => 0
            ]);
        }

        // Get waste data for last 7 days grouped by date
        $wasteStats = Waste::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->selectRaw('DATE(created_at) as date, 
                        SUM(CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END) as total_kg')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy(function($item) {
                return $item->date;
            });

        // Merge with last7Days to fill in missing dates with 0
        $chartData = $last7Days->map(function($day) use ($wasteStats) {
            $dateKey = $day['date'];
            if ($wasteStats->has($dateKey)) {
                $day['total_kg'] = (float)$wasteStats[$dateKey]->total_kg;
            }
            return $day;
        });

        // Category distribution
        $categoryStats = Waste::where('user_id', $user->id)
            ->join('waste_categories', 'wastes.category_id', '=', 'waste_categories.id')
            ->select('waste_categories.name', 
                    'waste_categories.color',
                    DB::raw('SUM(CASE WHEN wastes.unit = "gram" THEN wastes.amount / 1000 ELSE wastes.amount END) as total_kg'))
            ->groupBy('waste_categories.name', 'waste_categories.color')
            ->get();

        return view('user.dashboard', compact(
            'user',
            'todayWaste',
            'totalPoints',
            'recentActivities',
            'chartData',
            'categoryStats'
        ));
    }
}
