@extends('layouts.admin')

@section('title', isset($reward) ? 'Edit Reward' : 'Tambah Reward')

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
            <a href="{{ route('admin.rewards.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-{{ isset($reward) ? 'pencil' : 'plus-circle' }} me-2"></i>
                {{ isset($reward) ? 'Edit Reward' : 'Tambah Reward' }}
            </h2>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-info-circle me-2"></i>Informasi Reward
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($reward) ? route('admin.rewards.update', $reward->id) : route('admin.rewards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($reward))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            Nama Reward <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $reward->name ?? '') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="points_required" class="form-label">
                            <i class="bi bi-star-fill me-1"></i>Poin Dibutuhkan <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('points_required') is-invalid @enderror" 
                               id="points_required" 
                               name="points_required" 
                               value="{{ old('points_required', $reward->points_required ?? '') }}" 
                               min="1"
                               required>
                        @error('points_required')
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
                              rows="3">{{ old('description', $reward->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">
                            <i class="bi bi-tag me-1"></i>Tipe Reward
                        </label>
                        <input type="text" 
                               class="form-control @error('type') is-invalid @enderror" 
                               id="type" 
                               name="type" 
                               value="{{ old('type', $reward->type ?? '') }}"
                               placeholder="Contoh: Voucher, Pulsa, Barang, dll">
                        <small class="form-text text-muted">
                            Masukkan tipe reward (contoh: Voucher, Pulsa, Barang, Cashback, Diskon, dll)
                        </small>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="value" class="form-label">
                            <i class="bi bi-currency-exchange me-1"></i>Nilai Reward
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" 
                                   class="form-control @error('value') is-invalid @enderror" 
                                   id="value" 
                                   name="value" 
                                   value="{{ old('value', $reward->value ?? '') }}"
                                   placeholder="0"
                                   onkeyup="formatCurrency(this)">
                        </div>
                        <small class="form-text text-muted">
                            Masukkan nominal reward dalam Rupiah
                        </small>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">
                            <i class="bi bi-box-seam me-1"></i>Stok <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" 
                               name="stock" 
                               value="{{ old('stock', $reward->stock ?? 0) }}" 
                               min="0"
                               required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">
                            <i class="bi bi-image me-1"></i>Gambar Reward
                        </label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <small class="form-text text-muted">
                            Upload gambar reward (maks. 2MB)
                        </small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(isset($reward) && $reward->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $reward->image) }}" 
                                     alt="{{ $reward->name }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px;">
                                <p class="text-muted small mb-0 mt-1">Gambar saat ini</p>
                            </div>
                        @endif
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            <p class="text-muted small mb-0 mt-1">Preview gambar baru</p>
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
                               {{ old('is_active', $reward->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-toggle-on me-1"></i>Reward Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ isset($reward) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Format currency function
    function formatCurrency(input) {
        // Remove non-numeric characters
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format with thousand separators
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
            input.value = value;
        } else {
            input.value = '';
        }
    }
    
    // Remove formatting before form submit
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const valueInput = document.getElementById('value');
        
        // Format existing value on page load
        if (valueInput.value) {
            let numericValue = valueInput.value.replace(/[^\d]/g, '');
            if (numericValue) {
                valueInput.value = parseInt(numericValue).toLocaleString('id-ID');
            }
        }
        
        // Remove formatting before submit
        form.addEventListener('submit', function(e) {
            if (valueInput.value) {
                valueInput.value = valueInput.value.replace(/[^\d]/g, '');
            }
        });
        
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    e.target.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('File yang dipilih harus berupa gambar.');
                    e.target.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection

