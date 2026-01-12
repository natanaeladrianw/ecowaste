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
                                    <div class="card h-100 border-0 shadow-sm {{ $isCompleted ? 'border-success border-2' : '' }}" 
                                         style="transition: all 0.3s ease;">
                                        
                                        <div class="card-body">
                                            <!-- Desktop Layout -->
                                            <!-- Desktop Layout -->
                                            <div class="d-none d-md-block">
                                                <div class="d-flex align-items-start">
                                                    <!-- Icon Section -->
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 flex-shrink-0">
                                                        <i class="bi bi-trophy-fill text-success fs-4"></i>
                                                    </div>
                                                    
                                                    <!-- Content Section -->
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <h6 class="card-title fw-bold mb-1">{{ $challenge->title }}</h6>
                                                                <small class="text-muted d-block mb-2">
                                                                    <i class="bi bi-calendar3 me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($challenge->start_date)->format('d M Y') }} - 
                                                                    {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}
                                                                </small>
                                                            </div>
                                                            <div class="text-end ms-3 d-flex flex-column gap-1">
                                                                <span class="badge bg-success fs-6">
                                                                    <i class="bi bi-star-fill me-1"></i>
                                                                    {{ $challenge->points_reward }} Poin
                                                                </span>
                                                                @if($isCompleted)
                                                                    <span class="badge bg-primary">
                                                                        <i class="bi bi-check-circle-fill me-1"></i>Selesai
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <p class="card-text text-muted small mb-3">
                                                            {{ $challenge->description }}
                                                        </p>
                                                        
                                                        @if($challenge->targetCategory)
                                                            <div class="mb-3">
                                                                <span class="badge bg-primary">
                                                                    <i class="bi bi-tag me-1"></i>{{ $challenge->targetCategory->name }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="mb-3 p-3 bg-light rounded">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <small class="fw-bold text-dark">Progress Saya</small>
                                                                <small class="text-muted fw-bold">
                                                                    {{ number_format($currentProgress, 2) }}/{{ $targetAmount }} {{ $challenge->target_unit ?? 'kg' }}
                                                                </small>
                                                            </div>
                                                            <div class="progress mb-2" style="height: 10px;">
                                                                <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-warning' }}" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $progressPercentage }}%"
                                                                     aria-valuenow="{{ $progressPercentage }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <small class="text-muted">{{ number_format($progressPercentage, 1) }}% selesai</small>
                                                                @if($isCompleted)
                                                                    <small class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Target Tercapai</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="d-flex justify-content-end gap-2">
                                                            {{-- All challenges in this section are NOT yet claimed (filtered by controller) --}}
                                                            @if($challenge->target_category_id && $progressPercentage < 100)
                                                                <a href="{{ route('user.waste.create', ['category_id' => $challenge->target_category_id, 'challenge_id' => $challenge->id]) }}" 
                                                                   class="btn btn-primary">
                                                                    <i class="bi bi-plus-circle me-1"></i>Mulai Tantangan
                                                                </a>
                                                            @endif
                                                            <button type="button" 
                                                                    class="btn {{ $progressPercentage >= 100 ? 'btn-success' : 'btn-secondary' }}"
                                                                    {{ $progressPercentage < 100 ? 'disabled' : '' }}
                                                                    onclick="openClaimModal({{ $challenge->id }}, '{{ addslashes($challenge->title) }}', {{ $challenge->points_reward }}, '{{ route('user.education.challenges.complete', $challenge->id) }}')">
                                                                <i class="bi bi-check-circle me-1"></i>
                                                                {{ $progressPercentage >= 100 ? 'Klaim Reward' : 'Belum Selesai' }}
                                                            </button>
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
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <h6 class="card-title mb-1 fw-bold">{{ $challenge->title }}</h6>
                                                            <div class="d-flex flex-column gap-1 ms-2 flex-shrink-0">
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-star-fill me-1"></i>{{ $challenge->points_reward }} Poin
                                                                </span>
                                                                @if($isCompleted)
                                                                    <span class="badge bg-primary">
                                                                        <i class="bi bi-check-circle-fill me-1"></i>Selesai
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mb-2">
                                                            <i class="bi bi-calendar3 me-1"></i>
                                                            {{ \Carbon\Carbon::parse($challenge->start_date)->format('d M Y') }} - 
                                                            {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}
                                                        </small>
                                                        <p class="card-text text-muted small mb-2">
                                                            {{ $challenge->description }}
                                                        </p>
                                                        @if($challenge->targetCategory)
                                                            <span class="badge bg-info mb-2">
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
                                                
                                                <div class="d-flex justify-content-end align-items-center">
                                                    {{-- All challenges in this section are NOT yet claimed (filtered by controller) --}}
                                                    <div class="d-flex gap-2">
                                                        @if($challenge->target_category_id && $progressPercentage < 100)
                                                            <a href="{{ route('user.waste.create', ['category_id' => $challenge->target_category_id, 'challenge_id' => $challenge->id]) }}" 
                                                               class="btn btn-sm btn-primary">
                                                                <i class="bi bi-plus-circle me-1"></i>Mulai
                                                            </a>
                                                        @endif
                                                        <button type="button" 
                                                                class="btn btn-sm {{ $progressPercentage >= 100 ? 'btn-success' : 'btn-secondary' }}"
                                                                {{ $progressPercentage < 100 ? 'disabled' : '' }}
                                                                title="{{ $progressPercentage < 100 ? 'Progress belum mencapai 100%' : 'Klaim reward' }}"
                                                                onclick="openClaimModal({{ $challenge->id }}, '{{ addslashes($challenge->title) }}', {{ $challenge->points_reward }}, '{{ route('user.education.challenges.complete', $challenge->id) }}')">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            {{ $progressPercentage >= 100 ? 'Klaim' : 'Belum Selesai' }}
                                                        </button>
                                                    </div>
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
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                                        <i class="bi bi-trophy-fill text-success fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="card-title mb-0 fw-bold text-success">{{ $achievement->title }}</h6>
                                                                @if($achievement->completed_at)
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-calendar-check me-1"></i>
                                                                        Selesai: {{ \Carbon\Carbon::parse($achievement->completed_at)->format('d M Y') }}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex flex-column gap-1 ms-3">
                                                                @if($achievement->challenge && $achievement->challenge->points_reward)
                                                                    <span class="badge bg-warning text-dark">
                                                                        <i class="bi bi-star-fill me-1"></i>{{ $achievement->challenge->points_reward }} Poin
                                                                    </span>
                                                                @endif
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>Diklaim
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($achievement->description)
                                                    <p class="card-text text-muted small mb-3">
                                                        {{ $achievement->description }}
                                                    </p>
                                                @endif
                                                @if($achievement->challenge && $achievement->challenge->targetCategory)
                                                    <span class="badge bg-info mb-2">
                                                        <i class="bi bi-tag me-1"></i>{{ $achievement->challenge->targetCategory->name }}
                                                    </span>
                                                @endif
                                                <div class="d-flex align-items-center justify-content-between mt-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-bullseye me-1"></i>
                                                        Target tercapai: {{ number_format($achievement->target_value ?? 0) }} {{ $achievement->challenge->target_unit ?? 'kg' }}
                                                    </small>
                                                    <form action="{{ route('user.community.achievements.share', $achievement->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-share-fill me-1"></i>Share Pencapaian
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <!-- Mobile Layout (List) -->
                                            <div class="d-md-none challenge-mobile">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                                                        <i class="bi bi-trophy-fill text-success fs-5"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <h6 class="card-title mb-1 fw-bold text-success">{{ $achievement->title }}</h6>
                                                            <div class="d-flex flex-column gap-1 ms-2 flex-shrink-0">
                                                                @if($achievement->challenge && $achievement->challenge->points_reward)
                                                                    <span class="badge bg-warning text-dark">
                                                                        <i class="bi bi-star-fill me-1"></i>{{ $achievement->challenge->points_reward }} Poin
                                                                    </span>
                                                                @endif
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>Diklaim
                                                                </span>
                                                            </div>
                                                        </div>
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
                                                        @if($achievement->challenge && $achievement->challenge->targetCategory)
                                                            <span class="badge bg-info mb-2">
                                                                <i class="bi bi-tag me-1"></i>{{ $achievement->challenge->targetCategory->name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-bullseye me-1"></i>
                                                        Target: {{ number_format($achievement->target_value ?? 0) }} {{ $achievement->challenge->target_unit ?? 'kg' }}
                                                    </small>
                                                    <form action="{{ route('user.community.achievements.share', $achievement->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-share-fill me-1"></i>Share
                                                        </button>
                                                    </form>
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
</div>

<!-- Custom Confirmation Modal -->
<div class="modal fade" id="claimRewardModal" tabindex="-1" aria-labelledby="claimRewardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <!-- Modal Header with gradient -->
            <div class="modal-header border-0 text-white py-4" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="w-100 text-center">
                    <div class="mb-3">
                        <i class="bi bi-trophy-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="modal-title fw-bold" id="claimRewardModalLabel">
                        Klaim Reward
                    </h4>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body text-center py-4 px-4">
                <div class="mb-3">
                    <i class="bi bi-gift-fill text-success" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-3" id="challengeTitle">Nama Tantangan</h5>
                <p class="text-muted mb-3">
                    Apakah Anda yakin ingin mengklaim reward untuk tantangan ini?
                </p>
                <div class="d-flex justify-content-center gap-2 mb-2">
                    <span class="badge bg-success fs-6 px-3 py-2" id="rewardPoints">
                        <i class="bi bi-star-fill me-1"></i>0 Poin
                    </span>
                </div>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Poin akan langsung ditambahkan ke akun Anda
                </small>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-success px-4 py-2" id="confirmClaimBtn" style="border-radius: 10px;">
                    <i class="bi bi-check-circle-fill me-1"></i>Ya, Klaim Reward
                </button>
            </div>
        </div>
    </div>
</div>

<form id="claimRewardForm" method="POST" style="display: none;">
    @csrf
</form>

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
    
    /* Modal Animation */
    #claimRewardModal .modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            transform: scale(0.8) translateY(-50px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }
    
    /* Modal hover effects */
    #claimRewardModal .btn {
        transition: all 0.3s ease;
    }
    
    #claimRewardModal .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    let currentFormAction = '';
    
    function openClaimModal(challengeId, challengeTitle, rewardPoints, formAction) {
        currentFormAction = formAction;
        
        // Update modal content
        document.getElementById('challengeTitle').textContent = challengeTitle;
        document.getElementById('rewardPoints').innerHTML = '<i class="bi bi-star-fill me-1"></i>' + rewardPoints + ' Poin';
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('claimRewardModal'));
        modal.show();
    }
    
    document.getElementById('confirmClaimBtn').addEventListener('click', function() {
        if (currentFormAction) {
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('claimRewardModal'));
            modal.hide();
            
            // Submit form
            const form = document.getElementById('claimRewardForm');
            form.action = currentFormAction;
            form.submit();
        }
    });
</script>
@endpush
@endsection
