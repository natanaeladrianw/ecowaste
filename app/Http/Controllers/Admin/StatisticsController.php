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
    public function index()
    {
        // Total waste statistics
        $totalWaste = Waste::sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        
        // Get all active waste types from database
        $wasteTypes = WasteType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        // Calculate waste statistics for each waste type dynamically
        $wasteTypeStats = [];
        foreach ($wasteTypes as $wasteType) {
            $wasteAmount = Waste::whereHas('category', function($query) use ($wasteType) {
                $query->where('waste_type', $wasteType->slug);
        })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
            
            $wasteTypeStats[] = [
                'type' => $wasteType,
                'amount' => $wasteAmount,
                'percentage' => $totalWaste > 0 ? ($wasteAmount / $totalWaste) * 100 : 0
            ];
        }
        
        // Keep backward compatibility for specific types (organik, anorganik)
        $organicType = $wasteTypes->where('slug', 'organik')->first();
        $anorganicType = $wasteTypes->where('slug', 'anorganik')->first();
        
        $organicWaste = 0;
        $anorganicWaste = 0;
        
        if ($organicType) {
            $organicWaste = Waste::whereHas('category', function($query) use ($organicType) {
                $query->where('waste_type', $organicType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        }
        
        if ($anorganicType) {
            $anorganicWaste = Waste::whereHas('category', function($query) use ($anorganicType) {
                $query->where('waste_type', $anorganicType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        }

        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::whereHas('wastes', function($query) {
            $query->where('created_at', '>=', now()->subMonth());
        })->count();
        
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

        // Category breakdown
        $categoryStats = WasteCategory::withCount('wastes')
            ->withCount(['wastes as total_kg' => function($query) {
            $query->select(DB::raw('SUM(CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END)'));
            }])
            ->get();

        return view('admin.statistics.index', compact(
            'totalWaste',
            'organicWaste',
            'anorganicWaste',
            'wasteTypes',
            'wasteTypeStats',
            'totalUsers',
            'activeUsers',
            'newUsersThisMonth',
            'categoryStats'
        ));
    }
}
