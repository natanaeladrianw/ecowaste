@extends('layouts.app')

@section('title', 'Daftar Bank Sampah')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-geo-alt me-2"></i>Daftar Bank Sampah
            </h2>
            <p class="text-muted mb-0">Temukan lokasi bank sampah terdekat</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('user.bank-sampah.index') }}" class="row g-3">
                <div class="col-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Cari bank sampah..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </div>
                @if(request('search'))
                    <div class="col-12">
                        <a href="{{ route('user.bank-sampah.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Hapus Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Bank Sampah List -->
    @if($bankSampah->count() > 0)
        <div class="row g-3">
            @foreach($bankSampah as $bank)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm" style="transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                @if($bank->photo)
                                    <img src="{{ asset('storage/' . $bank->photo) }}" 
                                         alt="{{ $bank->name }}" 
                                         class="rounded me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-building fs-4"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1 fw-bold">{{ $bank->name }}</h5>
                                    @if($bank->is_active)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted mb-2 small">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $bank->location_name ?? $bank->address }}
                                </p>
                                @if($bank->phone)
                                    <p class="text-muted mb-2 small">
                                        <i class="bi bi-telephone me-1"></i>{{ $bank->phone }}
                                    </p>
                                @endif
                                @if($bank->email)
                                    <p class="text-muted mb-2 small">
                                        <i class="bi bi-envelope me-1"></i>{{ Str::limit($bank->email, 30) }}
                                    </p>
                                @endif
                                @if($bank->latitude && $bank->longitude)
                                    <p class="text-muted mb-0 small">
                                        <i class="bi bi-geo me-1"></i>Koordinat tersedia
                                    </p>
                                @endif
                </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    @if($bank->latitude && $bank->longitude)
                                        <a href="{{ route('user.bank-sampah.map') }}?bank={{ $bank->id }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-map me-1"></i>Lihat Peta
                                        </a>
                                    @endif
                                </div>
                                <a href="{{ route('user.bank-sampah.show', $bank->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                <p class="fs-5 mb-0 text-muted">
                    @if(request('search'))
                        Tidak ada bank sampah yang ditemukan untuk pencarian "{{ request('search') }}"
                    @else
                        Belum ada bank sampah tersedia
                    @endif
                </p>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
@endsection
