@extends('layouts.app')

@section('title', 'Riwayat Sampah')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Riwayat Data Sampah
            </h2>
            <p class="text-muted mb-0">Lihat semua riwayat input sampah Anda</p>
        </div>
        <a href="{{ route('user.waste.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Tambah Sampah
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <ul class="nav nav-pills" id="filterTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                        Semua
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="this-month-tab" data-bs-toggle="pill" data-bs-target="#this-month" type="button" role="tab">
                        Bulan Ini
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="last-month-tab" data-bs-toggle="pill" data-bs-target="#last-month" type="button" role="tab">
                        Bulan Lalu
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Waste History Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Tanggal & Waktu</th>
                            <th style="width: 20%;">Kategori</th>
                            <th style="width: 20%;">Jenis Sampah</th>
                            <th class="text-center" style="width: 12%;">Jumlah</th>
                            <th class="text-center" style="width: 10%;">Poin</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 13%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wastes as $waste)
                            <tr>
                                <td>
                                    <strong>{{ $waste->date->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $waste->time ?? $waste->created_at->format('H:i') }}</small>
                                </td>
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
                                <td>
                                    <strong>{{ $waste->type }}</strong>
                                    @if($waste->description)
                                        <br><small class="text-muted">{{ Str::limit($waste->description, 30) }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}</strong>
                                    <small class="text-muted d-block">{{ $waste->unit }}</small>
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
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('user.waste.edit', $waste->id) }}" 
                                           class="btn btn-sm btn-primary btn-action" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('user.waste.destroy', $waste->id) }}" 
                                              method="POST" 
                                              id="delete-form-{{ $waste->id }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-action" 
                                                    title="Hapus"
                                                    onclick="confirmDelete('delete-form-{{ $waste->id }}', 'Apakah Anda yakin ingin menghapus data sampah ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox display-4 d-block mb-2 opacity-50"></i>
                                    <p class="fs-5 mb-0">Belum ada riwayat data sampah</p>
                                    <a href="{{ route('user.waste.create') }}" class="btn btn-success btn-sm mt-3">
                                        <i class="bi bi-plus-circle me-1"></i>Tambah Data Sampah Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card stat-card text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Sampah</h6>
                            <h3 class="card-title mb-0">
                                {{ number_format($wastes->sum(function($w) { return $w->unit === 'gram' ? $w->amount / 1000 : $w->amount; }), 2) }} kg
                            </h3>
                        </div>
                        <i class="bi bi-trash display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Poin</h6>
                            <h3 class="card-title mb-0">
                                {{ number_format($wastes->sum('points_earned')) }}
                            </h3>
                        </div>
                        <i class="bi bi-award display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Transaksi</h6>
                            <h3 class="card-title mb-0">
                                {{ $wastes->count() }}
                            </h3>
                        </div>
                        <i class="bi bi-list-check display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }
    
    .btn-action:hover {
        transform: scale(1.1);
    }
</style>
@endpush
@endsection
