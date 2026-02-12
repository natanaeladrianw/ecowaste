<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use App\Models\User;
use App\Models\WasteCategory;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Total waste statistics
        $totalWaste = Waste::query()->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

        // Get all active waste types from database with pagination and search
        $query = WasteType::where('is_active', true);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $wasteTypes = $query->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(5);

        // Calculate waste statistics for each waste type dynamically
        $wasteTypeStats = collect($wasteTypes->items())->map(function ($wasteType) use ($totalWaste) {
            $wasteAmount = Waste::query()->whereHas('category', function ($query) use ($wasteType) {
                $query->where('waste_type', $wasteType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

            return [
                'type' => $wasteType,
                'amount' => $wasteAmount,
                'percentage' => $totalWaste > 0 ? ($wasteAmount / $totalWaste) * 100 : 0
            ];
        });

        // Keep backward compatibility for specific types (organik, anorganik)
        $organicType = WasteType::where('slug', 'organik')->first();
        $anorganicType = WasteType::where('slug', 'anorganik')->first();

        $organicWaste = 0;
        $anorganicWaste = 0;

        if ($organicType) {
            $organicWaste = Waste::query()->whereHas('category', function ($query) use ($organicType) {
                $query->where('waste_type', $organicType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        }

        if ($anorganicType) {
            $anorganicWaste = Waste::query()->whereHas('category', function ($query) use ($anorganicType) {
                $query->where('waste_type', $anorganicType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        }

        // User statistics
        $totalUsers = User::query()->count();
        $activeUsers = User::query()->whereHas('wastes', function ($query) {
            $query->where('created_at', '>=', now()->subMonth());
        })->count();

        $newUsersThisMonth = User::query()->whereMonth('created_at', now()->month)->count();

        // Category breakdown
        $categoryStats = WasteCategory::withCount('wastes')
            ->withCount([
                'wastes as total_kg' => function ($query) {
                    $query->select(DB::raw('SUM(CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END)'));
                }
            ])
            ->get();

        // Daily statistics for chart (last 7 days)
        $dailyStats = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayWaste = Waste::query()->whereDate('created_at', $date->toDateString())
                ->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

            $dayUsers = User::query()->whereDate('created_at', $date->toDateString())->count();

            $dayTransactions = Waste::query()->whereDate('created_at', $date->toDateString())->count();

            $dailyStats->push([
                'label' => $date->format('D, d M'),
                'waste' => round($dayWaste, 2),
                'users' => $dayUsers,
                'transactions' => $dayTransactions,
            ]);
        }

        // Weekly statistics for chart (last 4 weeks)
        $weeklyStats = collect();
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            $weekWaste = Waste::query()->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

            $weekUsers = User::query()->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

            $weekTransactions = Waste::query()->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

            $weeklyStats->push([
                'label' => $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M'),
                'waste' => round($weekWaste, 2),
                'users' => $weekUsers,
                'transactions' => $weekTransactions,
            ]);
        }

        // Monthly statistics for chart (last 6 months)
        $monthlyStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthWaste = Waste::query()->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

            $monthUsers = User::query()->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthTransactions = Waste::query()->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyStats->push([
                'label' => $date->format('M Y'),
                'waste' => round($monthWaste, 2),
                'users' => $monthUsers,
                'transactions' => $monthTransactions,
            ]);
        }

        return view('admin.statistics.index', compact(
            'totalWaste',
            'organicWaste',
            'anorganicWaste',
            'wasteTypes',
            'wasteTypeStats',
            'totalUsers',
            'activeUsers',
            'newUsersThisMonth',
            'categoryStats',
            'dailyStats',
            'weeklyStats',
            'monthlyStats'
        ));
    }
}
