@extends('layouts.app')

@section('title', 'Daftar Sampah')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-trash me-2"></i>Data Sampah
            </h2>
            <p class="text-muted mb-0">Kelola data sampah harian Anda</p>
        </div>
        <a href="{{ route('user.waste.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Tambah Sampah
                    </a>
                </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-4">
            <div class="card stat-card text-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-1" style="font-size: 0.75rem;">Total Sampah</h6>
                            <h4 class="card-title mb-0" style="font-size: 1.25rem; font-weight: 600;">
                                {{ number_format($totalWasteKg, 2) }} kg
                            </h4>
                        </div>
                        <i class="bi bi-trash" style="font-size: 1.5rem; opacity: 0.8; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stat-card text-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-1" style="font-size: 0.75rem;">Total Poin</h6>
                            <h4 class="card-title mb-0" style="font-size: 1.25rem; font-weight: 600;">
                                {{ number_format($userTotalPoints) }}
                            </h4>
                        </div>
                        <i class="bi bi-award" style="font-size: 1.5rem; opacity: 0.8; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stat-card text-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-1" style="font-size: 0.75rem;">Total Transaksi</h6>
                            <h4 class="card-title mb-0" style="font-size: 1.25rem; font-weight: 600;">
                                {{ number_format($totalTransactions) }}
                            </h4>
                        </div>
                        <i class="bi bi-list-check" style="font-size: 1.5rem; opacity: 0.8; flex-shrink: 0; margin-left: 0.5rem; align-self: flex-start;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Waste List -->
    <div class="waste-list">
        @forelse($wastes as $waste)
            <div class="card mb-3 shadow-sm waste-item" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="card-body" data-bs-toggle="collapse" data-bs-target="#waste-detail-{{ $waste->id }}" aria-expanded="false">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($waste->category)
                                    <span class="badge" style="background-color: {{ $waste->category->color ?? '#6c757d' }}; color: white;">
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
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $waste->date->format('d/m/Y') }}</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $waste->time ?? $waste->created_at->format('H:i') }}</span>
                                <span><i class="bi bi-box-seam me-1"></i>{{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }} {{ $waste->unit }}</span>
                                <span><i class="bi bi-star-fill text-warning me-1"></i>{{ $waste->points_earned ?? 0 }} Poin</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down ms-2 text-muted" style="font-size: 1.2rem;"></i>
                    </div>
                </div>
                
                <!-- Detail Section -->
                <div class="collapse" id="waste-detail-{{ $waste->id }}">
                    <div class="card-body border-top bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2"><i class="bi bi-info-circle me-1"></i>Informasi Detail</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" style="width: 40%;">Tanggal:</td>
                                        <td><strong>{{ $waste->date->format('d F Y') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Waktu:</td>
                                        <td><strong>{{ $waste->time ?? $waste->created_at->format('H:i') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Kategori:</td>
                                        <td>
                                            @if($waste->category)
                                                <span class="badge" style="background-color: {{ $waste->category->color ?? '#6c757d' }}; color: white;">
                                                    <i class="bi {{ $waste->category->icon ?? 'bi-circle' }} me-1"></i>
                                                    {{ $waste->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis Sampah:</td>
                                        <td><strong>{{ $waste->type }}</strong></td>
                                    </tr>
                                    @if($waste->description)
                                    <tr>
                                        <td class="text-muted">Deskripsi:</td>
                                        <td>{{ $waste->description }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2"><i class="bi bi-calculator me-1"></i>Jumlah & Poin</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" style="width: 40%;">Jumlah:</td>
                                        <td><strong>{{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }} {{ $waste->unit }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Poin:</td>
                                        <td>
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-star-fill me-1"></i>{{ $waste->points_earned ?? 0 }} Poin
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status:</td>
                                        <td>
                                            @if($waste->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($waste->status === 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($waste->bankSampah)
                                    <tr>
                                        <td class="text-muted">Bank Sampah:</td>
                                        <td><strong>{{ $waste->bankSampah->name }}</strong></td>
                            </tr>
                                    @endif
                            <tr>
                                        <td class="text-muted">Dibuat:</td>
                                        <td><small>{{ $waste->created_at->format('d/m/Y H:i') }}</small></td>
                            </tr>
                    </table>
                </div>
            </div>
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('user.waste.edit', $waste->id) }}" 
                                   class="btn btn-sm btn-primary" 
                                   onclick="event.stopPropagation();">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                                @if($waste->status !== 'approved')
                                    <form action="{{ route('user.waste.destroy', $waste->id) }}" 
                                          method="POST" 
                                          id="delete-form-{{ $waste->id }}"
                                          class="d-inline"
                                          onclick="event.stopPropagation();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete('delete-form-{{ $waste->id }}', 'Apakah Anda yakin ingin menghapus data sampah ini?')">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                @endif
                </div>
            </div>
        </div>
    </div>
</div>
        @empty
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-inbox display-4 d-block mb-2 opacity-50"></i>
                    <p class="fs-5 mb-0">Belum ada data sampah</p>
                    <a href="{{ route('user.waste.create') }}" class="btn btn-success btn-sm mt-3">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Data Sampah Pertama
                    </a>
                </div>
            </div>
        @endforelse

        @if($wastes->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $wastes->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .waste-item {
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }
    
    .waste-item:hover {
        border-left-color: var(--primary-color);
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }
    
    .waste-item[aria-expanded="true"] {
        border-left-color: var(--primary-color);
    }
    
    .waste-item .card-body[data-bs-toggle="collapse"] {
        cursor: pointer;
    }
    
    .waste-item .card-body[data-bs-toggle="collapse"]:hover {
        background-color: #f8f9fa;
    }
    
    .waste-item .bi-chevron-down {
        transition: transform 0.3s ease;
    }
    
    .waste-item[aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }
    
    .waste-list .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush
@endsection
