@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
<div class="container-fluid" style="max-width: 100%; overflow-x: hidden;">
    <!-- Admin Stats -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Pengguna</h6>
                            <h2 class="card-title">{{ number_format($totalUsers) }}</h2>
                        </div>
                        <i class="bi bi-people display-6"></i>
                    </div>
                    <small>+{{ number_format($newUsersThisMonth) }} pengguna bulan ini</small>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Sampah</h6>
                            <h2 class="card-title">{{ number_format($totalWaste, 2) }} kg</h2>
                        </div>
                        <i class="bi bi-trash display-6"></i>
                    </div>
                    <small>Bulan ini</small>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Bank Sampah</h6>
                            <h2 class="card-title">{{ number_format($totalBankSampah) }}</h2>
                        </div>
                        <i class="bi bi-geo-alt display-6"></i>
                    </div>
                    <small>Terdaftar</small>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Aktivitas Hari Ini</h6>
                            <h2 class="card-title">{{ number_format($todayActivities) }}</h2>
                        </div>
                        <i class="bi bi-activity display-6"></i>
                    </div>
                    <small>Input data sampah</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-12 col-md-8 mb-4 mb-md-0">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Trend Bulanan
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Distribusi Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    <a href="{{ route('admin.activities') }}" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @forelse($recentActivities as $activity)
                        @php
                            $activityLabels = [
                                'waste_input' => 'Input Sampah',
                                'waste_approved' => 'Sampah Disetujui',
                                'waste_rejected' => 'Sampah Ditolak',
                                'waste_updated' => 'Update Sampah',
                                'waste_deleted' => 'Hapus Sampah',
                                'point_earned' => 'Poin Diperoleh',
                                'point_spent' => 'Poin Digunakan',
                                'reward_claimed' => 'Reward Diklaim',
                                'profile_updated' => 'Update Profil',
                                'user_registered' => 'Registrasi User',
                                'login' => 'Login',
                                'logout' => 'Logout',
                            ];
                            $label = $activityLabels[$activity->activity_type] ?? ucfirst(str_replace('_', ' ', $activity->activity_type ?? 'Aktivitas'));
                            
                            $detail = '';
                            if ($activity->description) {
                                $detail = $activity->description;
                            } elseif ($activity->metadata) {
                                $metadata = $activity->metadata;
                                if (isset($metadata['amount'])) {
                                    $detail = number_format($metadata['amount'], 2) . ' kg';
                                    if (isset($metadata['category'])) {
                                        $detail .= ' ' . $metadata['category'];
                                    }
                                } elseif (isset($metadata['name'])) {
                                    $detail = $metadata['name'];
                                }
                            }
                        @endphp
                        <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-activity"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="badge bg-primary me-2">{{ $label }}</span>
                                        <span class="text-muted small">
                                            <i class="bi bi-clock me-1"></i>{{ $activity->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <strong>{{ $activity->user->name ?? 'N/A' }}</strong>
                                    @if($activity->user && $activity->user->email)
                                        <small class="text-muted d-block">{{ $activity->user->email }}</small>
                                    @endif
                                </div>
                                @if($detail)
                                    <div class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>{{ Str::limit($detail, 50) }}
                                    </div>
                                @endif
                            </div>
                            @if($activity->metadata)
                                <div class="flex-shrink-0 ms-2">
                                    <button class="btn btn-sm btn-outline-info" type="button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $activity->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Aktivitas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @php
                                                        $metadata = $activity->metadata;
                                                    @endphp
                                                    
                                                    @if(isset($metadata['waste_id']))
                                                        @php
                                                            $waste = \App\Models\Waste::with(['category', 'bankSampah', 'user'])->find($metadata['waste_id']);
                                                        @endphp
                                                        
                                                        @if($waste)
                                                            <div class="mb-3">
                                                                <h6 class="text-muted mb-3">
                                                                    <i class="bi bi-info-circle me-2"></i>Informasi Sampah
                                                                </h6>
                                                                <div class="list-group list-group-flush">
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">ID Sampah</small>
                                                                        <strong>#{{ $waste->id }}</strong>
                                                                    </div>
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Jenis Sampah</small>
                                                                        <strong>{{ $waste->type }}</strong>
                                                                    </div>
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Kategori</small>
                                                                        @if($waste->category)
                                                                            <span class="badge" style="background-color: {{ $waste->category->color ?? '#007bff' }};">
                                                                                {{ $waste->category->name }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Jumlah</small>
                                                                        <strong>{{ number_format($waste->amount, 2) }} {{ $waste->unit }}</strong>
                                                                    </div>
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Tanggal</small>
                                                                        <strong>{{ $waste->date->format('d M Y') }}</strong>
                                                                    </div>
                                                                    @if($waste->time)
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Waktu</small>
                                                                        <strong>{{ $waste->time }}</strong>
                                                                    </div>
                                                                    @endif
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Status</small>
                                                                        @if($waste->status === 'approved')
                                                                            <span class="badge bg-success">Disetujui</span>
                                                                        @elseif($waste->status === 'rejected')
                                                                            <span class="badge bg-danger">Ditolak</span>
                                                                        @else
                                                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Poin</small>
                                                                        <strong class="text-success">{{ number_format($waste->points_earned ?? 0) }} poin</strong>
                                                                    </div>
                                                                    @if($waste->bankSampah)
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">Lokasi Bank Sampah</small>
                                                                        <strong>{{ $waste->bankSampah->name }}</strong>
                                                                    </div>
                                                                    @endif
                                                                    @if($waste->description)
                                                                    <div class="list-group-item px-0 py-2">
                                                                        <small class="text-muted d-block mb-1">Deskripsi</small>
                                                                        <div>{{ $waste->description }}</div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-warning">
                                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                                Data sampah dengan ID {{ $metadata['waste_id'] }} tidak ditemukan atau sudah dihapus.
                                                            </div>
                                                            <div class="mt-3">
                                                                <h6 class="text-muted mb-2">Metadata Raw</h6>
                                                                <pre class="bg-light p-3 rounded small">{{ json_encode($metadata, JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                        @endif
                                                    @elseif(!empty($metadata))
                                                        <div class="mb-3">
                                                            <h6 class="text-muted mb-3">
                                                                <i class="bi bi-info-circle me-2"></i>Informasi Detail
                                                            </h6>
                                                            <div class="list-group list-group-flush">
                                                                @foreach($metadata as $key => $value)
                                                                    <div class="list-group-item px-0 py-2 border-bottom">
                                                                        <small class="text-muted d-block mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                                                                        <div>{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info">
                                                            <i class="bi bi-info-circle me-2"></i>
                                                            Tidak ada informasi detail tambahan untuk aktivitas ini.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            Belum ada aktivitas
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Monthly Trend Chart
    const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    
    // Prepare monthly data
    const monthlyData = @json($monthlyTrend);
    const monthLabels = monthlyData.map(item => item.month_name);
    const monthValues = monthlyData.map(item => parseFloat(item.total_kg || 0));
    
    const trendChart = new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Total Sampah (kg)',
                data: monthValues,
                backgroundColor: 'rgba(46, 125, 50, 0.7)',
                borderColor: 'rgba(46, 125, 50, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total Sampah: ' + context.parsed.y.toFixed(2) + ' kg';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(0);
                        }
                    }
                }
            }
        }
    });

    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    const userChart = new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Baru', 'Tidak Aktif'],
            datasets: [{
                data: [
                    {{ $activeUsers }},
                    {{ $newUsers }},
                    {{ $inactiveUsers }}
                ],
                backgroundColor: [
                    '#2E7D32',
                    '#2196F3',
                    '#9E9E9E'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection