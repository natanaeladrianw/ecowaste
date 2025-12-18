@extends('layouts.app')

@section('title', 'Tantangan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-0">
            <i class="bi bi-trophy me-2"></i>Tantangan
        </h2>
        <p class="text-muted mb-0">Ikuti tantangan untuk mendapatkan poin dan hadiah menarik</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Tantangan Aktif -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Tantangan Aktif
                    </h5>
                </div>
                <div class="card-body">
                    @if($challengesWithProgress->count() > 0)
                        <div class="challenges-list">
                            @foreach($challengesWithProgress as $item)
                                @php
                                    $challenge = $item['challenge'];
                                    $progress = $item['progress'];
                                    
                                    // Calculate current progress from waste data
                                    $currentProgress = 0;
                                    if ($challenge->target_category_id) {
                                        $totalWaste = \App\Models\Waste::where('user_id', Auth::id())
                                            ->where('category_id', $challenge->target_category_id)
                                            ->whereBetween('date', [$challenge->start_date, $challenge->end_date])
                                            ->sum(\DB::raw('CASE WHEN unit = "gram" THEN amount / 1000 ELSE amount END'));
                                        $currentProgress = $totalWaste ?? 0;
                                    } else {
                                        $currentProgress = $progress->current_value ?? 0;
                                    }
                                    
                                    $targetAmount = $challenge->target_amount ?? 1;
                                    $progressPercentage = $targetAmount > 0 ? min(100, ($currentProgress / $targetAmount) * 100) : 0;
                                    $isCompleted = $progressPercentage >= 100;
                                @endphp
                                
                                <div class="challenge-item mb-3">
                                    <div class="card h-100 border-0 shadow-sm {{ $isCompleted ? 'border-success' : '' }}" 
                                         style="transition: all 0.3s ease;">
                                        @if($isCompleted)
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Selesai
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <div class="card-body">
                                            <!-- Desktop Layout -->
                                            <div class="d-none d-md-block">
                                                <div class="row g-3">
                                                    <div class="col-md-12 col-lg-4">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                                                <i class="bi bi-trophy-fill text-success fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="card-title mb-0 fw-bold">{{ $challenge->title }}</h6>
                                                                <small class="text-muted">
                                                                    <i class="bi bi-calendar3 me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($challenge->start_date)->format('d M Y') }} - 
                                                                    {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-lg-8">
                                                        <p class="card-text text-muted small mb-3">
                                                            {{ $challenge->description }}
                                                        </p>
                                                        
                                                        @if($challenge->targetCategory)
                                                            <span class="badge bg-primary mb-3">
                                                                <i class="bi bi-tag me-1"></i>{{ $challenge->targetCategory->name }}
                                                            </span>
                                                        @endif
                                                        
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <small class="text-muted fw-bold">Progress</small>
                                                                <small class="text-muted">
                                                                    {{ number_format($currentProgress, 2) }}/{{ $targetAmount }} {{ $challenge->target_unit ?? 'kg' }}
                                                                </small>
                                                            </div>
                                                            <div class="progress" style="height: 10px;">
                                                                <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-warning' }}" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $progressPercentage }}%"
                                                                     aria-valuenow="{{ $progressPercentage }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <small class="text-muted">{{ number_format($progressPercentage, 1) }}% selesai</small>
                                                        </div>
                                                        
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <small class="text-success fw-bold">
                                                                    <i class="bi bi-star-fill me-1"></i>
                                                                    {{ $challenge->points_reward }} Poin
                                                                </small>
                                                            </div>
                                                            @if(!$isCompleted)
                                                                <form action="{{ route('user.education.challenges.complete', $challenge->id) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Apakah Anda yakin challenge ini sudah selesai?')">
                                                                    @csrf
                                                                    <button type="submit" 
                                                                            class="btn btn-sm btn-success"
                                                                            {{ $progressPercentage < 100 ? 'disabled' : '' }}
                                                                            title="{{ $progressPercentage < 100 ? 'Progress belum mencapai 100%' : 'Klaim reward' }}">
                                                                        <i class="bi bi-check-circle me-1"></i>
                                                                        {{ $progressPercentage >= 100 ? 'Klaim Reward' : 'Belum Selesai' }}
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle me-1"></i>Diselesaikan
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Mobile Layout (List) -->
                                            <div class="d-md-none challenge-mobile">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                                                        <i class="bi bi-trophy-fill text-success fs-5"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1 fw-bold">{{ $challenge->title }}</h6>
                                                        <small class="text-muted d-block mb-2">
                                                            <i class="bi bi-calendar3 me-1"></i>
                                                            {{ \Carbon\Carbon::parse($challenge->start_date)->format('d M Y') }} - 
                                                            {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}
                                                        </small>
                                                        <p class="card-text text-muted small mb-2">
                                                            {{ $challenge->description }}
                                                        </p>
                                                        @if($challenge->targetCategory)
                                                            <span class="badge bg-primary mb-2">
                                                                <i class="bi bi-tag me-1"></i>{{ $challenge->targetCategory->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted fw-bold">Progress</small>
                                                        <small class="text-muted">
                                                            {{ number_format($currentProgress, 2) }}/{{ $targetAmount }} {{ $challenge->target_unit ?? 'kg' }}
                                                        </small>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-warning' }}" 
                                                             role="progressbar" 
                                                             style="width: {{ $progressPercentage }}%"
                                                             aria-valuenow="{{ $progressPercentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ number_format($progressPercentage, 1) }}% selesai</small>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-success fw-bold">
                                                        <i class="bi bi-star-fill me-1"></i>
                                                        {{ $challenge->points_reward }} Poin
                                                    </small>
                                                    @if(!$isCompleted)
                                                        <form action="{{ route('user.education.challenges.complete', $challenge->id) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Apakah Anda yakin challenge ini sudah selesai?')"
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-success"
                                                                    {{ $progressPercentage < 100 ? 'disabled' : '' }}
                                                                    title="{{ $progressPercentage < 100 ? 'Progress belum mencapai 100%' : 'Klaim reward' }}">
                                                                <i class="bi bi-check-circle me-1"></i>
                                                                {{ $progressPercentage >= 100 ? 'Klaim Reward' : 'Belum Selesai' }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>Diselesaikan
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                            <p class="fs-5 mb-0 text-muted">Tidak ada tantangan aktif saat ini</p>
                            <small class="text-muted">Tantangan baru akan segera hadir!</small>
                        </div>
                    @endif
                </div>
            </div>
                    </div>

        <!-- Tantangan Selesai -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle me-2"></i>Tantangan Selesai
                    </h5>
                </div>
                <div class="card-body">
                    @if($completedChallenges->count() > 0)
                        <div class="challenges-list">
                            @foreach($completedChallenges as $achievement)
                                <div class="challenge-item mb-3">
                                    <div class="card h-100 border-success border-2 shadow-sm" style="transition: all 0.3s ease;">
                                        <div class="card-body">
                                            <!-- Desktop Layout -->
                                            <div class="d-none d-md-block">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                                        <i class="bi bi-trophy-fill text-success fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="card-title mb-0 fw-bold text-success">{{ $achievement->title }}</h6>
                                                        @if($achievement->completed_at)
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar-check me-1"></i>
                                                                Selesai: {{ \Carbon\Carbon::parse($achievement->completed_at)->format('d M Y') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($achievement->description)
                                                    <p class="card-text text-muted small mb-3">
                                                        {{ $achievement->description }}
                                                    </p>
                                                @endif
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="badge bg-success fs-6">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Diselesaikan
                                                    </span>
                                                    @if($achievement->target_value)
                                                        <small class="text-muted">
                                                            Target: {{ number_format($achievement->target_value) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Mobile Layout (List) -->
                                            <div class="d-md-none challenge-mobile">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                                                        <i class="bi bi-trophy-fill text-success fs-5"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1 fw-bold text-success">{{ $achievement->title }}</h6>
                                                        @if($achievement->completed_at)
                                                            <small class="text-muted d-block mb-2">
                                                                <i class="bi bi-calendar-check me-1"></i>
                                                                Selesai: {{ \Carbon\Carbon::parse($achievement->completed_at)->format('d M Y') }}
                                                            </small>
                                                        @endif
                                                        @if($achievement->description)
                                                            <p class="card-text text-muted small mb-2">
                                                                {{ $achievement->description }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Diselesaikan
                                                    </span>
                                                    @if($achievement->target_value)
                                                        <small class="text-muted">
                                                            Target: {{ number_format($achievement->target_value) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-trophy display-4 d-block mb-3 text-muted opacity-50"></i>
                            <p class="fs-5 mb-0 text-muted">Belum ada tantangan yang diselesaikan</p>
                            <small class="text-muted">Mulai ikuti tantangan aktif untuk mendapatkan poin!</small>
                    </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    @media (min-width: 768px) {
        .challenges-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }
    
    @media (min-width: 992px) {
        .challenges-list {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    .challenge-item {
        width: 100%;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .progress-bar {
        transition: width 0.6s ease;
    }
    
    /* Mobile specific styles */
    @media (max-width: 767.98px) {
        .challenge-mobile .card-body {
            padding: 1rem;
        }
        
        .challenge-mobile .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .challenge-item {
            margin-bottom: 1rem !important;
        }
    }
</style>
@endpush
@endsection

