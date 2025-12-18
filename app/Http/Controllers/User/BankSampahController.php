<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSampahController extends Controller
{
    public function index(Request $request)
    {
        $query = BankSampah::where('is_active', true);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
        }

        $bankSampah = $query->get();

        return view('user.bank-sampah.index', compact('bankSampah'));
    }

    public function map()
    {
        $bankSampah = BankSampah::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('user.bank-sampah.map', compact('bankSampah'));
    }

    public function show($id)
    {
        $bankSampah = BankSampah::findOrFail($id);
        return view('user.bank-sampah.detail', compact('bankSampah'));
    }

    public function route($id)
    {
        $bankSampah = BankSampah::findOrFail($id);
        // This would typically integrate with Google Maps API
        return view('user.bank-sampah.route', compact('bankSampah'));
    }
}
