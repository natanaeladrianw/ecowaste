<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use App\Models\WasteCategory;
use App\Models\BankSampah;
use App\Models\Point;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WasteController extends Controller
{
    public function index()
    {
        $wastes = Waste::where('user_id', Auth::id())
            ->with(['category', 'bankSampah'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get total stats (all wastes, not just paginated)
        $totalWasteKg = Waste::where('user_id', Auth::id())
            ->get()
            ->sum(function ($w) {
                return $w->unit === 'gram' ? $w->amount / 1000 : $w->amount;
            });

        // Total points from approved wastes only
        $totalPointsFromWaste = Waste::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->sum('points_earned');

        // Get user's actual total points from database
        $userTotalPoints = Auth::user()->total_points ?? 0;

        $totalTransactions = Waste::where('user_id', Auth::id())->count();

        return view('user.waste.index', compact('wastes', 'totalWasteKg', 'totalPointsFromWaste', 'userTotalPoints', 'totalTransactions'));
    }

    public function create(Request $request)
    {
        $categories = WasteCategory::where('is_active', true)->get();
        $bankSampah = BankSampah::where('is_active', true)->orderBy('name')->get();

        // Get pre-selected category and challenge from query string
        $selectedCategoryId = $request->query('category_id');
        $challengeId = $request->query('challenge_id');

        // Get challenge info if coming from a challenge
        $challenge = null;
        if ($challengeId) {
            $challenge = \App\Models\Challenge::find($challengeId);
        }

        return view('user.waste.create', compact('categories', 'bankSampah', 'selectedCategoryId', 'challengeId', 'challenge'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'nullable',
            'category_id' => 'required|exists:waste_categories,id',
            'bank_sampah_id' => 'required|exists:bank_sampah,id',
            'amount' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,gram,unit,liter',
            'description' => 'nullable|string',
            'photo' => 'required|image|max:2048',
        ], [
            'bank_sampah_id.required' => 'Lokasi Bank Sampah wajib dipilih.',
            'bank_sampah_id.exists' => 'Lokasi Bank Sampah yang dipilih tidak valid.',
            'photo.required' => 'Foto sampah wajib diupload sebagai bukti.',
            'photo.image' => 'File yang diupload harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // Upload photo
        $photoPath = $request->file('photo')->store('wastes', 'public');

        // Calculate points and get category
        $category = WasteCategory::findOrFail($request->category_id);
        $amountInKg = $request->unit === 'gram' ? $request->amount / 1000 : $request->amount;
        $pointsEarned = (int) ($amountInKg * $category->points_per_kg);

        // Get waste type name from category
        $wasteTypeName = $category->name; // Using category name as type

        $waste = Waste::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'bank_sampah_id' => $request->bank_sampah_id,
            'type' => $wasteTypeName,
            'amount' => $request->amount,
            'unit' => $request->unit,
            'date' => $request->date,
            'time' => $request->time ?? now()->format('H:i'),
            'description' => $request->description,
            'photo' => $photoPath,
            'points_earned' => $pointsEarned,
            'status' => 'pending',
        ]);

        // Poin akan ditambahkan setelah admin menyetujui data sampah

        // Log activity
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity_type' => 'waste_input',
            'description' => 'Input sampah: ' . $wasteTypeName . ' ' . $request->amount . ' ' . $request->unit,
            'metadata' => ['waste_id' => $waste->id],
        ]);

        return redirect()->route('user.waste.index')->with('success', 'Data sampah berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $waste = Waste::where('user_id', Auth::id())->findOrFail($id);
        $categories = WasteCategory::where('is_active', true)->get();
        $bankSampah = BankSampah::where('is_active', true)->orderBy('name')->get();
        return view('user.waste.edit', compact('waste', 'categories', 'bankSampah'));
    }

    public function update(Request $request, $id)
    {
        $waste = Waste::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'time' => 'nullable',
            'category_id' => 'required|exists:waste_categories,id',
            'bank_sampah_id' => 'required|exists:bank_sampah,id',
            'amount' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,gram,unit,liter',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ], [
            'bank_sampah_id.required' => 'Lokasi Bank Sampah wajib dipilih.',
            'bank_sampah_id.exists' => 'Lokasi Bank Sampah yang dipilih tidak valid.',
        ]);

        // Upload new photo if exists
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($waste->photo) {
                Storage::disk('public')->delete($waste->photo);
            }
            $photoPath = $request->file('photo')->store('wastes', 'public');
            $waste->photo = $photoPath;
        }

        // Recalculate points if category or amount changed
        $oldPoints = $waste->points_earned;
        $oldStatus = $waste->status; // Save old status before update
        $category = WasteCategory::findOrFail($request->category_id);
        $amountInKg = $request->unit === 'gram' ? $request->amount / 1000 : $request->amount;
        $newPoints = (int) ($amountInKg * $category->points_per_kg);

        // Get waste type name from category
        $wasteTypeName = $category->name; // Using category name as type

        // If waste was previously approved or rejected, change status to pending for re-verification
        $newStatus = $oldStatus;
        if ($oldStatus === 'approved' || $oldStatus === 'rejected') {
            $newStatus = 'pending';

            // If previously approved, we need to handle points
            if ($oldStatus === 'approved') {
                // Remove points that were added when approved
                $point = Point::where('source', 'waste_approval')->where('source_id', $waste->id)->first();
                if ($point) {
                    $user = Auth::user();
                    $user->total_points = max(0, $user->total_points - $point->points);
                    $user->save();
                    $point->delete();
                }
            }
        }

        $waste->update([
            'category_id' => $request->category_id,
            'bank_sampah_id' => $request->bank_sampah_id,
            'type' => $wasteTypeName,
            'amount' => $request->amount,
            'unit' => $request->unit,
            'date' => $request->date,
            'time' => $request->time ?? $waste->time,
            'description' => $request->description,
            'points_earned' => $newPoints,
            'status' => $newStatus,
        ]);

        // Points will be added again when admin approves the updated waste entry

        // Log activity
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity_type' => 'waste_updated',
            'description' => 'Update sampah: ' . $wasteTypeName . ' ' . $request->amount . ' ' . $request->unit . ' (Status: ' . ($newStatus === 'pending' ? 'Menunggu verifikasi ulang' : $newStatus) . ')',
            'metadata' => ['waste_id' => $waste->id],
        ]);

        $message = ($newStatus === 'pending' && $oldStatus !== 'pending')
            ? 'Data sampah berhasil diupdate dan menunggu verifikasi ulang!'
            : 'Data sampah berhasil diupdate!';

        return redirect()->route('user.waste.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $waste = Waste::where('user_id', Auth::id())->findOrFail($id);

        // Delete photo
        if ($waste->photo) {
            Storage::disk('public')->delete($waste->photo);
        }

        // Remove points
        $point = Point::where('source', 'waste')->where('source_id', $waste->id)->first();
        if ($point) {
            $user = Auth::user();
            $user->total_points = max(0, $user->total_points - $point->points);
            $user->save();
            $point->delete();
        }

        $waste->delete();

        return redirect()->route('user.waste.index')->with('success', 'Data sampah berhasil dihapus!');
    }

    public function history()
    {
        $wastes = Waste::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.waste.history', compact('wastes'));
    }
}
