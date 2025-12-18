@extends('layouts.admin')

@section('title', 'Generate Laporan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-file-earmark-pdf me-2"></i>Laporan {{ ucfirst($reportData['period']) }}
            </h2>
        </div>
        <form action="{{ route('admin.reports.export') }}" method="GET" class="d-inline">
            @foreach(request()->all() as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-download me-1"></i>Export Excel
            </button>
        </form>
    </div>

    <!-- Report Summary -->
    <div class="row mb-4">
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card admin-card border-0 shadow-sm" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="card-body text-white position-relative" style="min-height: 120px;">
                    <div class="position-absolute top-0 end-0 p-3">
                        <i class="bi bi-trash-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                    <div class="position-relative" style="z-index: 1;">
                        <h6 class="card-subtitle mb-2 text-white" style="font-size: 0.875rem; font-weight: 500; opacity: 0.9;">
                            Total Sampah
                        </h6>
                        <h2 class="card-title mb-0 text-white" style="font-size: 2rem; font-weight: 700;">
                            {{ number_format($reportData['total_waste'], 2) }} kg
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card admin-card border-0 shadow-sm" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                <div class="card-body text-white position-relative" style="min-height: 120px;">
                    <div class="position-absolute top-0 end-0 p-3">
                        <i class="bi bi-receipt-cutoff" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                    <div class="position-relative" style="z-index: 1;">
                        <h6 class="card-subtitle mb-2 text-white" style="font-size: 0.875rem; font-weight: 500; opacity: 0.9;">
                            Total Transaksi
                        </h6>
                        <h2 class="card-title mb-0 text-white" style="font-size: 2rem; font-weight: 700;">
                            {{ number_format($reportData['total_transactions']) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card admin-card border-0 shadow-sm" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                <div class="card-body text-white position-relative" style="min-height: 120px;">
                    <div class="position-absolute top-0 end-0 p-3">
                        <i class="bi bi-star-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                    <div class="position-relative" style="z-index: 1;">
                        <h6 class="card-subtitle mb-2 text-white" style="font-size: 0.875rem; font-weight: 500; opacity: 0.9;">
                            Total Poin Diberikan
                        </h6>
                        <h2 class="card-title mb-0 text-white" style="font-size: 2rem; font-weight: 700;">
                            {{ number_format($reportData['total_points']) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Info -->
    <div class="card admin-card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-info-circle me-2"></i>Informasi Periode
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Jenis Laporan:</strong> {{ ucfirst($reportData['period']) }}</p>
                    <p class="mb-0"><strong>Dari:</strong> {{ \Carbon\Carbon::parse($reportData['start_date'])->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Total Data:</strong> {{ $reportData['total_transactions'] }} transaksi</p>
                    <p class="mb-0"><strong>Sampai:</strong> {{ \Carbon\Carbon::parse($reportData['end_date'])->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report by Category -->
    @if($reportData['by_category']->count() > 0)
        <div class="card admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Laporan per Kategori
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Total Berat (kg)</th>
                                <th>Jumlah Transaksi</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['by_category'] as $category)
                                <tr>
                                    <td><strong>{{ $category['category'] }}</strong></td>
                                    <td>{{ number_format($category['total_kg'], 2) }} kg</td>
                                    <td>{{ $category['count'] }} transaksi</td>
                                    <td>
                                        @php
                                            $percentage = $reportData['total_waste'] > 0 
                                                ? ($category['total_kg'] / $reportData['total_waste']) * 100 
                                                : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card admin-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-6 text-muted d-block mb-3"></i>
                <h5 class="text-muted">Tidak ada data untuk periode ini</h5>
            </div>
        </div>
    @endif
</div>
@endsection

