@extends('layouts.admin')

@section('title', isset($wasteType) ? 'Edit Tipe Sampah' : 'Tambah Tipe Sampah')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.waste-types.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-{{ isset($wasteType) ? 'pencil' : 'plus-circle' }} me-2"></i>
                {{ isset($wasteType) ? 'Edit Tipe Sampah' : 'Tambah Tipe Sampah' }}
            </h2>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-info-circle me-2"></i>Informasi Tipe Sampah
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($wasteType) ? route('admin.waste-types.update', $wasteType->id) : route('admin.waste-types.store') }}" method="POST">
                @csrf
                @if(isset($wasteType))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            Nama Tipe Sampah <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $wasteType->name ?? '') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sort_order" class="form-label">
                            <i class="bi bi-sort-numeric-down me-1"></i>Urutan Tampil
                        </label>
                        <input type="number" 
                               class="form-control @error('sort_order') is-invalid @enderror" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', $wasteType->sort_order ?? 0) }}" 
                               min="0">
                        <small class="form-text text-muted">
                            Urutan tampil di daftar (angka lebih kecil akan muncul lebih dulu)
                        </small>
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">
                        <i class="bi bi-file-text me-1"></i>Deskripsi
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3">{{ old('description', $wasteType->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="color" class="form-label">
                            <i class="bi bi-palette me-1"></i>Warna
                        </label>
                        <input type="color" 
                               class="form-control form-control-color @error('color') is-invalid @enderror" 
                               id="color" 
                               name="color" 
                               value="{{ old('color', $wasteType->color ?? '#28a745') }}">
                        <small class="form-text text-muted">
                            Pilih warna yang sesuai dengan tipe sampah
                        </small>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="icon" class="form-label">
                            <i class="bi bi-image me-1"></i>Icon
                        </label>
                        <select class="form-select @error('icon') is-invalid @enderror" 
                                id="icon" 
                                name="icon">
                            <option value="">Pilih Icon</option>
                            <option value="bi bi-trash" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-trash' ? 'selected' : '' }}>
                                🗑️ Trash (Sampah)
                            </option>
                            <option value="bi bi-recycle" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-recycle' ? 'selected' : '' }}>
                                ♻️ Recycle (Daur Ulang)
                            </option>
                            <option value="bi bi-flower1" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-flower1' ? 'selected' : '' }}>
                                🌸 Flower (Organik)
                            </option>
                            <option value="bi bi-box" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-box' ? 'selected' : '' }}>
                                📦 Box (Kotak)
                            </option>
                            <option value="bi bi-bag" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-bag' ? 'selected' : '' }}>
                                👜 Bag (Tas)
                            </option>
                            <option value="bi bi-cup" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-cup' ? 'selected' : '' }}>
                                ☕ Cup (Gelas)
                            </option>
                            <option value="bi bi-file-earmark" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-file-earmark' ? 'selected' : '' }}>
                                📄 File (Kertas)
                            </option>
                            <option value="bi bi-battery" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-battery' ? 'selected' : '' }}>
                                🔋 Battery (Baterai)
                            </option>
                            <option value="bi bi-lightning" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-lightning' ? 'selected' : '' }}>
                                ⚡ Lightning (Listrik)
                            </option>
                            <option value="bi bi-droplet" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-droplet' ? 'selected' : '' }}>
                                💧 Droplet (Cairan)
                            </option>
                            <option value="bi bi-fire" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-fire' ? 'selected' : '' }}>
                                🔥 Fire (Api)
                            </option>
                            <option value="bi bi-tree" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-tree' ? 'selected' : '' }}>
                                🌳 Tree (Pohon)
                            </option>
                            <option value="bi bi-flower3" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-flower3' ? 'selected' : '' }}>
                                🍃 Leaf (Daun)
                            </option>
                            <option value="bi bi-apple" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-apple' ? 'selected' : '' }}>
                                🍎 Apple (Buah)
                            </option>
                            <option value="bi bi-circle" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-circle' ? 'selected' : '' }}>
                                ⭕ Circle (Lingkaran)
                            </option>
                            <option value="bi bi-star" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-star' ? 'selected' : '' }}>
                                ⭐ Star (Bintang)
                            </option>
                            <option value="bi bi-tag" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-tag' ? 'selected' : '' }}>
                                🏷️ Tag (Label)
                            </option>
                            <option value="bi bi-grid" {{ old('icon', $wasteType->icon ?? '') == 'bi bi-grid' ? 'selected' : '' }}>
                                ⚏ Grid (Grid)
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            Pilih icon yang sesuai dengan tipe sampah
                        </small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="iconPreview" class="mt-2">
                            @php
                                $currentIcon = old('icon', $wasteType->icon ?? '');
                            @endphp
                            @if($currentIcon)
                                <small class="text-muted">Preview: </small>
                                <i class="{{ $currentIcon }} fs-4"></i>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $wasteType->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Tipe Sampah Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.waste-types.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ isset($wasteType) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconSelect = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');
        
        iconSelect.addEventListener('change', function() {
            const selectedIcon = this.value;
            if (selectedIcon) {
                iconPreview.innerHTML = `
                    <small class="text-muted">Preview: </small>
                    <i class="${selectedIcon} fs-4"></i>
                `;
            } else {
                iconPreview.innerHTML = '';
            }
        });
    });
</script>
@endpush
@endsection

