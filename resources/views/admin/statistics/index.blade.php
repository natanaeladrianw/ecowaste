@extends('layouts.admin')

@section('title', 'Statistik')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h2 class="mb-0">
            <i class="bi bi-graph-up me-2"></i>Statistik
        </h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Waste Statistics -->
        <div class="col-12 col-md-6 mb-4 mb-md-0">
            <div class="card admin-card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trash me-2"></i>Statistik Sampah
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted mb-1">
                            <i class="bi bi-bar-chart me-1"></i>Total Sampah
                        </label>
                        <h3 class="mb-0 text-dark">{{ number_format($totalWaste, 2) }} kg</h3>
                    </div>
                    <hr>
                    @if(isset($wasteTypeStats) && count($wasteTypeStats) > 0)
                        @foreach($wasteTypeStats as $index => $stat)
                            @php
                                $wasteType = $stat['type'];
                                $amount = $stat['amount'];
                                $percentage = $stat['percentage'];
                                
                                // Determine color based on waste type color or default colors
                                $defaultColors = ['#28a745', '#007bff', '#ffc107', '#dc3545', '#6f42c1', '#20c997'];
                                $textColor = $wasteType->color ?? $defaultColors[$index % count($defaultColors)];
                                
                                // Default icons if not set
                                $defaultIcons = ['bi-bar-chart', 'bi-flower1', 'bi-recycle', 'bi-exclamation-triangle', 'bi-arrow-repeat', 'bi-tag'];
                                $icon = $wasteType->icon ?? $defaultIcons[$index % count($defaultIcons)];
                            @endphp
                            <div class="{{ !$loop->last ? 'mb-4' : '' }}">
                                <label class="form-label text-muted mb-1 d-flex align-items-center">
                                    <i class="{{ $icon }} me-1"></i>
                                    {{ $wasteType->name }}
                                </label>
                                <h3 class="mb-0" style="color: {{ $textColor }};">
                                    {{ number_format($amount, 2) }} kg
                                </h3>
                                @if($totalWaste > 0)
                                    <small class="text-muted">
                                        {{ number_format($percentage, 1) }}% dari total
                                    </small>
                                @endif
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback to old display if no waste types found --}}
                        <div class="mb-4">
                            <label class="form-label text-muted mb-1">
                                <i class="bi bi-flower1 me-1"></i>Sampah Organik
                            </label>
                            <h3 class="mb-0 text-success">{{ number_format($organicWaste, 2) }} kg</h3>
                            @if($totalWaste > 0)
                                <small class="text-muted">
                                    {{ number_format(($organicWaste / $totalWaste) * 100, 1) }}% dari total
                                </small>
                            @endif
                        </div>
                        <hr>
            <div>
                            <label class="form-label text-muted mb-1">
                                <i class="bi bi-recycle me-1"></i>Sampah Anorganik
                            </label>
                            <h3 class="mb-0 text-primary">{{ number_format($anorganicWaste, 2) }} kg</h3>
                            @if($totalWaste > 0)
                                <small class="text-muted">
                                    {{ number_format(($anorganicWaste / $totalWaste) * 100, 1) }}% dari total
                                </small>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card admin-card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Statistik User
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-muted mb-1">
                            <i class="bi bi-person-fill me-1"></i>Total User
                        </label>
                        <h3 class="mb-0 text-dark">{{ number_format($totalUsers) }}</h3>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label text-muted mb-1">
                            <i class="bi bi-person-check me-1"></i>User Aktif
                        </label>
                        <h3 class="mb-0 text-success">{{ number_format($activeUsers) }}</h3>
                        @if($totalUsers > 0)
                            <small class="text-muted">
                                {{ number_format(($activeUsers / $totalUsers) * 100, 1) }}% dari total
                            </small>
                        @endif
            </div>
                    <hr>
            <div>
                        <label class="form-label text-muted mb-1">
                            <i class="bi bi-person-plus me-1"></i>User Baru (Bulan Ini)
                        </label>
                        <h3 class="mb-0 text-info">{{ number_format($newUsersThisMonth) }}</h3>
                        <small class="text-muted">
                            Periode: {{ now()->format('F Y') }}
                        </small>
                    </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Statistik per Kategori
                    </h5>
                </div>
                <div class="card-body">
                    @if($categoryStats->count() > 0)
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
                                    @foreach($categoryStats as $category)
                                        @php
                                            $totalKg = $category->total_kg ?? 0;
                                            $percentage = $totalWaste > 0 ? ($totalKg / $totalWaste) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($category->color)
                                                        <div class="rounded-circle me-2" 
                                                             style="width: 20px; height: 20px; background-color: {{ $category->color }};"></div>
                                                    @endif
                                                    @if($category->icon)
                                                        <i class="{{ $category->icon }} me-2"></i>
                                                    @endif
                                                    <strong>{{ $category->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ number_format($totalKg, 2) }} kg
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $category->wastes_count }} transaksi
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 25px;">
                                                        <div class="progress-bar 
                                                            @if($percentage > 50) bg-success
                                                            @elseif($percentage > 25) bg-warning
                                                            @else bg-info
                                                            @endif" 
                                                             role="progressbar" 
                                                             style="width: {{ $percentage }}%"
                                                             aria-valuenow="{{ $percentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            {{ number_format($percentage, 1) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
            </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-6 text-muted d-block mb-2"></i>
                            <p class="text-muted">Belum ada data kategori</p>
            </div>
                    @endif
            </div>
        </div>
    </div>
</div>

    <!-- Statistics Graph Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart-line me-2"></i>Grafik Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="bg-light rounded p-5 text-center" style="min-height: 300px;">
                        <i class="bi bi-graph-up display-4 text-muted d-block mb-3"></i>
                        <h5 class="text-muted mb-2">Grafik Statistik</h5>
                        <p class="text-muted mb-0">Grafik akan ditampilkan di sini</p>
                        <small class="text-muted">Fitur grafik interaktif akan segera hadir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

