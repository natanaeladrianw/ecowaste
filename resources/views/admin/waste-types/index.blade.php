@extends('layouts.admin')

@section('title', 'Tipe Sampah')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h2 class="mb-0">
            <i class="bi bi-tags me-2"></i>Daftar Tipe Sampah
        </h2>
        <a href="{{ route('admin.waste-types.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Tipe Sampah</span><span class="d-md-none">Tambah</span>
        </a>
    </div>

    <!-- Waste Types Table -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>Data Tipe Sampah
            </h5>
        </div>
        <div class="card-body">
            @if($wasteTypes->count() > 0)
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Icon</th>
                                <th>Warna</th>
                                <th>Urutan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wasteTypes as $wasteType)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($wasteType->color)
                                                <div class="rounded-circle me-2" 
                                                     style="width: 20px; height: 20px; background-color: {{ $wasteType->color }};"></div>
                                            @endif
                                            <strong>{{ $wasteType->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($wasteType->description, 50) ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($wasteType->icon)
                                            <i class="{{ $wasteType->icon }} fs-5"></i>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($wasteType->color)
                                            <div class="rounded" 
                                                 style="width: 30px; height: 20px; background-color: {{ $wasteType->color }}; border: 1px solid #ddd;"></div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $wasteType->sort_order }}</span>
                                    </td>
                                    <td>
                                        @if($wasteType->is_active)
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
                                            <a href="{{ route('admin.waste-types.create') }}?edit={{ $wasteType->id }}" 
                                               class="btn btn-sm btn-warning btn-action"
                                               title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-action"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteWasteTypeModal{{ $wasteType->id }}"
                                                    title="Hapus">
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
                @foreach($wasteTypes as $wasteType)
                    <div class="modal fade" id="deleteWasteTypeModal{{ $wasteType->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Hapus Tipe Sampah
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus tipe sampah <strong>{{ $wasteType->name }}</strong>?</p>
                                    <div class="alert alert-danger mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data tipe sampah akan dihapus secara permanen.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-2"></i>Batal
                                    </button>
                                    <form action="{{ route('admin.waste-types.destroy', $wasteType->id) }}" method="POST" class="d-inline">
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
                <div class="d-md-none waste-types-list">
                    @foreach($wasteTypes as $wasteType)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="d-flex align-items-center me-3">
                                        @if($wasteType->color)
                                            <div class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; background-color: {{ $wasteType->color }}; display: flex; align-items: center; justify-content: center;">
                                                @if($wasteType->icon)
                                                    <i class="{{ $wasteType->icon }} text-white"></i>
                                                @endif
                                            </div>
                                        @elseif($wasteType->icon)
                                            <i class="{{ $wasteType->icon }} fs-3 me-2"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $wasteType->name }}</h6>
                                        @if($wasteType->description)
                                            <small class="text-muted d-block mb-2">
                                                {{ Str::limit($wasteType->description, 60) }}
                                            </small>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-secondary">Urutan: {{ $wasteType->sort_order }}</span>
                                            @if($wasteType->is_active)
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
                                    <a href="{{ route('admin.waste-types.create') }}?edit={{ $wasteType->id }}" 
                                       class="btn btn-sm btn-warning btn-action"
                                       title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-action"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteWasteTypeModal{{ $wasteType->id }}"
                                            title="Hapus">
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
                    Belum ada tipe sampah
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

