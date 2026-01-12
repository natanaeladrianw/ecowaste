<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use App\Models\WasteCategory;
use App\Models\BankSampah;
use App\Models\Point;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;

class WasteController extends Controller
{
    public function reports(Request $request)
    {
        $period = $request->period ?? 'daily';
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $bankSampahId = $request->bank_sampah_id;

        $query = Waste::with(['user', 'category', 'bankSampah']);

        // Filter berdasarkan bank sampah
        if ($bankSampahId) {
            $query->where('bank_sampah_id', $bankSampahId);
        }

        // Jika ada custom date range, gunakan itu
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
            $period = 'custom';
        } elseif ($period === 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month);
        }

        $wastes = $query->orderBy('created_at', 'desc')->paginate(20);

        $totalWaste = $wastes->sum(function ($waste) {
            return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
        });

        $totalPoints = $wastes->sum('points_earned');
        $totalTransactions = $wastes->count();

        // Get all bank sampah for filter dropdown
        $bankSampahList = BankSampah::where('is_active', true)->orderBy('name')->get();

        return view('admin.waste.reports', compact('wastes', 'totalWaste', 'totalPoints', 'totalTransactions', 'period', 'startDate', 'endDate', 'bankSampahList', 'bankSampahId'));
    }

    public function export(Request $request)
    {
        $period = $request->period ?? 'daily';
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $bankSampahId = $request->bank_sampah_id;

        $query = Waste::with(['user', 'category', 'bankSampah']);

        // Filter berdasarkan bank sampah
        if ($bankSampahId) {
            $query->where('bank_sampah_id', $bankSampahId);
        }

        // Jika ada custom date range, gunakan itu
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
            $period = 'custom';
        } elseif ($period === 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month);
        }

        $wastes = $query->orderBy('created_at', 'desc')->get();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setTitle('Laporan Sampah');

        // Header Row
        $sheet->setCellValue('A1', 'LAPORAN DATA SAMPAH');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension('1')->setRowHeight(25);

        // Period Info
        $periodText = ucfirst($period);
        if ($startDate && $endDate) {
            $periodText = 'Custom: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
        }
        $sheet->setCellValue('A2', 'Periode: ' . $periodText);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getRowDimension('2')->setRowHeight(20);

        // Empty row
        $sheet->getRowDimension('3')->setRowHeight(10);

        // Table Headers
        $headers = ['No', 'Tanggal', 'User', 'Jenis Sampah', 'Berat (kg)', 'Lokasi Bank Sampah', 'Poin'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF28a745');
            $sheet->getStyle($col . '4')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle($col . '4')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $sheet->getRowDimension('4')->setRowHeight(25);

        // Data Rows
        $row = 5;
        $no = 1;
        foreach ($wastes as $waste) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $waste->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $waste->user->name ?? 'N/A');
            $sheet->setCellValue('D' . $row, $waste->category->name ?? 'N/A');

            $weight = $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
            $sheet->setCellValue('E' . $row, $weight);
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            $bankSampahName = $waste->bankSampah ? ($waste->bankSampah->name . ' - ' . ($waste->bankSampah->location_name ?? Str::limit($waste->bankSampah->address, 40))) : '-';
            $sheet->setCellValue('F' . $row, $bankSampahName);

            $sheet->setCellValue('G' . $row, $waste->points_earned ?? 0);

            // Add borders
            foreach (range('A', 'G') as $col) {
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            }

            $row++;
        }

        // Summary Row
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $totalWaste = $wastes->sum(function ($waste) {
            return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
        });
        $sheet->setCellValue('E' . $row, $totalWaste);
        $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);

        $sheet->setCellValue('F' . $row, 'Total Transaksi: ' . $wastes->count());
        $sheet->getStyle('F' . $row)->getFont()->setBold(true);

        $totalPoints = $wastes->sum('points_earned');
        $sheet->setCellValue('G' . $row, $totalPoints);
        $sheet->getStyle('G' . $row)->getFont()->setBold(true);

        // Style summary row
        $sheet->getStyle('A' . $row . ':G' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF0F0F0');
        foreach (range('A', 'G') as $col) {
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(35);
        $sheet->getColumnDimension('G')->setWidth(12);

        // Generate filename
        $filename = 'Laporan_Sampah_' . $period . '_' . date('Y-m-d_His') . '.xlsx';

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);

        // Return download response with proper headers
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $period = $request->period ?? 'daily';
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $bankSampahId = $request->bank_sampah_id;

        $query = Waste::with(['user', 'category', 'bankSampah']);

        // Filter berdasarkan bank sampah
        if ($bankSampahId) {
            $query->where('bank_sampah_id', $bankSampahId);
        }

        // Jika ada custom date range, gunakan itu
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
            $period = 'custom';
        } elseif ($period === 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month);
        }

        $wastes = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals
        $totalWaste = $wastes->sum(function ($waste) {
            return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
        });
        $totalPoints = $wastes->sum('points_earned');
        $totalTransactions = $wastes->count();

        // Period text
        $periodText = ucfirst($period);
        if ($startDate && $endDate) {
            $periodText = 'Custom: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
        } elseif ($period === 'daily') {
            $periodText = 'Harian: ' . Carbon::today()->format('d/m/Y');
        } elseif ($period === 'weekly') {
            $periodText = 'Mingguan: ' . Carbon::now()->startOfWeek()->format('d/m/Y') . ' - ' . Carbon::now()->endOfWeek()->format('d/m/Y');
        } elseif ($period === 'monthly') {
            $periodText = 'Bulanan: ' . Carbon::now()->format('F Y');
        }

        $data = [
            'wastes' => $wastes,
            'periodText' => $periodText,
            'totalWaste' => $totalWaste,
            'totalPoints' => $totalPoints,
            'totalTransactions' => $totalTransactions,
            'generatedAt' => Carbon::now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('admin.waste.pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Laporan_Sampah_' . $period . '_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function approve($id)
    {
        $waste = Waste::findOrFail($id);

        // Only approve if status is pending
        if ($waste->status !== 'pending') {
            return redirect()->back()->with('error', 'Status data sampah ini sudah tidak dapat diubah.');
        }

        $waste->update(['status' => 'approved']);

        // Add points to user if not already added
        if ($waste->points_earned > 0) {
            $user = $waste->user;
            if ($user) {
                // Check if points already added
                $pointExists = Point::where('user_id', $user->id)
                    ->where('source', 'waste')
                    ->where('source_id', $waste->id)
                    ->exists();

                if (!$pointExists) {
                    Point::create([
                        'user_id' => $user->id,
                        'points' => $waste->points_earned,
                        'source' => 'waste',
                        'source_id' => $waste->id,
                        'description' => 'Poin dari input sampah: ' . $waste->type,
                        'type' => 'earned',
                    ]);

                    // Update user total points
                    $user->total_points += $waste->points_earned;
                    $user->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Data sampah berhasil disetujui dan poin telah ditambahkan.');
    }

    public function reject($id)
    {
        $waste = Waste::findOrFail($id);

        // Only reject if status is pending
        if ($waste->status !== 'pending') {
            return redirect()->back()->with('error', 'Status data sampah ini sudah tidak dapat diubah.');
        }

        $waste->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Data sampah ditolak.');
    }

    public function update(Request $request, $id)
    {
        $waste = Waste::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:waste_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,gram',
            'points_earned' => 'required|integer|min:0',
            'bank_sampah_id' => 'nullable|exists:bank_sampah,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $oldPointsEarned = $waste->points_earned;
        $oldStatus = $waste->status;

        $waste->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'unit' => $request->unit,
            'points_earned' => $request->points_earned,
            'bank_sampah_id' => $request->bank_sampah_id,
            'status' => $request->status,
        ]);

        // Handle point changes if status is approved
        if ($waste->status === 'approved' && $waste->user) {
            $pointDiff = $waste->points_earned - $oldPointsEarned;

            if ($pointDiff != 0) {
                // Update existing point record or create new one
                $point = Point::where('user_id', $waste->user_id)
                    ->where('source', 'waste')
                    ->where('source_id', $waste->id)
                    ->first();

                if ($point) {
                    $point->update(['points' => $waste->points_earned]);
                } else {
                    Point::create([
                        'user_id' => $waste->user_id,
                        'points' => $waste->points_earned,
                        'source' => 'waste',
                        'source_id' => $waste->id,
                        'description' => 'Poin dari input sampah: ' . $waste->type,
                        'type' => 'earned',
                    ]);
                }

                // Update user total points
                $waste->user->total_points += $pointDiff;
                $waste->user->save();
            }
        }

        return redirect()->back()->with('success', 'Data laporan berhasil diperbarui.');
    }
}
