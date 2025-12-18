@extends('layouts.app')

@section('title', 'Dashboard')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
<div class="container-fluid">
    <!-- Header Stats -->
    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-1" style="font-size: 0.75rem;">Total Sampah Hari Ini</h6>
                            <h4 class="card-title mb-0" style="font-size: 1.25rem; font-weight: 600;">
                                {{ number_format($todayWaste, 2) }} kg
                            </h4>
                        </div>
                        <i class="bi bi-trash" style="font-size: 1.5rem; opacity: 0.8; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                    </div>
                    <small style="font-size: 0.7rem;">
                        @php
                            $yesterdayWaste = \App\Models\Waste::where('user_id', Auth::id())
                                ->whereDate('date', \Carbon\Carbon::yesterday())
                                ->sum(\DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
                            $diff = $todayWaste - $yesterdayWaste;
                        @endphp
                        @if($diff > 0)
                            +{{ number_format($diff, 2) }} kg dari kemarin
                        @elseif($diff < 0)
                            {{ number_format(abs($diff), 2) }} kg kurang dari kemarin
                        @else
                            Sama dengan kemarin
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('waste.create') }}" class="text-decoration-none">
                                <div class="card h-100 border text-center hover-shadow">
                                    <div class="card-body">
                                        <i class="bi bi-trash display-6 text-success mb-3"></i>
                                        <h6>Input Sampah</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('bank-sampah.map') }}" class="text-decoration-none">
                                <div class="card h-100 border text-center hover-shadow">
                                    <div class="card-body">
                                        <i class="bi bi-geo-alt display-6 text-primary mb-3"></i>
                                        <h6>Cari Bank Sampah</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('education.challenges') }}" class="text-decoration-none">
                                <div class="card h-100 border text-center hover-shadow">
                                    <div class="card-body">
                                        <i class="bi bi-trophy display-6 text-warning mb-3"></i>
                                        <h6>Challenge</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('community.forum') }}" class="text-decoration-none">
                                <div class="card h-100 border text-center hover-shadow">
                                    <div class="card-body">
                                        <i class="bi bi-chat-dots display-6 text-info mb-3"></i>
                                        <h6>Forum Diskusi</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    @forelse($recentActivities as $activity)
                        @php
                            // Map activity type to icon and color
                            $activityIcons = [
                                'waste_input' => ['icon' => 'bi-check-circle', 'color' => 'text-success'],
                                'waste_approved' => ['icon' => 'bi-check-circle-fill', 'color' => 'text-success'],
                                'waste_rejected' => ['icon' => 'bi-x-circle', 'color' => 'text-danger'],
                                'waste_updated' => ['icon' => 'bi-pencil', 'color' => 'text-primary'],
                                'waste_deleted' => ['icon' => 'bi-trash', 'color' => 'text-danger'],
                                'point_earned' => ['icon' => 'bi-award', 'color' => 'text-warning'],
                                'point_spent' => ['icon' => 'bi-cash-coin', 'color' => 'text-info'],
                                'reward_claimed' => ['icon' => 'bi-gift', 'color' => 'text-success'],
                                'profile_updated' => ['icon' => 'bi-person-gear', 'color' => 'text-primary'],
                                'challenge_completed' => ['icon' => 'bi-trophy', 'color' => 'text-info'],
                                'user_registered' => ['icon' => 'bi-person-plus', 'color' => 'text-success'],
                                'login' => ['icon' => 'bi-box-arrow-in-right', 'color' => 'text-primary'],
                                'logout' => ['icon' => 'bi-box-arrow-right', 'color' => 'text-secondary'],
                            ];
                            
                            $activityInfo = $activityIcons[$activity->activity_type] ?? ['icon' => 'bi-activity', 'color' => 'text-secondary'];
                            $icon = $activityInfo['icon'];
                            $color = $activityInfo['color'];
                            
                            // Get description or build from metadata
                            $description = $activity->description ?? 'Aktivitas';
                            if (!$activity->description && $activity->metadata) {
                                $metadata = is_array($activity->metadata) ? $activity->metadata : json_decode($activity->metadata, true);
                                if (isset($metadata['amount'])) {
                                    $description = 'Input sampah ' . number_format($metadata['amount'], 2) . ' kg';
                                    if (isset($metadata['category'])) {
                                        $description .= ' - ' . $metadata['category'];
                                    }
                                }
                            }
                        @endphp
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <i class="bi {{ $icon }} {{ $color }} me-2"></i>
                                <span>{{ $description }}</span>
                            </div>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Statistik Sampah 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="wasteChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Distribusi Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Prepare data from PHP
    const chartData = @json($chartData);
    const categoryStats = @json($categoryStats);

    // Waste Chart - 7 Days
    const wasteCtx = document.getElementById('wasteChart').getContext('2d');
    
    // Extract labels and data from chartData
    const labels = chartData.map(item => item.day_short);
    const wasteData = chartData.map(item => parseFloat(item.total_kg) || 0);
    
    const wasteChart = new Chart(wasteCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Sampah (kg)',
                data: wasteData,
                borderColor: '#2E7D32',
                backgroundColor: 'rgba(46, 125, 50, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total: ' + context.parsed.y.toFixed(2) + ' kg';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(1) + ' kg';
                        }
                    }
                }
            }
        }
    });

    // Category Chart - Distribution
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    
    // Prepare category data
    const categoryLabels = categoryStats.map(item => item.name);
    const categoryData = categoryStats.map(item => parseFloat(item.total_kg) || 0);
    
    // Generate colors for categories (use category color if available, otherwise generate)
    const defaultColors = [
        '#2E7D32', // green
        '#2196F3', // blue
        '#F44336', // red
        '#FF9800', // orange
        '#9C27B0', // purple
        '#00BCD4', // cyan
        '#4CAF50', // light green
        '#FF5722', // deep orange
    ];
    
    const categoryColors = categoryStats.map((item, index) => {
        if (item.color) {
            return item.color;
        }
        return defaultColors[index % defaultColors.length];
    });
    
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels.length > 0 ? categoryLabels : ['Belum Ada Data'],
            datasets: [{
                data: categoryData.length > 0 ? categoryData : [1],
                backgroundColor: categoryColors.length > 0 ? categoryColors : ['#cccccc']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = categoryData.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value.toFixed(2) + ' kg (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection