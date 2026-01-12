<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.education.articles', compact('articles'));
    }

    public function create()
    {
        $article = null;
        return view('admin.education.articles-create', compact('article'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.education.articles-create', compact('article'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/articles'), $filename);
            $data['image'] = 'uploads/articles/' . $filename;
        }

        Article::create($data);

        return redirect()->route('admin.education.articles.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($article->image && file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            } elseif ($article->image && Storage::disk('public')->exists($article->image)) {
                // Fallback cleanup for old storage way
                Storage::disk('public')->delete($article->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/articles'), $filename);
            $data['image'] = 'uploads/articles/' . $filename;
        }

        $article->update($data);

        return redirect()->route('admin.education.articles.index')->with('success', 'Artikel berhasil diupdate!');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->image) {
            if (file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            } elseif (Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
        }

        $article->delete();

        return redirect()->route('admin.education.articles.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
