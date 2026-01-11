<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteCategory;
use App\Models\WasteType;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = WasteCategory::orderBy('name')->get();
        return view('admin.waste.categories', compact('categories'));
    }

    public function create()
    {
        $category = null;
        if (request()->has('edit')) {
            $category = WasteCategory::findOrFail(request()->edit);
        }
        $wasteTypes = WasteType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.waste.categories-create', compact('category', 'wasteTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:waste_categories,name',
            'waste_type' => 'nullable|exists:waste_types,slug',
            'description' => 'nullable|string',
            'points_per_kg' => 'required|integer|min:0',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        WasteCategory::create($data);

        return redirect()->route('admin.waste.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = WasteCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:waste_categories,name,' . $id,
            'waste_type' => 'nullable|exists:waste_types,slug',
            'description' => 'nullable|string',
            'points_per_kg' => 'required|integer|min:0',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $category->update($data);

        return redirect()->route('admin.waste.categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        $category = WasteCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.waste.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
