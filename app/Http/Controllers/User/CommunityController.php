<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumPostLike;
use App\Models\Achievement;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function forum()
    {
        $user = Auth::user();
        $posts = ForumPost::with(['user', 'comments', 'likes'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.community.forum', compact('posts', 'user'));
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string',
        ]);

        ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
        ]);

        return back()->with('success', 'Post berhasil dibuat!');
    }

    public function likePost($id)
    {
        $post = ForumPost::findOrFail($id);
        $user = Auth::user();

        // Check if user already liked this post
        $existingLike = ForumPostLike::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            // Unlike: Remove the like
            $existingLike->delete();
            $post->decrement('likes_count');
            $message = 'Post di-unlike';
        } else {
            // Like: Add the like
            ForumPostLike::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $post->increment('likes_count');
            $message = 'Post di-like';
        }

        return back()->with('success', $message);
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_comments,id',
        ]);

        ForumComment::create([
            'post_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        // Update post comments count
        $post = ForumPost::findOrFail($id);
        $post->increment('comments_count');

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function achievements()
    {
        $user = Auth::user();

        $achievements = Achievement::where('user_id', $user->id)
            ->orderBy('is_completed', 'desc')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('user.community.achievements', compact('achievements'));
    }

    public function shareAchievement($id)
    {
        $achievement = Achievement::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('is_completed', true)
            ->firstOrFail();

        // Create post content
        $title = 'Saya baru saja menyelesaikan tantangan: ' . $achievement->title . '!';
        $content = "Halo teman-teman komunitas! \n\nSaya sangat senang bisa berbagi bahwa saya baru saja menyelesaikan tantangan '" . $achievement->title . "'.\n\n" .
            ($achievement->description ? $achievement->description . "\n\n" : "") .
            "Ayo ikutan tantangan ini dan jaga lingkungan kita bersama! 🌱💪";

        // Check if already shared (optional, to prevent spam)
        // For now we allow multiple shares or maybe just create one

        ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $content,
            'category' => 'Pencapaian',
        ]);

        return redirect()->route('user.community.forum')->with('success', 'Pencapaian berhasil dibagikan ke komunitas!');
    }

    public function shareTransaction($id)
    {
        $waste = Waste::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('category')
            ->firstOrFail();

        // Format amount
        $amount = $waste->unit === 'gram' ? ($waste->amount / 1000) . ' kg' : $waste->amount . ' ' . $waste->unit;
        $categoryName = $waste->category ? $waste->category->name : 'Lainnya';

        // Create post content
        $title = 'Saya baru menyetor sampah ' . $categoryName . '!';
        $content = "Halo sobat EcoWaste! 👋\n\n" .
            "Hari ini saya baru saja menyetor sampah jenis *" . $categoryName . "* (" . $waste->type . ") sebanyak **" . $amount . "**.\n\n" .
            "Senang rasanya bisa berkontribusi menjaga lingkungan. Yuk, kalian juga jangan lupa setor sampah kalian! ♻️🌍\n\n" .
            "#EcoWaste #PeduliLingkungan #" . str_replace(' ', '', $categoryName);

        ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $content,
            'category' => 'Aktivitas',
        ]);

        return redirect()->route('user.community.forum')->with('success', 'Aktivitas berhasil dibagikan ke komunitas!');
    }
}
