<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankSampahController extends Controller
{
    public function index(Request $request)
    {
        $query = BankSampah::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        $bankSampah = $query->orderBy('name')->paginate(15);
        
        return view('admin.bank-sampah.index', compact('bankSampah'));
    }

    public function create()
    {
        return view('admin.bank-sampah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'operating_hours' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'accepted_categories' => 'nullable|array',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('bank-sampah', 'public');
        }

        BankSampah::create($data);

        return redirect()->route('admin.bank-sampah.index')->with('success', 'Bank Sampah berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bankSampah = BankSampah::findOrFail($id);
        return view('admin.bank-sampah.edit', compact('bankSampah'));
    }

    public function update(Request $request, $id)
    {
        $bankSampah = BankSampah::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'operating_hours' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'accepted_categories' => 'nullable|array',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($bankSampah->photo) {
                Storage::disk('public')->delete($bankSampah->photo);
            }
            $data['photo'] = $request->file('photo')->store('bank-sampah', 'public');
        }

        $bankSampah->update($data);

        return redirect()->route('admin.bank-sampah.index')->with('success', 'Bank Sampah berhasil diupdate!');
    }

    public function destroy($id)
    {
        $bankSampah = BankSampah::findOrFail($id);
        
        if ($bankSampah->photo) {
            Storage::disk('public')->delete($bankSampah->photo);
        }
        
        $bankSampah->delete();

        return redirect()->route('admin.bank-sampah.index')->with('success', 'Bank Sampah berhasil dihapus!');
    }
}
