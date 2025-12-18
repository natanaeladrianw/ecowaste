@extends('layouts.app')

@section('title', 'Detail Bank Sampah')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('user.bank-sampah.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-0">
            <i class="bi bi-geo-alt me-2"></i>Detail Bank Sampah
        </h2>
        <p class="text-muted mb-0">Informasi lengkap bank sampah</p>
    </div>

    <div class="row g-4">
        <!-- Information Section -->
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi
                    </h5>
                </div>
                <div class="card-body">
                    @if($bankSampah->photo)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $bankSampah->photo) }}" 
                                 alt="{{ $bankSampah->name }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px;">
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Nama</label>
                        <p class="mb-0 fw-bold fs-5">{{ $bankSampah->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Alamat</label>
                        <p class="mb-0">
                            <i class="bi bi-geo-alt me-1 text-primary"></i>
                            {{ $bankSampah->location_name ?? $bankSampah->address }}
                        </p>
                        @if($bankSampah->address && $bankSampah->location_name)
                            <small class="text-muted d-block mt-1">{{ $bankSampah->address }}</small>
                        @endif
                </div>

                    @if($bankSampah->phone)
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Telepon</label>
                        <p class="mb-0">
                            <i class="bi bi-telephone me-1 text-primary"></i>
                            <a href="tel:{{ $bankSampah->phone }}" class="text-decoration-none">
                                {{ $bankSampah->phone }}
                            </a>
                        </p>
                    </div>
                    @endif
                    
                    @if($bankSampah->email)
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Email</label>
                        <p class="mb-0">
                            <i class="bi bi-envelope me-1 text-primary"></i>
                            <a href="mailto:{{ $bankSampah->email }}" class="text-decoration-none">
                                {{ $bankSampah->email }}
                            </a>
                        </p>
                    </div>
                    @endif
                    
                    @if($bankSampah->operational_hours)
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Jam Operasional</label>
                        <p class="mb-0">
                            <i class="bi bi-clock me-1 text-primary"></i>
                            {{ $bankSampah->operational_hours }}
                        </p>
                            </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Status</label>
                        <p class="mb-0">
                            @if($bankSampah->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                </span>
                            @endif
                        </p>
                            </div>
                    
                    @if($bankSampah->description)
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Deskripsi</label>
                        <p class="mb-0">{{ $bankSampah->description }}</p>
                            </div>
                    @endif
                            </div>
                        </div>
                    </div>

        <!-- Location Section -->
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-geo-alt me-2"></i>Lokasi
                    </h5>
                </div>
                <div class="card-body">
                    @if($bankSampah->latitude && $bankSampah->longitude)
                        <div id="map" style="height: 400px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                        <div class="mt-3">
                            <p class="text-muted small mb-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Koordinat: {{ $bankSampah->latitude }}, {{ $bankSampah->longitude }}
                            </p>
                            <a href="https://www.google.com/maps?q={{ $bankSampah->latitude }},{{ $bankSampah->longitude }}" 
                               target="_blank" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-map me-1"></i>Buka di Google Maps
                            </a>
                        </div>
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                            <div class="text-center">
                                <i class="bi bi-map display-4 d-block mb-3 text-muted opacity-50"></i>
                                <p class="text-muted mb-0">Koordinat lokasi tidak tersedia</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($bankSampah->latitude && $bankSampah->longitude)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bankLocation = [{{ $bankSampah->latitude }}, {{ $bankSampah->longitude }}];
        
        // Initialize map
        const map = L.map('map').setView(bankLocation, 15);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add marker
        const marker = L.marker(bankLocation).addTo(map);
        
        // Create popup content
        let popupContent = `
            <div style="padding: 5px;">
                <h6 class="fw-bold mb-2" style="margin: 0; font-size: 14px;">{{ $bankSampah->name }}</h6>
                <p class="mb-1 small" style="margin: 5px 0;">{{ $bankSampah->location_name ?? $bankSampah->address }}</p>
        `;
        
        @if($bankSampah->phone)
            popupContent += `<p class="mb-0 small" style="margin: 5px 0;"><i class="bi bi-telephone"></i> {{ $bankSampah->phone }}</p>`;
        @endif
        
        popupContent += `</div>`;
        
        // Bind popup to marker
        marker.bindPopup(popupContent).openPopup();
    });
</script>
@endpush
@endif
@endsection
