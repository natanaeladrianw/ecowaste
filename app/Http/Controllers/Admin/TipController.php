<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TipController extends Controller
{
    public function index()
    {
        $tips = Tip::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.education.tips', compact('tips'));
    }

    public function create()
    {
        $tip = null;
        if (request()->has('edit')) {
            $tip = Tip::findOrFail(request()->edit);
        }
        return view('admin.education.tips-create', compact('tip'));
    }

    public function edit($id)
    {
        $tip = Tip::findOrFail($id);
        return view('admin.education.tips-create', compact('tip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tips', 'public');
        }

        $data['user_id'] = Auth::id();
        Tip::create($data);

        return redirect()->route('admin.education.tips.index')->with('success', 'Tips berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tip = Tip::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($tip->image) {
                Storage::disk('public')->delete($tip->image);
            }
            $data['image'] = $request->file('image')->store('tips', 'public');
        }

        $tip->update($data);

        return redirect()->route('admin.education.tips.index')->with('success', 'Tips berhasil diupdate!');
    }

    public function destroy($id)
    {
        $tip = Tip::findOrFail($id);
        
        if ($tip->image) {
            Storage::disk('public')->delete($tip->image);
        }
        
        $tip->delete();

        return redirect()->route('admin.education.tips.index')->with('success', 'Tips berhasil dihapus!');
    }
}
