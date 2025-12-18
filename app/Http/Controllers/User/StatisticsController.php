<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? today()->format('Y-m-d');
        $user = Auth::user();

        $wastes = Waste::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->with('category')
            ->get();

        $totalKg = $wastes->sum(function($waste) {
            return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
        });

        $totalPoints = $wastes->sum('points_earned');
        $totalTransactions = $wastes->count();

        return view('user.statistics.daily', compact('wastes', 'totalKg', 'totalPoints', 'totalTransactions', 'date'));
    }

    public function weekly(Request $request)
    {
        $week = $request->week ?? date('Y-\WW');
        $user = Auth::user();

        // Parse week
        [$year, $weekNum] = explode('-W', $week);
        $startDate = date('Y-m-d', strtotime($year . 'W' . str_pad($weekNum, 2, '0', STR_PAD_LEFT)));
        $endDate = date('Y-m-d', strtotime($startDate . ' +6 days'));

        $wastes = Waste::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->get();

        $totalKg = $wastes->sum(function($waste) {
            return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
        });

        $totalPoints = $wastes->sum('points_earned');
        $totalTransactions = $wastes->count();

        return view('user.statistics.weekly', compact('wastes', 'totalKg', 'totalPoints', 'totalTransactions', 'week'));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? date('Y-m');
        $user = Auth::user();

        $wastes = Waste::where('user_id', $user->id)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->with('category')
            ->get();

        $totalKg = $wastes->sum(function($waste) {
            return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
        });

        $totalPoints = $wastes->sum('points_earned');
        $totalTransactions = $wastes->count();

        // Category breakdown
        $categoryBreakdown = $wastes->groupBy('category_id')->map(function($group) {
            return [
                'category' => $group->first()->category->name ?? 'Unknown',
                'total_kg' => $group->sum(function($waste) {
                    return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
                }),
                'count' => $group->count(),
            ];
        });

        return view('user.statistics.monthly', compact('wastes', 'totalKg', 'totalPoints', 'totalTransactions', 'month', 'categoryBreakdown'));
    }
}
