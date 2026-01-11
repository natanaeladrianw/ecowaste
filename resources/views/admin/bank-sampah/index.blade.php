@extends('layouts.admin')

@section('title', 'Daftar Bank Sampah')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h2 class="mb-0">
            <i class="bi bi-geo-alt me-2"></i>Daftar Bank Sampah
        </h2>
        <a href="{{ route('admin.bank-sampah.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Bank Sampah</span><span class="d-md-none">Tambah</span>
            </a>
        </div>

    <!-- Search Bar -->
    <div class="card admin-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bank-sampah.index') }}" class="row g-3">
                <div class="col-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Cari bank sampah..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i><span class="d-none d-md-inline">Cari</span><span class="d-md-none">Cari</span>
                    </button>
                </div>
                @if(request('search'))
                    <div class="col-12">
                        <a href="{{ route('admin.bank-sampah.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Hapus Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
        </div>

    <!-- Table -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>Data Bank Sampah
            </h5>
        </div>
        <div class="card-body">
            @if($bankSampah->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($bankSampah as $bank)
                        <div class="col">
                            <div class="card h-100 shadow-sm hover-shadow transition-all">
                                <!-- Image Section -->
                                <div class="position-relative">
                                    @if($bank->photo)
                                        <div class="ratio ratio-16x9">
                                            <img src="{{ asset('storage/' . $bank->photo) }}" 
                                                 class="card-img-top object-fit-cover" 
                                                 alt="{{ $bank->name }}">
                                        </div>
                                    @else
                                        <div class="ratio ratio-16x9 bg-secondary d-flex align-items-center justify-content-center text-white">
                                            <i class="bi bi-building display-1 opacity-25"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        @if($bank->is_active)
                                            <span class="badge bg-success shadow-sm">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary shadow-sm">
                                                <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold mb-3 text-truncate" title="{{ $bank->name }}">
                                        {{ $bank->name }}
                                    </h5>
                                    
                                    <div class="mb-3 flex-grow-1">
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="bi bi-geo-alt text-danger me-2 mt-1"></i>
                                            <small class="text-muted">{{ Str::limit($bank->address, 80) }}</small>
                                        </div>
                                        
                                        @if($bank->phone)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-telephone text-primary me-2"></i>
                                                <small class="text-muted">{{ $bank->phone }}</small>
                                            </div>
                                        @endif

                                        @if($bank->email)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-envelope text-info me-2"></i>
                                                <small class="text-muted text-truncate">{{ $bank->email }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.bank-sampah.edit', $bank->id) }}" 
                                           class="btn btn-warning btn-sm flex-grow-1">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm flex-grow-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteBankSampahModal{{ $bank->id }}">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteBankSampahModal{{ $bank->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Hapus Bank Sampah
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus bank sampah <strong>{{ $bank->name }}</strong>?</p>
                                        <div class="alert alert-danger mb-0">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data bank sampah akan dihapus secara permanen.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-2"></i>Batal
                                        </button>
                                        <form action="{{ route('admin.bank-sampah.destroy', $bank->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash me-2"></i>Ya, Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted d-block mb-3 opacity-25"></i>
                    @if(request('search'))
                        <h5 class="text-muted">Tidak ada hasil ditemukan</h5>
                        <p class="text-muted mb-0">Tidak ada bank sampah yang cocok dengan pencarian "{{ request('search') }}"</p>
                    @else
                        <h5 class="text-muted">Belum ada data</h5>
                        <p class="text-muted mb-3">Belum ada bank sampah yang ditambahkan</p>
                        <a href="{{ route('admin.bank-sampah.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Bank Sampah
                        </a>
                    @endif
                </div>
            @endif
            
            <!-- Pagination -->
            @if($bankSampah->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bankSampah->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Bootstrap tooltips (only for elements with tooltip, not modal)
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<style>
    /* Custom styling untuk tombol aksi */
    .btn-sm {
        min-width: 80px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .btn-sm:active {
        transform: translateY(0);
    }
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }
    
    .btn-warning:hover {
        background-color: #ffb300;
        border-color: #ffb300;
        color: #000;
    }
    
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    /* Responsive: hide text on small screens */
    @media (max-width: 768px) {
        .btn-sm span {
            display: none;
        }
        .btn-sm {
            min-width: 40px;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endpush
@endsection

