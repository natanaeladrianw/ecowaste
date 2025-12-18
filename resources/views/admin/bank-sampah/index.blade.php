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
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                        <tbody>
                            @foreach($bankSampah as $bank)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($bank->photo)
                                                <img src="{{ asset('storage/' . $bank->photo) }}" 
                                                     alt="{{ $bank->name }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                            @endif
                                            <strong>{{ $bank->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($bank->address, 50) }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($bank->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $bank->phone }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bank->email)
                                            <i class="bi bi-envelope me-1"></i>{{ $bank->email }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bank->is_active)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center align-items-center">
                                            <a href="{{ route('admin.bank-sampah.edit', $bank->id) }}" 
                                               class="btn btn-sm btn-warning btn-action" 
                                               title="Edit Data">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteBankSampahModal{{ $bank->id }}"
                                                    title="Hapus Data">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                    </tr>
                            @endforeach
                </tbody>
            </table>
                </div>

                <!-- Modals for Desktop View -->
                @foreach($bankSampah as $bank)
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

                <!-- Mobile List View -->
                <div class="d-md-none bank-sampah-list">
                    @foreach($bankSampah as $bank)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-2">
                                    @if($bank->photo)
                                        <img src="{{ asset('storage/' . $bank->photo) }}" 
                                             alt="{{ $bank->name }}" 
                                             class="rounded me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-building fs-4"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $bank->name }}</h6>
                                        <small class="text-muted d-block mb-2">
                                            <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($bank->address, 40) }}
                                        </small>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @if($bank->phone)
                                                <small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $bank->phone }}
                                                </small>
                                            @endif
                                            @if($bank->email)
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>{{ Str::limit($bank->email, 20) }}
                                                </small>
                                            @endif
                                        </div>
                                        <div>
                                            @if($bank->is_active)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-1 justify-content-end mt-3">
                                    <a href="{{ route('admin.bank-sampah.edit', $bank->id) }}" 
                                       class="btn btn-sm btn-warning btn-action" 
                                       title="Edit Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-action" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteBankSampahModal{{ $bank->id }}"
                                            title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                    @if(request('search'))
                        Tidak ada bank sampah yang ditemukan untuk pencarian "{{ request('search') }}"
                    @else
                        Belum ada bank sampah
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

