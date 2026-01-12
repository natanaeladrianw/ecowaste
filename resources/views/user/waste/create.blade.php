@extends('layouts.app')

@section('title', 'Input Data Sampah')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Informasi Poin -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi Poin
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($categories as $category)
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>{{ $category->name }}:</strong> {{ $category->points_per_kg }} poin/kg
                            </div>
                        @endforeach
                    </div>
                    <div class="alert alert-warning mt-2">
                        <small>
                            <i class="bi bi-exclamation-triangle"></i> 
                            Poin akan ditambahkan setelah data diverifikasi oleh sistem.
                        </small>
                    </div>
                </div>
            </div>
            
            @if(isset($challenge) && $challenge)
            <!-- Challenge Info Alert -->
            <div class="alert alert-success mb-4">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <i class="bi bi-trophy-fill fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">
                            <i class="bi bi-flag-fill me-1"></i>Anda sedang mengikuti tantangan:
                        </h6>
                        <strong class="d-block mb-2">{{ $challenge->title }}</strong>
                        <p class="mb-2 small">{{ $challenge->description }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">
                                <i class="bi bi-tag me-1"></i>{{ $challenge->targetCategory->name ?? 'Kategori' }}
                            </span>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-bullseye me-1"></i>Target: {{ $challenge->target_amount }} {{ $challenge->target_unit ?? 'kg' }}
                            </span>
                            <span class="badge bg-success">
                                <i class="bi bi-star-fill me-1"></i>{{ $challenge->points_reward }} Poin
                            </span>
                        </div>
                        <input type="hidden" form="wasteForm" name="challenge_id" value="{{ $challenge->id }}">
                    </div>
                </div>
            </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Input Data Sampah Harian
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.waste.store') }}" id="wasteForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="time" class="form-label">Waktu</label>
                                <input type="time" class="form-control" id="time" name="time" 
                                       value="{{ date('H:i') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori Sampah <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            data-points="{{ $category->points_per_kg }}"
                                            {{ (old('category_id') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="bank_sampah_id" class="form-label">Lokasi Bank Sampah <span class="text-danger">*</span></label>
                            <select class="form-select @error('bank_sampah_id') is-invalid @enderror" id="bank_sampah_id" name="bank_sampah_id" required>
                                <option value="">Pilih Lokasi Bank Sampah</option>
                                @foreach($bankSampah as $bank)
                                    <option value="{{ $bank->id }}" 
                                            {{ old('bank_sampah_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->name }} - {{ $bank->address }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_sampah_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih lokasi Bank Sampah tempat Anda menyerahkan sampah</small>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Jumlah</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" 
                                           id="amount" name="amount" placeholder="0.00" required>
                                    <select class="form-select" id="unit" name="unit" style="max-width: 120px;">
                                        <option value="kg">kg</option>
                                        <option value="gram">gram</option>
                                        <option value="unit">unit</option>
                                        <option value="liter">liter</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Perkiraan Poin</label>
                                <div class="alert alert-info py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Poin yang akan didapat:</span>
                                        <strong id="pointsEstimate">0 poin</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3" placeholder="Tambahkan keterangan jika perlu"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Lampiran Foto <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*" capture="environment" required>
                            <small class="text-muted">
                                <i class="bi bi-camera me-1"></i>
                                Upload foto sampah sebagai bukti atau ambil foto langsung dari kamera (maks. 2MB)
                            </small>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="photoPreview" class="mt-3" style="display: none;">
                                <img id="previewImage" src="" alt="Preview foto" 
                                     class="img-thumbnail" 
                                     style="max-height: 300px; max-width: 100%;">
                                <p class="text-muted small mt-2 mb-0">Preview foto yang akan diupload</p>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.waste.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Calculate points estimate
    function calculatePoints() {
        const categorySelect = document.getElementById('category_id');
        const category = categorySelect.value;
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const unit = document.getElementById('unit').value;
        
        let points = 0;
        let amountInKg = amount;
        
        // Convert to kg for calculation
        if (unit === 'gram') amountInKg = amount / 1000;
        
        if (category && amountInKg > 0) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const pointsPerKg = parseFloat(selectedOption.getAttribute('data-points')) || 0;
            points = Math.round(amountInKg * pointsPerKg);
        }
        
        document.getElementById('pointsEstimate').textContent = points + ' poin';
    }
    
    // Add event listeners
    document.getElementById('category_id').addEventListener('change', calculatePoints);
    document.getElementById('amount').addEventListener('input', calculatePoints);
    document.getElementById('unit').addEventListener('change', calculatePoints);
    
    // Calculate on page load
    calculatePoints();
    
    // Photo preview
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('photoPreview');
        const previewImage = document.getElementById('previewImage');
        
        if (file) {
            // Check file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Check file type
            if (!file.type.startsWith('image/')) {
                alert('File yang dipilih harus berupa gambar.');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush
@endsection