<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rewards = Reward::orderBy('created_at', 'desc')->get();
        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reward = null;
        return view('admin.rewards.create', compact('reward'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_required' => 'required|integer|min:1',
            'type' => 'nullable|string|max:255',
            'value' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/rewards'), $filename);
            $data['image'] = 'uploads/rewards/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        Reward::create($data);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reward = Reward::findOrFail($id);
        return view('admin.rewards.create', compact('reward'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reward = Reward::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_required' => 'required|integer|min:1',
            'type' => 'nullable|string|max:255',
            'value' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($reward->image) {
                if (file_exists(public_path($reward->image))) {
                    unlink(public_path($reward->image));
                } elseif (Storage::disk('public')->exists($reward->image)) {
                    Storage::disk('public')->delete($reward->image);
                }
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/rewards'), $filename);
            $data['image'] = 'uploads/rewards/' . $filename;
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        $reward->update($data);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reward = Reward::findOrFail($id);

        // Delete image if exists
        if ($reward->image) {
            if (file_exists(public_path($reward->image))) {
                unlink(public_path($reward->image));
            } elseif (Storage::disk('public')->exists($reward->image)) {
                Storage::disk('public')->delete($reward->image);
            }
        }

        $reward->delete();

        return redirect()->route('admin.rewards.index')->with('success', 'Reward berhasil dihapus!');
    }
}

