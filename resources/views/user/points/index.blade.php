@extends('layouts.app')

@section('title', 'Poin Saya')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-0">
            <i class="bi bi-award me-2"></i>Poin Saya
        </h2>
        <p class="text-muted mb-0">Kelola poin dan reward Anda</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Total Points Card -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #28a745, #20c997);">
        <div class="card-body text-center text-white py-5">
            <h3 class="card-title mb-3" style="font-size: 1.1rem; opacity: 0.9;">
                <i class="bi bi-star-fill me-2"></i>Total Poin
            </h3>
            <h2 class="mb-0" style="font-size: 3.5rem; font-weight: 700;">
                {{ number_format($user->total_points ?? 0) }}
            </h2>
            <p class="mt-3 mb-0" style="opacity: 0.8;">
                <i class="bi bi-info-circle me-1"></i>Gunakan poin untuk menukar reward menarik
            </p>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="mb-4">
        <a href="{{ route('user.points.rewards') }}" class="btn btn-success">
            <i class="bi bi-gift me-1"></i>Lihat Hadiah
        </a>
    </div>

    <!-- Points History -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-clock-history me-2"></i>Riwayat Poin
            </h5>
        </div>
        <div class="card-body">
            @if($points->count() > 0)
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="text-center">Poin</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($points as $point)
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            {{ $point->created_at->format('d/m/Y') }}<br>
                                            <span class="text-muted">{{ $point->created_at->format('H:i') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $point->description ?? '-' }}</strong>
                                            @if($point->source)
                                                <br><small class="text-muted">
                                                    Sumber: {{ ucfirst(str_replace('_', ' ', $point->source)) }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($point->points > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-plus-circle me-1"></i>+{{ number_format($point->points) }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-dash-circle me-1"></i>{{ number_format($point->points) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($point->type === 'earned')
                                            <span class="badge bg-success">Diperoleh</span>
                                        @elseif($point->type === 'spent')
                                            <span class="badge bg-danger">Digunakan</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($point->type ?? '-') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List View -->
                <div class="d-md-none points-list">
                    @foreach($points as $point)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $point->description ?? '-' }}</h6>
                                        <small class="text-muted d-block mb-2">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $point->created_at->format('d/m/Y H:i') }}
                                        </small>
                                        @if($point->source)
                                            <small class="text-muted d-block mb-2">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Sumber: {{ ucfirst(str_replace('_', ' ', $point->source)) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($point->type === 'earned')
                                            <span class="badge bg-success">Diperoleh</span>
                                        @elseif($point->type === 'spent')
                                            <span class="badge bg-danger">Digunakan</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($point->type ?? '-') }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if($point->points > 0)
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-plus-circle me-1"></i>+{{ number_format($point->points) }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-dash-circle me-1"></i>{{ number_format($point->points) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($points->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $points->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                    <p class="text-muted mb-0">Belum ada riwayat poin</p>
                    <p class="text-muted small mt-2">Mulai kumpulkan poin dengan menginput data sampah!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
