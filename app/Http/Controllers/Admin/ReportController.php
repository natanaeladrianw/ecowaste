<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $type = $request->type ?? 'monthly';
        
        // Set date range based on type
        if ($type === 'daily') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($type === 'weekly') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($type === 'custom') {
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();
        } else { // monthly
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $wastes = Waste::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'category', 'bankSampah'])
            ->get();

        // Generate report data
        $reportData = [
            'period' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_waste' => $wastes->sum(function($waste) {
                return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
            }),
            'total_transactions' => $wastes->count(),
            'total_points' => $wastes->sum('points_earned'),
            'by_category' => $wastes->groupBy('category_id')->map(function($group) {
                return [
                    'category' => $group->first()->category->name ?? 'Unknown',
                    'total_kg' => $group->sum(function($waste) {
                        return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
                    }),
                    'count' => $group->count(),
                ];
            }),
        ];

        return view('admin.reports.generate', compact('reportData'));
    }

    public function export(Request $request)
    {
        $type = $request->type ?? 'monthly';
        
        // Set date range based on type
        if ($type === 'daily') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($type === 'weekly') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($type === 'custom') {
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();
        } else { // monthly
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $wastes = Waste::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'category', 'bankSampah'])
            ->orderBy('created_at', 'desc')
            ->get();

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
        $periodText = ucfirst($type);
        if ($type === 'custom' && $request->start_date && $request->end_date) {
            $periodText = 'Custom: ' . Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($request->end_date)->format('d/m/Y');
        } elseif ($type === 'daily') {
            $periodText = 'Harian: ' . Carbon::today()->format('d/m/Y');
        } elseif ($type === 'weekly') {
            $periodText = 'Mingguan: ' . Carbon::now()->startOfWeek()->format('d/m/Y') . ' - ' . Carbon::now()->endOfWeek()->format('d/m/Y');
        } elseif ($type === 'monthly') {
            $periodText = 'Bulanan: ' . Carbon::now()->format('F Y');
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
        
        $totalWaste = $wastes->sum(function($waste) {
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
        $filename = 'Laporan_Sampah_' . $type . '_' . date('Y-m-d_His') . '.xlsx';

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
}
