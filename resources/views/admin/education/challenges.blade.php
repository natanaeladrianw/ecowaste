@extends('layouts.admin')

@section('title', 'Challenges')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h2 class="mb-0">
            <i class="bi bi-trophy me-2"></i>Daftar Challenges
        </h2>
        <a href="{{ route('admin.education.challenges.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Challenge</span><span class="d-md-none">Tambah</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="card admin-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.education.challenges.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Challenge</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari judul atau deskripsi...">
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Tipe Challenge</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="daily" {{ request('type') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request('type') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Challenges List -->
    <div class="challenges-list">
        @forelse($challenges as $challenge)
            <div class="challenge-item mb-3">
                <div class="card admin-card h-100">
                    <div class="card-body d-flex flex-column">
                        <!-- Desktop Layout -->
                        <div class="d-none d-md-block">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <h5 class="card-title mb-0">{{ $challenge->title }}</h5>
                                <div class="d-flex flex-column gap-1">
                                    @if($challenge->is_active)
                                        @php
                                            $now = now();
                                            $status = '';
                                            if ($now < $challenge->start_date) {
                                                $status = 'upcoming';
                                            } elseif ($now > $challenge->end_date) {
                                                $status = 'expired';
                                            } else {
                                                $status = 'active';
                                            }
                                        @endphp
                                        @if($status == 'active')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        @elseif($status == 'upcoming')
                                            <span class="badge bg-info">
                                                <i class="bi bi-clock me-1"></i>Akan Datang
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Kedaluwarsa
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                        </span>
                                    @endif
                                    <span class="badge bg-primary">
                                        {{ ucfirst($challenge->type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($challenge->description, 100) }}
                            </p>

                            <div class="mb-2">
                                @if($challenge->targetCategory)
                                    <span class="badge bg-info mb-1">
                                        <i class="bi bi-tag me-1"></i>{{ $challenge->targetCategory->name }}
                                    </span>
                                @endif
                                @if($challenge->target_amount)
                                    <span class="badge bg-warning text-dark mb-1">
                                        <i class="bi bi-bullseye me-1"></i>Target: {{ number_format($challenge->target_amount) }} {{ $challenge->target_unit ?? 'kg' }}
                                    </span>
                                @endif
                                <span class="badge bg-success mb-1">
                                    <i class="bi bi-star-fill me-1"></i>{{ number_format($challenge->points_reward) }} Poin
                                </span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <strong>Mulai:</strong> {{ $challenge->start_date->format('d M Y') }}
                                </small><br>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    <strong>Selesai:</strong> {{ $challenge->end_date->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        
                        <!-- Mobile Layout (List) -->
                        <div class="d-md-none challenge-mobile">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3 flex-shrink-0">
                                    <i class="bi bi-trophy-fill text-success fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0 fw-bold">{{ $challenge->title }}</h6>
                                        <div class="d-flex flex-column gap-1">
                                            @if($challenge->is_active)
                                                @php
                                                    $now = now();
                                                    $status = '';
                                                    if ($now < $challenge->start_date) {
                                                        $status = 'upcoming';
                                                    } elseif ($now > $challenge->end_date) {
                                                        $status = 'expired';
                                                    } else {
                                                        $status = 'active';
                                                    }
                                                @endphp
                                                @if($status == 'active')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                                    </span>
                                                @elseif($status == 'upcoming')
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-clock me-1"></i>Akan Datang
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle me-1"></i>Kedaluwarsa
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                                </span>
                                            @endif
                                            <span class="badge bg-primary">
                                                {{ ucfirst($challenge->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit($challenge->description, 100) }}
                                    </p>
                                    
                                    <div class="mb-2">
                                        @if($challenge->targetCategory)
                                            <span class="badge bg-info mb-1">
                                                <i class="bi bi-tag me-1"></i>{{ $challenge->targetCategory->name }}
                                            </span>
                                        @endif
                                        @if($challenge->target_amount)
                                            <span class="badge bg-warning text-dark mb-1">
                                                <i class="bi bi-bullseye me-1"></i>Target: {{ number_format($challenge->target_amount) }} {{ $challenge->target_unit ?? 'kg' }}
                                            </span>
                                        @endif
                                        <span class="badge bg-success mb-1">
                                            <i class="bi bi-star-fill me-1"></i>{{ number_format($challenge->points_reward) }} Poin
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Mulai: {{ $challenge->start_date->format('d M Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Selesai: {{ $challenge->end_date->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.education.challenges.edit', $challenge->id) }}" 
                               class="btn btn-sm btn-warning d-flex align-items-center gap-1 flex-grow-1 justify-content-center">
                                <i class="bi bi-pencil-fill"></i>
                                <span>Edit</span>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger d-flex align-items-center gap-1 flex-grow-1 justify-content-center"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteChallengeModal{{ $challenge->id }}">
                                <i class="bi bi-trash-fill"></i>
                                <span>Hapus</span>
                            </button>
                            
                            <!-- Delete Challenge Modal -->
                            <div class="modal fade" id="deleteChallengeModal{{ $challenge->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle me-2"></i>Hapus Challenge
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus challenge <strong>{{ $challenge->title }}</strong>?</p>
                                            <div class="alert alert-danger mb-0">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Data challenge akan dihapus secara permanen.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>Batal
                                            </button>
                                            <form action="{{ route('admin.education.challenges.destroy', $challenge->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash me-2"></i>Ya, Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card admin-card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox display-6 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Belum ada challenge</h5>
                        <p class="text-muted">Mulai dengan menambahkan challenge pertama Anda</p>
                        <a href="{{ route('admin.education.challenges.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Challenge
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($challenges->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $challenges->links() }}
        </div>
    @endif
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
        
        .challenge-mobile .card-footer {
            padding: 0.75rem;
        }
    }
</style>
@endpush
@endsection

