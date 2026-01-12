@extends('layouts.admin')

@section('title', 'Rewards')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3"
        style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3"
        style="z-index: 9999; min-width: 300px;" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <h2 class="mb-0">
                <i class="bi bi-gift me-2"></i>Daftar Rewards
            </h2>
            <a href="{{ route('admin.rewards.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Reward</span><span
                    class="d-md-none">Tambah</span>
            </a>
        </div>

        <!-- Rewards Table -->
        <div class="card admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Data Rewards
                </h5>
            </div>
            <div class="card-body">
                @if($rewards->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th>Poin Dibutuhkan</th>
                                    <th>Tipe</th>
                                    <th>Nilai</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rewards as $reward)
                                    <tr>
                                        <td>
                                            @if($reward->image)
                                                <img src="{{ Str::startsWith($reward->image, 'uploads/') ? asset($reward->image) : asset('storage/' . $reward->image) }}"
                                                    alt="{{ $reward->name }}" class="img-thumbnail"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="bi bi-gift text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $reward->name }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($reward->description, 50) ?? '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i>{{ number_format($reward->points_required) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $reward->type ?? '-' }}</span>
                                        </td>
                                        <td>
                                            {{ $reward->value ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $reward->stock }}</span>
                                        </td>
                                        <td>
                                            @if($reward->is_active)
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
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('admin.rewards.edit', $reward->id) }}"
                                                    class="btn btn-sm btn-primary btn-action" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.rewards.destroy', $reward->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus reward ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile List View -->
                    <div class="d-md-none rewards-list">
                        @foreach($rewards as $reward)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        @if($reward->image)
                                            <img src="{{ Str::startsWith($reward->image, 'uploads/') ? asset($reward->image) : asset('storage/' . $reward->image) }}"
                                                alt="{{ $reward->name }}" class="img-thumbnail"
                                                style="width: 80px; height: 80px; object-fit: cover; flex-shrink: 0;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                style="width: 80px; height: 80px; flex-shrink: 0;">
                                                <i class="bi bi-gift text-muted fs-4"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $reward->name }}</h6>
                                            <p class="text-muted small mb-2">
                                                {{ Str::limit($reward->description, 60) ?? '-' }}
                                            </p>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i
                                                        class="bi bi-star-fill me-1"></i>{{ number_format($reward->points_required) }}
                                                    Poin
                                                </span>
                                                @if($reward->type)
                                                    <span class="badge bg-info">{{ $reward->type }}</span>
                                                @endif
                                                <span class="badge bg-secondary">Stok: {{ $reward->stock }}</span>
                                                @if($reward->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                                @endif
                                            </div>
                                            @if($reward->value)
                                                <p class="small mb-2">
                                                    <i class="bi bi-currency-exchange me-1"></i>Nilai: {{ $reward->value }}
                                                </p>
                                            @endif
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.rewards.edit', $reward->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('admin.rewards.destroy', $reward->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus reward ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                        <p class="text-muted mb-0">Belum ada reward</p>
                        <a href="{{ route('admin.rewards.create') }}" class="btn btn-success mt-3">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Reward Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection