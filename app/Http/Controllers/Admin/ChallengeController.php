<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\WasteCategory;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        $query = Challenge::with('targetCategory');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'active') {
                $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status == 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($request->status == 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        $challenges = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.education.challenges', compact('challenges'));
    }

    public function create()
    {
        $challenge = null;
        $categories = WasteCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.education.challenges-create', compact('challenge', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:daily,weekly,monthly',
            'target_amount' => 'nullable|integer|min:1',
            'target_unit' => 'nullable|string|max:50',
            'target_category_id' => 'nullable|exists:waste_categories,id',
            'points_reward' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        Challenge::create($data);

        return redirect()->route('admin.education.challenges.index')->with('success', 'Challenge berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        $categories = WasteCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.education.challenges-create', compact('challenge', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:daily,weekly,monthly',
            'target_amount' => 'nullable|integer|min:1',
            'target_unit' => 'nullable|string|max:50',
            'target_category_id' => 'nullable|exists:waste_categories,id',
            'points_reward' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $challenge->update($data);

        return redirect()->route('admin.education.challenges.index')->with('success', 'Challenge berhasil diupdate!');
    }

    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);
        $challenge->delete();

        return redirect()->route('admin.education.challenges.index')->with('success', 'Challenge berhasil dihapus!');
    }
}

