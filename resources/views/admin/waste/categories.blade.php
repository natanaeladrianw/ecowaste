@extends('layouts.admin')

@section('title', 'Kategori Sampah')

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
            <i class="bi bi-tags me-2"></i>Daftar Kategori
        </h2>
        <a href="{{ route('admin.waste.categories.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Kategori</span><span class="d-md-none">Tambah</span>
        </a>
        </div>

    <!-- Categories Table -->
    <div class="card admin-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>Data Kategori Sampah
            </h5>
        </div>
        <div class="card-body">
            @if($categories->count() > 0)
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tipe Sampah</th>
                                <th>Poin per kg</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($category->color)
                                                <div class="rounded-circle me-2" 
                                                     style="width: 20px; height: 20px; background-color: {{ $category->color }};"></div>
                                            @endif
                                            @if($category->icon)
                                                <i class="{{ $category->icon }} me-2"></i>
                                            @endif
                                            <strong>{{ $category->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($category->waste_type)
                                            @php
                                                $wasteType = \App\Models\WasteType::where('slug', $category->waste_type)->first();
                                                if ($wasteType) {
                                                    $color = $wasteType->color ?? '#6c757d';
                                                    $label = $wasteType->name;
                                                } else {
                                                    $typeColors = [
                                                        'organik' => 'success',
                                                        'anorganik' => 'primary',
                                                        'b3' => 'danger',
                                                        'recycle' => 'info'
                                                    ];
                                                    $typeLabels = [
                                                        'organik' => 'Organik',
                                                        'anorganik' => 'Anorganik',
                                                        'b3' => 'B3',
                                                        'recycle' => 'Recycle'
                                                    ];
                                                    $color = $typeColors[$category->waste_type] ?? 'secondary';
                                                    $label = $typeLabels[$category->waste_type] ?? ucfirst($category->waste_type);
                                                }
                                            @endphp
                                            <span class="badge" style="background-color: {{ is_string($color) && strpos($color, '#') === false ? 'var(--bs-' . $color . ')' : $color }}; color: white;">
                                                {{ $label }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-star-fill me-1"></i>{{ number_format($category->points_per_kg) }} poin/kg
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($category->description, 50) ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($category->is_active)
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
                                            <a href="{{ route('admin.waste.categories.create') }}?edit={{ $category->id }}" 
                                               class="btn btn-sm btn-warning btn-action"
                                               title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-action"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteCategoryModal{{ $category->id }}"
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
                @foreach($categories as $category)
                    <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Hapus Kategori
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $category->name }}</strong>?</p>
                                    <div class="alert alert-danger mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data kategori akan dihapus secara permanen.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-2"></i>Batal
                                    </button>
                                    <form action="{{ route('admin.waste.categories.destroy', $category->id) }}" method="POST" class="d-inline">
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
                <div class="d-md-none categories-list">
                    @foreach($categories as $category)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="d-flex align-items-center me-3">
                                        @if($category->color)
                                            <div class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; background-color: {{ $category->color }}; display: flex; align-items: center; justify-content: center;">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }} text-white"></i>
                                                @endif
                                            </div>
                                        @elseif($category->icon)
                                            <i class="{{ $category->icon }} fs-3 me-2"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $category->name }}</h6>
                                        @if($category->description)
                                            <small class="text-muted d-block mb-2">
                                                {{ Str::limit($category->description, 60) }}
                                            </small>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @if($category->waste_type)
                                                @php
                                                    $wasteType = \App\Models\WasteType::where('slug', $category->waste_type)->first();
                                                    if ($wasteType) {
                                                        $color = $wasteType->color ?? '#6c757d';
                                                        $label = $wasteType->name;
                                                    } else {
                                                        $typeColors = [
                                                            'organik' => 'success',
                                                            'anorganik' => 'primary',
                                                            'b3' => 'danger',
                                                            'recycle' => 'info'
                                                        ];
                                                        $typeLabels = [
                                                            'organik' => 'Organik',
                                                            'anorganik' => 'Anorganik',
                                                            'b3' => 'B3',
                                                            'recycle' => 'Recycle'
                                                        ];
                                                        $color = $typeColors[$category->waste_type] ?? 'secondary';
                                                        $label = $typeLabels[$category->waste_type] ?? ucfirst($category->waste_type);
                                                    }
                                                @endphp
                                                <span class="badge" style="background-color: {{ is_string($color) && strpos($color, '#') === false ? 'var(--bs-' . $color . ')' : $color }}; color: white;">
                                                    {{ $label }}
                                                </span>
                                            @endif
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i>{{ number_format($category->points_per_kg) }} poin/kg
                                            </span>
                                        </div>
                                        <div>
                                            @if($category->is_active)
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
                                    <a href="{{ route('admin.waste.categories.create') }}?edit={{ $category->id }}" 
                                       class="btn btn-sm btn-warning btn-action"
                                       title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-action"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCategoryModal{{ $category->id }}"
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
                    Belum ada kategori
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

