<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumPostLike;
use App\Models\Achievement;
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
}
