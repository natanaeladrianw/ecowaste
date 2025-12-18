@extends('layouts.app')

@section('title', 'Peta Bank Sampah')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('user.bank-sampah.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-map me-2"></i>Peta Bank Sampah
            </h2>
            <p class="text-muted mb-0">Lihat lokasi bank sampah di peta</p>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <label for="search" class="form-label">
                        <i class="bi bi-search me-1"></i>Cari Lokasi
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           placeholder="Masukkan alamat...">
                </div>
                <div class="col-12 col-md-4">
                    <label for="radius" class="form-label">
                        <i class="bi bi-circle me-1"></i>Radius (km)
                    </label>
                    <input type="number" 
                           class="form-control" 
                           id="radius" 
                           value="5" 
                           min="1" 
                           max="50">
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="card">
        <div class="card-body p-0">
            @if($bankSampah->count() > 0)
                <div id="map" style="height: 500px; width: 100%;"></div>
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 500px;">
                    <div class="text-center">
                        <i class="bi bi-map display-4 d-block mb-3 text-muted opacity-50"></i>
                        <p class="text-muted mb-0">Tidak ada bank sampah dengan koordinat tersedia</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bank Sampah List (Optional) -->
    @if($bankSampah->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-list-ul me-2"></i>Daftar Bank Sampah di Peta
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($bankSampah as $bank)
                    <div class="col-12 col-md-6">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <i class="bi bi-building me-1"></i>{{ $bank->name }}
                                </h6>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $bank->location_name ?? $bank->address }}
                                </p>
                                @if($bank->phone)
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-telephone me-1"></i>{{ $bank->phone }}
                                    </p>
                                @endif
                                <button class="btn btn-sm btn-primary" 
                                        onclick="focusMarker({{ $bank->id }})">
                                    <i class="bi bi-crosshair me-1"></i>Fokus ke Lokasi
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@if($bankSampah->count() > 0)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let markers = [];

    document.addEventListener('DOMContentLoaded', function() {
        // Default center (Jakarta)
        const defaultCenter = [-6.2088, 106.8456];
        
        // Initialize map
        map = L.map('map').setView(defaultCenter, 12);
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add markers for each bank sampah
        const bankSampahData = @json($bankSampah);
        const bounds = [];
        
        bankSampahData.forEach(function(bank) {
            if (bank.latitude && bank.longitude) {
                const position = [parseFloat(bank.latitude), parseFloat(bank.longitude)];
                
                // Create custom icon (green marker)
                const greenIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                
                // Create popup content
                let popupContent = `
                    <div style="padding: 5px; min-width: 200px;">
                        <h6 class="fw-bold mb-2" style="margin: 0; font-size: 14px;">${bank.name}</h6>
                        <p class="mb-1 small" style="margin: 5px 0;">${bank.location_name || bank.address || '-'}</p>
                `;
                
                if (bank.phone) {
                    popupContent += `<p class="mb-1 small" style="margin: 5px 0;"><i class="bi bi-telephone"></i> ${bank.phone}</p>`;
                }
                
                popupContent += `
                        <a href="/user/bank-sampah/${bank.id}" class="btn btn-sm btn-success mt-2" style="width: 100%; background-color: #28a745; border-color: #28a745; color: white; text-decoration: none;">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                `;
                
                const marker = L.marker(position, { icon: greenIcon })
                    .addTo(map)
                    .bindPopup(popupContent);
                
                markers.push({ id: bank.id, marker: marker });
                bounds.push(position);
            }
        });
        
        // Fit bounds to show all markers
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
        
        // Search functionality using Nominatim (OpenStreetMap)
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        searchLocation(query);
                    }
                }
            });
        }
    });
    
    function focusMarker(bankId) {
        const markerData = markers.find(m => m.id === bankId);
        if (markerData) {
            const position = markerData.marker.getLatLng();
            map.setView(position, 15);
            
            // Open popup
            markerData.marker.openPopup();
            
            // Bounce animation effect
            markerData.marker.setIcon(L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            }));
            
            setTimeout(function() {
                markerData.marker.setIcon(L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                }));
            }, 2000);
        }
    }
    
    function searchLocation(query) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Indonesia')}&countrycodes=id&limit=1&addressdetails=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const result = data[0];
                    const position = [parseFloat(result.lat), parseFloat(result.lon)];
                    map.setView(position, 15);
                } else {
                    alert('Lokasi tidak ditemukan');
                }
            })
            .catch(error => {
                console.error('Error searching location:', error);
                alert('Terjadi kesalahan saat mencari lokasi');
            });
    }
</script>
@endpush
@endif
@endsection
