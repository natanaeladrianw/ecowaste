@extends('layouts.admin')

@section('title', 'Edit Bank Sampah')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.bank-sampah.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Bank Sampah
            </h2>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-building me-2"></i>Informasi Bank Sampah
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.bank-sampah.update', $bankSampah->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Nama Bank Sampah -->
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">
                            Nama Bank Sampah <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $bankSampah->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">
                            Alamat <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3" 
                                  required>{{ old('address', $bankSampah->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telepon & Email -->
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1"></i>Telepon
                        </label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $bankSampah->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $bankSampah->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jam Operasional -->
                    <div class="col-md-12 mb-3">
                        <label for="operating_hours" class="form-label">
                            <i class="bi bi-clock me-1"></i>Jam Operasional
                        </label>
                        <input type="text" 
                               class="form-control @error('operating_hours') is-invalid @enderror" 
                               id="operating_hours" 
                               name="operating_hours" 
                               value="{{ old('operating_hours', $bankSampah->operating_hours) }}"
                               placeholder="Contoh: Senin - Jumat: 08:00 - 17:00">
                        @error('operating_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Masukkan jam operasional bank sampah</small>
                    </div>

                    <!-- Lokasi dengan Autocomplete -->
                    <div class="col-md-12 mb-3 position-relative">
                        <label for="location" class="form-label">
                            <i class="bi bi-geo-alt me-1"></i>Lokasi
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="location" 
                               value="{{ old('location_name', $bankSampah->location_name) }}"
                               placeholder="Cari nama kecamatan, kota, atau alamat..."
                               autocomplete="off">
                        <small class="form-text text-muted">Ketik nama kecamatan atau lokasi untuk mencari koordinat</small>
                        <div id="locationSuggestions" class="list-group mt-1" style="display: none; position: absolute; z-index: 1000; max-height: 300px; overflow-y: auto; width: 100%; top: 100%;"></div>
                        
                        <!-- Hidden fields untuk menyimpan latitude, longitude, dan location_name -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $bankSampah->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $bankSampah->longitude) }}">
                        <input type="hidden" id="location_name" name="location_name" value="{{ old('location_name', $bankSampah->location_name) }}">
                        
                        <!-- Display selected location info -->
                        <div id="locationInfo" class="mt-2" style="display: {{ ($bankSampah->location_name) ? 'block' : 'none' }};">
                            <div class="alert alert-info">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                <span id="selectedLocationName">{{ $bankSampah->location_name ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">
                            <i class="bi bi-file-text me-1"></i>Deskripsi
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4">{{ old('description', $bankSampah->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Deskripsi tentang bank sampah</small>
                    </div>

                    <!-- Status Aktif -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $bankSampah->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                        <small class="form-text text-muted">Aktifkan atau nonaktifkan bank sampah ini</small>
                    </div>

                    <!-- Foto -->
                    <div class="col-md-12 mb-3">
                        <label for="photo" class="form-label">
                            <i class="bi bi-image me-1"></i>Foto
                        </label>
                        @if($bankSampah->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $bankSampah->photo) }}" 
                                     alt="{{ $bankSampah->name }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px; max-height: 200px;">
                                <p class="text-muted small mb-0">Foto saat ini</p>
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" 
                               name="photo" 
                               accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, PNG, GIF (Maks: 2MB). Kosongkan jika tidak ingin mengubah foto.</small>
                        <div id="photoPreview" class="mt-2"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.bank-sampah.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Photo preview
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('photoPreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i>Preview foto baru:
                    </div>
                    <img src="${e.target.result}" 
                         alt="Preview" 
                         class="img-thumbnail" 
                         style="max-width: 200px; max-height: 200px;">
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });

    // Location Autocomplete dengan Nominatim API
    let searchTimeout;
    const locationInput = document.getElementById('location');
    const suggestionsDiv = document.getElementById('locationSuggestions');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const locationInfo = document.getElementById('locationInfo');
    const selectedLocationName = document.getElementById('selectedLocationName');
    const locationNameInput = document.getElementById('location_name');

    locationInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Hide suggestions if input is empty
        if (query.length < 3) {
            suggestionsDiv.style.display = 'none';
            return;
        }

        // Debounce search
        searchTimeout = setTimeout(() => {
            searchLocation(query);
        }, 500);
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        const locationContainer = locationInput.closest('.position-relative');
        if (!locationContainer.contains(e.target)) {
            suggestionsDiv.style.display = 'none';
        }
    });

    function searchLocation(query) {
        // Nominatim API untuk geocoding (gratis, tidak perlu API key)
        // Membatasi pencarian ke Indonesia dengan parameter countrycodes=id
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Indonesia')}&countrycodes=id&limit=10&addressdetails=1`;
        
        suggestionsDiv.innerHTML = '<div class="list-group-item"><small class="text-muted">Mencari...</small></div>';
        suggestionsDiv.style.display = 'block';

        fetch(url, {
            headers: {
                'User-Agent': 'BankSampahApp/1.0' // Required by Nominatim
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                suggestionsDiv.innerHTML = '<div class="list-group-item"><small class="text-muted">Tidak ada hasil ditemukan</small></div>';
                return;
            }

            suggestionsDiv.innerHTML = '';
            data.forEach((place, index) => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                
                // Format alamat yang lebih baik
                const address = place.address || {};
                let displayName = place.display_name;
                
                // Coba buat nama yang lebih ringkas
                if (address.village || address.suburb) {
                    displayName = `${address.village || address.suburb}, ${address.city || address.town || address.county || ''}`;
                } else if (address.city || address.town) {
                    displayName = `${address.city || address.town}, ${address.state || ''}`;
                }
                
                item.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <strong>${displayName}</strong>
                            <br>
                            <small class="text-muted">${place.display_name}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">${parseFloat(place.lat).toFixed(6)}, ${parseFloat(place.lon).toFixed(6)}</small>
                        </div>
                    </div>
                `;
                
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    selectLocation(place, displayName);
                });
                
                suggestionsDiv.appendChild(item);
            });
        })
        .catch(error => {
            console.error('Error searching location:', error);
            suggestionsDiv.innerHTML = '<div class="list-group-item"><small class="text-danger">Error saat mencari lokasi</small></div>';
        });
    }

    function selectLocation(place, displayName) {
        // Set nilai input
        locationInput.value = displayName;
        
        // Set latitude, longitude, dan location_name
        latitudeInput.value = place.lat;
        longitudeInput.value = place.lon;
        locationNameInput.value = displayName;
        
        // Show location info dengan nama lokasi saja
        selectedLocationName.textContent = displayName;
        locationInfo.style.display = 'block';
        
        // Hide suggestions
        suggestionsDiv.style.display = 'none';
    }
</script>
<style>
    #locationSuggestions {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-top: 0.25rem;
    }
    #locationSuggestions .list-group-item {
        border-left: none;
        border-right: none;
        cursor: pointer;
        padding: 0.75rem 1rem;
    }
    #locationSuggestions .list-group-item:first-child {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
    #locationSuggestions .list-group-item:last-child {
        border-bottom-left-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    #locationSuggestions .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection

