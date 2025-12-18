@extends('layouts.admin')

@section('title', $tip ? 'Edit Tips' : 'Tambah Tips')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.education.tips.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-{{ $tip ? 'pencil-square' : 'plus-circle' }} me-2"></i>{{ $tip ? 'Edit' : 'Tambah' }} Tips
            </h2>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-lightbulb me-2"></i>Informasi Tips
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" 
                  action="{{ $tip ? route('admin.education.tips.update', $tip->id) : route('admin.education.tips.store') }}" 
                  enctype="multipart/form-data">
                @csrf
                @if($tip)
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Judul -->
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">
                            Judul Tips <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $tip->title ?? '') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">
                            <i class="bi bi-tag me-1"></i>Kategori
                        </label>
                        <input type="text" 
                               class="form-control @error('category') is-invalid @enderror" 
                               id="category" 
                               name="category" 
                               value="{{ old('category', $tip->category ?? '') }}"
                               placeholder="Contoh: Daur Ulang, Pengelolaan Sampah">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured & Active -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured" 
                                       value="1"
                                       {{ old('is_featured', $tip->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </label>
                            </div>
                            @if($tip)
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $tip->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="bi bi-toggle-on me-1"></i>Aktif
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="col-md-12 mb-3">
                        <label for="content" class="form-label">
                            Konten <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="8" 
                                  required>{{ old('content', $tip->content ?? '') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Masukkan konten tips edukasi</small>
                    </div>

                    <!-- Gambar -->
                    <div class="col-md-12 mb-3">
                        <label for="image" class="form-label">
                            <i class="bi bi-image me-1"></i>Gambar
                        </label>
                        @if($tip && $tip->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $tip->image) }}" 
                                     alt="{{ $tip->title }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 300px; max-height: 300px;">
                                <p class="text-muted small mb-0">Gambar saat ini</p>
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, PNG, GIF (Maks: 2MB). {{ $tip ? 'Kosongkan jika tidak ingin mengubah gambar.' : '' }}</small>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.education.tips.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>{{ $tip ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i>Preview gambar baru:
                    </div>
                    <img src="${e.target.result}" 
                         alt="Preview" 
                         class="img-thumbnail" 
                         style="max-width: 300px; max-height: 300px;">
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });
</script>
@endpush
@endsection

