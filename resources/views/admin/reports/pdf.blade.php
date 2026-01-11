<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Sampah</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-table {
            width: 50%;
            margin-left: auto;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN DATA SAMPAH</h1>
        <p>Periode:
            @if($period === 'daily')
                {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
            @elseif($period === 'weekly')
                {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            @elseif($period === 'monthly')
                {{ \Carbon\Carbon::parse($startDate)->format('F Y') }}
            @else
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">User</th>
                <th width="15%">Jenis Sampah</th>
                <th class="text-right" width="10%">Berat (kg)</th>
                <th width="25%">Bank Sampah</th>
                <th class="text-right" width="10%">Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wastes as $index => $waste)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $waste->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $waste->user->name ?? '-' }}</td>
                    <td>{{ $waste->category->name ?? '-' }}</td>
                    <td class="text-right">
                        {{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}
                    </td>
                    <td>
                        @if($waste->bankSampah)
                            {{ $waste->bankSampah->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($waste->points_earned) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-right">
                    {{ number_format($wastes->sum(function ($w) {
    return $w->unit === 'gram' ? $w->amount / 1000 : $w->amount; }), 2) }}
                </td>
                <td class="text-right">Total Transaksi: {{ $wastes->count() }}</td>
                <td class="text-right">{{ number_format($wastes->sum('points_earned')) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>