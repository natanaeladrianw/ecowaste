@extends('layouts.admin')

@section('title', $article ? 'Edit Artikel' : 'Tambah Artikel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.education.articles.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-{{ $article ? 'pencil-square' : 'plus-circle' }} me-2"></i>{{ $article ? 'Edit' : 'Tambah' }} Artikel
            </h2>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-newspaper me-2"></i>Informasi Artikel
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" 
                  action="{{ $article ? route('admin.education.articles.update', $article->id) : route('admin.education.articles.store') }}" 
                  enctype="multipart/form-data">
                @csrf
                @if($article)
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Judul -->
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">
                            Judul Artikel <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $article->title ?? '') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="col-md-12 mb-3">
                        <label for="excerpt" class="form-label">
                            Ringkasan
                        </label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" 
                                  name="excerpt" 
                                  rows="3">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ringkasan singkat artikel (opsional)</small>
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">
                            Kategori
                        </label>
                        <input type="text" 
                               class="form-control @error('category') is-invalid @enderror" 
                               id="category" 
                               name="category" 
                               value="{{ old('category', $article->category ?? '') }}"
                               placeholder="Contoh: Lingkungan, Daur Ulang">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Published -->
                    @if($article)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_published" 
                                       name="is_published" 
                                       value="1"
                                       {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    <i class="bi bi-toggle-on me-1"></i>Publish Artikel
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- Konten -->
                    <div class="col-md-12 mb-3">
                        <label for="content" class="form-label">
                            Konten <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="10" 
                                  required>{{ old('content', $article->content ?? '') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Isi artikel lengkap</small>
                    </div>

                    <!-- Gambar -->
                    <div class="col-md-12 mb-3">
                        <label for="image" class="form-label">
                            <i class="bi bi-image me-1"></i>Gambar
                        </label>
                        @if($article && $article->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $article->image) }}" 
                                     alt="{{ $article->title }}" 
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
                        <small class="form-text text-muted">Format: JPG, PNG, GIF (Maks: 2MB). {{ $article ? 'Kosongkan jika tidak ingin mengubah gambar.' : '' }}</small>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.education.articles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>{{ $article ? 'Update' : 'Simpan' }}
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

