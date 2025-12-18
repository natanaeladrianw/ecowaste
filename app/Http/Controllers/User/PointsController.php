<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $points = Point::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.points.index', compact('user', 'points'));
    }

    public function rewards()
    {
        $user = Auth::user();
        $rewards = Reward::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('points_required', 'asc')
            ->get();

        return view('user.points.rewards', compact('user', 'rewards'));
    }

    public function claimReward(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);
        $user = Auth::user();

        if ($user->total_points < $reward->points_required) {
            return back()->with('error', 'Poin tidak cukup!');
        }

        if ($reward->stock <= 0) {
            return back()->with('error', 'Reward sudah habis!');
        }

        // Deduct points
        Point::create([
            'user_id' => $user->id,
            'points' => -$reward->points_required,
            'source' => 'reward',
            'source_id' => $reward->id,
            'description' => 'Claim reward: ' . $reward->name,
            'type' => 'spent',
        ]);

        $user->total_points -= $reward->points_required;
        $user->save();

        // Reduce stock
        $reward->stock -= 1;
        $reward->save();

        // Save reward claim history (optional - if table exists)
        try {
            RewardClaim::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_used' => $reward->points_required,
                'status' => 'completed',
                'claimed_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Table might not exist yet, but reward claim is still successful
            // Log error if needed: \Log::warning('Reward claim history not saved: ' . $e->getMessage());
        }

        return back()->with('success', 'Reward berhasil di-claim!');
    }
}
