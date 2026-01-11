<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WasteTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wasteTypes = WasteType::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.waste-types.index', compact('wasteTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wasteType = null;
        if (request()->has('edit')) {
            $wasteType = WasteType::findOrFail(request()->edit);
        }
        return view('admin.waste-types.create', compact('wasteType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:waste_types,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['sort_order'] = $request->sort_order ?? 0;
        $data['is_active'] = $request->has('is_active');

        WasteType::create($data);

        return redirect()->route('admin.waste-types.index')->with('success', 'Tipe Sampah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $wasteType = WasteType::findOrFail($id);
        return view('admin.waste-types.create', compact('wasteType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $wasteType = WasteType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:waste_types,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['sort_order'] = $request->sort_order ?? 0;
        $data['is_active'] = $request->has('is_active');

        $wasteType->update($data);

        return redirect()->route('admin.waste-types.index')->with('success', 'Tipe Sampah berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wasteType = WasteType::findOrFail($id);

        // Check if there are categories using this waste type
        $categoriesCount = $wasteType->categories()->count();
        if ($categoriesCount > 0) {
            return redirect()->route('admin.waste-types.index')
                ->with('error', "Tidak dapat menghapus tipe sampah karena masih digunakan oleh {$categoriesCount} kategori!");
        }

        $wasteType->delete();

        return redirect()->route('admin.waste-types.index')->with('success', 'Tipe Sampah berhasil dihapus!');
    }
}
