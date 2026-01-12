<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use App\Models\Article;
use App\Models\Challenge;
use App\Models\Achievement;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    public function tips()
    {
        $tips = Tip::where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.education.tips', compact('tips'));
    }

    public function articles()
    {
        $articles = Article::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.education.articles', compact('articles'));
    }

    public function challenges()
    {
        $user = Auth::user();

        // Get all completed challenge source_ids for this user
        $completedChallengeIds = Achievement::where('user_id', $user->id)
            ->where('type', 'challenge')
            ->where('is_completed', true)
            ->whereNotNull('source_id')
            ->pluck('source_id')
            ->toArray();

        // Get active challenges that user has NOT completed yet
        $activeChallenges = Challenge::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereNotIn('id', $completedChallengeIds) // Exclude completed challenges by ID
            ->get();

        // Get user's progress for each active (not completed) challenge
        $challengesWithProgress = $activeChallenges->map(function ($challenge) use ($user) {
            $progress = Achievement::where('user_id', $user->id)
                ->where('type', 'challenge')
                ->where('source_id', $challenge->id)
                ->first();

            return [
                'challenge' => $challenge,
                'progress' => $progress,
            ];
        });

        // Get completed challenges (with challenge relationship)
        $completedChallenges = Achievement::where('user_id', $user->id)
            ->where('type', 'challenge')
            ->where('is_completed', true)
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function ($achievement) {
                // Load the related challenge data by source_id
                $challenge = Challenge::find($achievement->source_id);
                $achievement->challenge = $challenge;
                return $achievement;
            });

        return view('user.education.challenges', compact('challengesWithProgress', 'completedChallenges'));
    }

    public function completeChallenge(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);
        $user = Auth::user();

        // Check if already completed (using source_id to match)
        $existing = Achievement::where('user_id', $user->id)
            ->where('type', 'challenge')
            ->where('source_id', $challenge->id)
            ->where('is_completed', true)
            ->first();

        if ($existing) {
            return back()->with('error', 'Challenge sudah diselesaikan!');
        }

        // Verify challenge completion based on challenge type
        $completed = false;
        if ($challenge->target_category_id) {
            // Check waste collected for this category
            $totalWaste = \App\Models\Waste::where('user_id', $user->id)
                ->where('category_id', $challenge->target_category_id)
                ->whereBetween('date', [$challenge->start_date, $challenge->end_date])
                ->sum(\DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));

            $completed = $totalWaste >= $challenge->target_amount;
        }

        if ($completed) {
            // Create achievement with source_id
            Achievement::create([
                'user_id' => $user->id,
                'type' => 'challenge',
                'source_id' => $challenge->id,
                'title' => $challenge->title,
                'description' => 'Menyelesaikan challenge: ' . $challenge->title,
                'target_value' => $challenge->target_amount,
                'current_value' => $challenge->target_amount,
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            // Add points
            Point::create([
                'user_id' => $user->id,
                'points' => $challenge->points_reward,
                'source' => 'challenge',
                'source_id' => $challenge->id,
                'description' => 'Poin dari challenge: ' . $challenge->title,
                'type' => 'earned',
            ]);

            $user->total_points += $challenge->points_reward;
            $user->save();

            return back()->with('success', 'Challenge berhasil diselesaikan! Anda mendapat ' . $challenge->points_reward . ' poin.');
        }

        return back()->with('error', 'Challenge belum memenuhi syarat untuk diselesaikan.');
    }
}
