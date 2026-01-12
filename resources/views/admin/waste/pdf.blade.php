<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sampah</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #28a745;
        }

        .header h1 {
            font-size: 20px;
            color: #28a745;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .meta-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .meta-info .left,
        .meta-info .right {
            display: table-cell;
            width: 50%;
        }

        .meta-info .left {
            text-align: left;
        }

        .meta-info .right {
            text-align: right;
        }

        .summary-cards {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-cards table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-cards td {
            padding: 10px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .summary-cards .card-value {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }

        .summary-cards .card-label {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th {
            background-color: #28a745;
            color: white;
            padding: 8px 5px;
            font-size: 9px;
            text-align: left;
            border: 1px solid #28a745;
        }

        .data-table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .total-row {
            background-color: #f0f0f0 !important;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🌿 LAPORAN DATA SAMPAH</h1>
        <p>EcoWaste - Sistem Pengelolaan Sampah</p>
    </div>

    <div class="meta-info">
        <div class="left">
            <strong>Periode:</strong> {{ $periodText }}
        </div>
        <div class="right">
            <strong>Dicetak:</strong> {{ $generatedAt }}
        </div>
    </div>

    <div class="summary-cards">
        <table>
            <tr>
                <td>
                    <div class="card-value">{{ number_format($totalWaste, 2) }} kg</div>
                    <div class="card-label">Total Sampah</div>
                </td>
                <td>
                    <div class="card-value">{{ number_format($totalTransactions) }}</div>
                    <div class="card-label">Total Transaksi</div>
                </td>
                <td>
                    <div class="card-value">{{ number_format($totalPoints) }}</div>
                    <div class="card-label">Total Poin Diberikan</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 15%;">User</th>
                <th style="width: 12%;">Jenis Sampah</th>
                <th class="text-center" style="width: 10%;">Berat (kg)</th>
                <th style="width: 20%;">Bank Sampah</th>
                <th class="text-center" style="width: 8%;">Poin</th>
                <th class="text-center" style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wastes as $index => $waste)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $waste->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        {{ $waste->user->name ?? 'N/A' }}
                        @if($waste->user && $waste->user->email)
                            <br><small style="color: #666;">{{ Str::limit($waste->user->email, 20) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $waste->category->name ?? 'N/A' }}</span>
                    </td>
                    <td class="text-center">
                        {{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}
                    </td>
                    <td>
                        @if($waste->bankSampah)
                            {{ $waste->bankSampah->name }}
                            <br><small
                                style="color: #666;">{{ Str::limit($waste->bankSampah->location_name ?? $waste->bankSampah->address, 30) }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">{{ $waste->points_earned ?? 0 }}</span>
                    </td>
                    <td class="text-center">
                        @if($waste->status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($waste->status === 'rejected')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        Tidak ada data untuk periode ini.
                    </td>
                </tr>
            @endforelse

            @if($wastes->count() > 0)
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL:</td>
                    <td class="text-center">{{ number_format($totalWaste, 2) }} kg</td>
                    <td>{{ $totalTransactions }} Transaksi</td>
                    <td class="text-center">{{ number_format($totalPoints) }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem EcoWaste</p>
        <p>© {{ date('Y') }} EcoWaste - Sistem Pengelolaan Sampah Terpadu</p>
    </div>
</body>

</html>