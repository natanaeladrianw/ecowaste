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

    <!-- Statistics Graph Section - MOVED TO TOP -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card admin-card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bar-chart-line me-2"></i>Grafik Statistik
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Period Filter -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary active" id="btnDaily" onclick="changePeriod('daily')">
                                    <i class="bi bi-calendar-day me-1"></i>Harian
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btnWeekly" onclick="changePeriod('weekly')">
                                    <i class="bi bi-calendar-week me-1"></i>Mingguan
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btnMonthly" onclick="changePeriod('monthly')">
                                    <i class="bi bi-calendar-month me-1"></i>Bulanan
                                </button>
                            </div>
                            <!-- Data Type Filter -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-success active" id="btnWaste" onclick="changeDataType('waste')">
                                    <i class="bi bi-trash me-1"></i>Sampah
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="btnTransactions" onclick="changeDataType('transactions')">
                                    <i class="bi bi-receipt me-1"></i>Transaksi
                                </button>
                                <button type="button" class="btn btn-outline-info" id="btnUsers" onclick="changeDataType('users')">
                                    <i class="bi bi-people me-1"></i>User Baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted" id="periodLabel">
                            <i class="bi bi-info-circle me-1"></i>Menampilkan data 7 hari terakhir
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 350px; position: relative;">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
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
                    <!-- Search Form -->
                    <form action="{{ route('admin.statistics.index') }}" method="GET" class="mb-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-start-0" 
                                   placeholder="Cari jenis sampah..." 
                                   value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('admin.statistics.index') }}" class="btn btn-outline-secondary border-start-0 border-end-0" title="Hapus Filter">
                                    <i class="bi bi-x"></i>
                                </a>
                            @endif
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        </div>
                    </form>

                    @if(isset($wasteTypeStats) && $wasteTypeStats->count() > 0)
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
                            <div class="mb-4">
                                <label class="form-label text-muted mb-1 d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="{{ $icon }} me-1"></i>
                                        {{ $wasteType->name }}
                                    </span>
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
                                <hr class="my-3 text-muted opacity-25">
                            @endif
                        @endforeach

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $wasteTypeStats->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        @if(request('search'))
                            <div class="text-center py-4">
                                <i class="bi bi-search display-6 text-muted d-block mb-2 opacity-50"></i>
                                <p class="text-muted mb-0">Tidak ditemukan jenis sampah "{{ request('search') }}"</p>
                            </div>
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
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Data from controller
    const dailyStats = @json($dailyStats);
    const weeklyStats = @json($weeklyStats);
    const monthlyStats = @json($monthlyStats);
    
    // Current state
    let currentPeriod = 'daily';
    let currentDataType = 'waste';
    let currentChart = null;
    
    const ctx = document.getElementById('statisticsChart').getContext('2d');

    // Period labels
    const periodLabels = {
        daily: 'Menampilkan data 7 hari terakhir',
        weekly: 'Menampilkan data 4 minggu terakhir',
        monthly: 'Menampilkan data 6 bulan terakhir'
    };

    // Get data based on period
    function getDataByPeriod(period) {
        switch(period) {
            case 'daily':
                return dailyStats;
            case 'weekly':
                return weeklyStats;
            case 'monthly':
                return monthlyStats;
            default:
                return dailyStats;
        }
    }

    // Chart colors
    const chartColors = {
        waste: {
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
        },
        transactions: {
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderColor: 'rgba(0, 123, 255, 1)',
        },
        users: {
            backgroundColor: 'rgba(23, 162, 184, 0.2)',
            borderColor: 'rgba(23, 162, 184, 1)',
        }
    };

    // Chart labels
    const chartLabels = {
        waste: 'Total Sampah (kg)',
        transactions: 'Jumlah Transaksi',
        users: 'User Baru'
    };

    function createChart() {
        const data = getDataByPeriod(currentPeriod);
        const labels = data.map(item => item.label);
        const values = data.map(item => item[currentDataType]);
        const colors = chartColors[currentDataType];
        
        if (currentChart) {
            currentChart.destroy();
        }

        currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: chartLabels[currentDataType],
                    data: values,
                    backgroundColor: colors.backgroundColor,
                    borderColor: colors.borderColor,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: colors.borderColor,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });

        // Update period label
        document.getElementById('periodLabel').innerHTML = 
            '<i class="bi bi-info-circle me-1"></i>' + periodLabels[currentPeriod];
    }

    function changePeriod(period) {
        currentPeriod = period;
        
        // Update button states
        document.getElementById('btnDaily').classList.remove('active');
        document.getElementById('btnWeekly').classList.remove('active');
        document.getElementById('btnMonthly').classList.remove('active');
        document.getElementById('btn' + period.charAt(0).toUpperCase() + period.slice(1)).classList.add('active');

        createChart();
    }

    function changeDataType(type) {
        currentDataType = type;
        
        // Update button states - reset all to outline
        document.getElementById('btnWaste').className = 'btn btn-outline-success';
        document.getElementById('btnTransactions').className = 'btn btn-outline-primary';
        document.getElementById('btnUsers').className = 'btn btn-outline-info';
        
        // Set active button with filled style
        if (type === 'waste') {
            document.getElementById('btnWaste').className = 'btn btn-success active';
        } else if (type === 'transactions') {
            document.getElementById('btnTransactions').className = 'btn btn-primary active';
        } else if (type === 'users') {
            document.getElementById('btnUsers').className = 'btn btn-info active';
        }

        createChart();
    }

    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', function() {
        createChart();
    });
</script>
@endpush
@endsection
