@extends('layouts.app')

@section('title', 'Edit Data Sampah')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Data Sampah
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.waste.update', $waste->id) }}" id="wasteForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" 
                                       value="{{ old('date', $waste->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="time" class="form-label">Waktu</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                       id="time" name="time" 
                                       value="{{ old('time', $waste->time ?? $waste->created_at->format('H:i')) }}">
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori Sampah <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            data-points="{{ $category->points_per_kg }}"
                                            {{ old('category_id', $waste->category_id) == $category->id ? 'selected' : '' }}>
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
                            <select class="form-select @error('bank_sampah_id') is-invalid @enderror" 
                                    id="bank_sampah_id" name="bank_sampah_id" required>
                                <option value="">Pilih Lokasi Bank Sampah</option>
                                @foreach($bankSampah as $bank)
                                    <option value="{{ $bank->id }}" 
                                            {{ old('bank_sampah_id', $waste->bank_sampah_id) == $bank->id ? 'selected' : '' }}>
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
                                <label for="amount" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" 
                                           placeholder="0.00" 
                                           value="{{ old('amount', $waste->amount) }}" required>
                                    <select class="form-select" id="unit" name="unit" style="max-width: 120px;">
                                        <option value="kg" {{ old('unit', $waste->unit) == 'kg' ? 'selected' : '' }}>kg</option>
                                        <option value="gram" {{ old('unit', $waste->unit) == 'gram' ? 'selected' : '' }}>gram</option>
                                        <option value="unit" {{ old('unit', $waste->unit) == 'unit' ? 'selected' : '' }}>unit</option>
                                        <option value="liter" {{ old('unit', $waste->unit) == 'liter' ? 'selected' : '' }}>liter</option>
                                    </select>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Perkiraan Poin</label>
                                <div class="alert alert-info py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Poin yang akan didapat:</span>
                                        <strong id="pointsEstimate">{{ $waste->points_earned ?? 0 }} poin</strong>
                                    </div>
                                </div>
                            </div>
                    </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" 
                                      rows="3" 
                                      placeholder="Tambahkan keterangan jika perlu">{{ old('description', $waste->description) }}</textarea>
                        @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <div class="mb-3">
                            <label class="form-label">Lampiran Foto (Opsional)</label>
                            @if($waste->photo)
                                <div class="mb-2" id="currentPhoto">
                                    <img src="{{ asset('storage/' . $waste->photo) }}" 
                                         alt="Foto sampah" 
                                         class="img-thumbnail" 
                                         style="max-height: 150px;">
                                    <p class="text-muted small mb-0">Foto saat ini</p>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" capture="environment">
                            <small class="text-muted">
                                <i class="bi bi-camera me-1"></i>
                                Upload foto baru untuk mengganti atau ambil foto langsung dari kamera (maks. 2MB)
                            </small>
                            <div id="photoPreview" class="mt-3" style="display: none;">
                                <img id="previewImage" src="" alt="Preview foto baru" 
                                     class="img-thumbnail" 
                                     style="max-height: 300px; max-width: 100%;">
                                <p class="text-muted small mt-2 mb-0">Preview foto baru yang akan diupload</p>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.waste.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Update Data
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
        const currentPhoto = document.getElementById('currentPhoto');
        
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
                // Hide current photo when new photo is selected
                if (currentPhoto) {
                    currentPhoto.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            // Show current photo again if file input is cleared
            if (currentPhoto) {
                currentPhoto.style.display = 'block';
            }
        }
    });
</script>
@endpush
@endsection
