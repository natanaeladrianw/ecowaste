@extends('layouts.admin')

@section('title', $challenge ? 'Edit Challenge' : 'Tambah Challenge')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.education.challenges.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <h2 class="mb-0">
                <i class="bi bi-{{ $challenge ? 'pencil-square' : 'plus-circle' }} me-2"></i>{{ $challenge ? 'Edit' : 'Tambah' }} Challenge
            </h2>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-trophy me-2"></i>Informasi Challenge
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" 
                  action="{{ $challenge ? route('admin.education.challenges.update', $challenge->id) : route('admin.education.challenges.store') }}">
                @csrf
                @if($challenge)
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Judul -->
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">
                            Judul Challenge <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $challenge->title ?? '') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">
                            Deskripsi <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  required>{{ old('description', $challenge->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Jelaskan detail challenge yang akan dibuat</small>
                    </div>

                    <!-- Tipe Challenge -->
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">
                            Tipe Challenge <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">Pilih Tipe</option>
                            <option value="daily" {{ old('type', $challenge->type ?? '') == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ old('type', $challenge->type ?? '') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ old('type', $challenge->type ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Target Kategori -->
                    <div class="col-md-4 mb-3">
                        <label for="target_category_id" class="form-label">
                            Kategori Target
                        </label>
                        <select class="form-select @error('target_category_id') is-invalid @enderror" 
                                id="target_category_id" 
                                name="target_category_id">
                            <option value="">Tidak Ada Kategori Khusus</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('target_category_id', $challenge->target_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('target_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kategori sampah yang ditargetkan (opsional)</small>
                    </div>

                    <!-- Poin Reward -->
                    <div class="col-md-4 mb-3">
                        <label for="points_reward" class="form-label">
                            Poin Reward <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('points_reward') is-invalid @enderror" 
                               id="points_reward" 
                               name="points_reward" 
                               value="{{ old('points_reward', $challenge->points_reward ?? '') }}" 
                               min="1" 
                               required>
                        @error('points_reward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Poin yang diberikan saat challenge selesai</small>
                    </div>

                    <!-- Target Amount -->
                    <div class="col-md-4 mb-3">
                        <label for="target_amount" class="form-label">
                            Target Jumlah
                        </label>
                        <input type="number" 
                               class="form-control @error('target_amount') is-invalid @enderror" 
                               id="target_amount" 
                               name="target_amount" 
                               value="{{ old('target_amount', $challenge->target_amount ?? '') }}" 
                               min="1">
                        @error('target_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Target jumlah yang harus dicapai</small>
                    </div>

                    <!-- Target Unit -->
                    <div class="col-md-4 mb-3">
                        <label for="target_unit" class="form-label">
                            Satuan Target
                        </label>
                        <input type="text" 
                               class="form-control @error('target_unit') is-invalid @enderror" 
                               id="target_unit" 
                               name="target_unit" 
                               value="{{ old('target_unit', $challenge->target_unit ?? 'kg') }}" 
                               placeholder="kg, unit, dll">
                        @error('target_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Satuan untuk target (contoh: kg, unit)</small>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">
                            Tanggal Mulai <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date', $challenge ? $challenge->start_date->format('Y-m-d') : '') }}" 
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">
                            Tanggal Selesai <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date', $challenge ? $challenge->end_date->format('Y-m-d') : '') }}" 
                               required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $challenge->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktifkan Challenge
                            </label>
                        </div>
                        <small class="form-text text-muted">Challenge yang aktif akan ditampilkan kepada user</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.education.challenges.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>{{ $challenge ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validate end date is after start date
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = document.getElementById('start_date').value;
        const endDate = this.value;
        
        if (startDate && endDate && endDate <= startDate) {
            alert('Tanggal selesai harus setelah tanggal mulai!');
            this.value = '';
        }
    });

    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        const endDate = document.getElementById('end_date').value;
        
        if (startDate && endDate && endDate <= startDate) {
            alert('Tanggal selesai harus setelah tanggal mulai!');
            document.getElementById('end_date').value = '';
        }
    });
</script>
@endpush
@endsection

