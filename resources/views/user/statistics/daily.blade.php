@extends('layouts.app')

@section('title', 'Statistik Harian')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h2 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Statistik Harian
                </h2>
                <p class="text-muted mb-0">Lihat statistik sampah harian Anda</p>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('user.statistics.daily') }}" class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="date" class="form-label">
                            <i class="bi bi-calendar3 me-1"></i>Pilih Tanggal
                        </label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}" required>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-3">
            <div class="col-6 col-md-4">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; opacity: 0.9;">Total Sampah Hari
                                    Ini</h6>
                                <h4 class="card-title mb-0" style="font-size: 1.5rem; font-weight: 600;">
                                    {{ number_format($totalKg, 2) }} kg
                                </h4>
                            </div>
                            <i class="bi bi-trash"
                                style="font-size: 2rem; opacity: 0.3; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; opacity: 0.9;">Poin Hari Ini</h6>
                                <h4 class="card-title mb-0" style="font-size: 1.5rem; font-weight: 600;">
                                    {{ number_format($totalPoints) }}
                                </h4>
                            </div>
                            <i class="bi bi-award"
                                style="font-size: 2rem; opacity: 0.3; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; opacity: 0.9;">Transaksi Hari Ini
                                </h6>
                                <h4 class="card-title mb-0" style="font-size: 1.5rem; font-weight: 600;">
                                    {{ number_format($totalTransactions) }}
                                </h4>
                            </div>
                            <i class="bi bi-list-check"
                                style="font-size: 2rem; opacity: 0.3; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Grafik Harian
                </h5>
            </div>
            <div class="card-body">
                @if($wastes->count() > 0)
                    <canvas id="dailyChart" height="80"></canvas>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                        <p class="text-muted mb-0">Tidak ada data untuk tanggal yang dipilih</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Waste Details Table (Optional) -->
        @if($wastes->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>Detail Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jenis</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Poin</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wastes as $waste)
                                    <tr>
                                        <td>
                                            @if($waste->category)
                                                <span class="badge"
                                                    style="background-color: {{ $waste->category->color ?? '#6c757d' }}; color: white;">
                                                    <i class="bi {{ $waste->category->icon ?? 'bi-circle' }} me-1"></i>
                                                    {{ $waste->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $waste->type }}</strong></td>
                                        <td class="text-center">
                                            {{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}
                                            {{ $waste->unit }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i>{{ $waste->points_earned ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($waste->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($waste->status === 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('user.community.transactions.share', $waste->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary"
                                                    title="Share ke Komunitas">
                                                    <i class="bi bi-share-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile List View -->
                    <div class="d-md-none transaction-list">
                        @foreach($wastes as $waste)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                @if($waste->category)
                                                    <span class="badge"
                                                        style="background-color: {{ $waste->category->color ?? '#6c757d' }}; color: white;">
                                                        <i class="bi {{ $waste->category->icon ?? 'bi-circle' }} me-1"></i>
                                                        {{ $waste->category->name }}
                                                    </span>
                                                @endif
                                                @if($waste->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($waste->status === 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @endif
                                            </div>
                                            <h6 class="mb-1 fw-bold">{{ $waste->type }}</h6>
                                            <div class="d-flex flex-wrap gap-3 text-muted small mb-2">
                                                <span>
                                                    <i class="bi bi-box-seam me-1"></i>
                                                    {{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}
                                                    {{ $waste->unit }}
                                                </span>
                                                <span>
                                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                                    {{ $waste->points_earned ?? 0 }} Poin
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <form action="{{ route('user.community.transactions.share', $waste->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                                        <i class="bi bi-share-fill me-1"></i>Share Aktivitas
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if($wastes->count() > 0)
                    // Prepare chart data
                    const wastes = @json($wastes);
                    const categoryData = {};

                    wastes.forEach(waste => {
                        const categoryName = waste.category ? waste.category.name : 'Lainnya';
                        const amount = waste.unit === 'gram' ? waste.amount / 1000 : waste.amount;

                        if (!categoryData[categoryName]) {
                            categoryData[categoryName] = 0;
                        }
                        categoryData[categoryName] += amount;
                    });

                    const labels = Object.keys(categoryData);
                    const data = Object.values(categoryData);

                    // Chart colors
                    const colors = [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(108, 117, 125, 0.8)',
                    ];

                    // Create chart
                    const ctx = document.getElementById('dailyChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Sampah (kg)',
                                data: data,
                                backgroundColor: colors.slice(0, labels.length),
                                borderColor: colors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                                borderWidth: 2,
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            return context.parsed.y.toFixed(2) + ' kg';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function (value) {
                                            return value.toFixed(2) + ' kg';
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif
            });
        </script>
    @endpush
@endsection