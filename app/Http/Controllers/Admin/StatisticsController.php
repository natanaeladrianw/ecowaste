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
        $totalWaste = Waste::sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

        // Get all active waste types from database with pagination and search
        $query = WasteType::where('is_active', true);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $wasteTypes = $query->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(5);

        // Calculate waste statistics for each waste type dynamically
        $wasteTypeStats = $wasteTypes->through(function ($wasteType) use ($totalWaste) {
            $wasteAmount = Waste::whereHas('category', function ($query) use ($wasteType) {
                $query->where('waste_type', $wasteType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

            return [
                'type' => $wasteType,
                'amount' => $wasteAmount,
                'percentage' => $totalWaste > 0 ? ($wasteAmount / $totalWaste) * 100 : 0
            ];
        });

        // Keep backward compatibility for specific types (organik, anorganik)
        // Note: For backward compatibility, we need to fetch all organic/anorganic types, not just the paginated ones.
        // This assumes 'organik' and 'anorganik' are unique slugs and we want their total, not just what's on the current page.
        $organicType = WasteType::where('slug', 'organik')->first();
        $anorganicType = WasteType::where('slug', 'anorganik')->first();

        $organicWaste = 0;
        $anorganicWaste = 0;

        if ($organicType) {
            $organicWaste = Waste::whereHas('category', function ($query) use ($organicType) {
                $query->where('waste_type', $organicType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        }

        if ($anorganicType) {
            $anorganicWaste = Waste::whereHas('category', function ($query) use ($anorganicType) {
                $query->where('waste_type', $anorganicType->slug);
            })->sum(DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
        }

        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::whereHas('wastes', function ($query) {
            $query->where('created_at', '>=', now()->subMonth());
        })->count();

        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

        // Category breakdown
        $categoryStats = WasteCategory::withCount('wastes')
            ->withCount([
                'wastes as total_kg' => function ($query) {
                    $query->select(DB::raw('SUM(CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END)'));
                }
            ])
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
