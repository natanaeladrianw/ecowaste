@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="mb-0">
            <i class="bi bi-person-circle me-2"></i>Informasi User
        </h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi User -->
        <div class="col-md-6 mb-4">
            <div class="card admin-card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi User
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Nama:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->name ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->email ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Role:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $user->role === 'admin' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Tanggal Daftar:</strong>
                        </div>
                        <div class="col-sm-8">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Total Poin:</strong>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill me-1"></i>{{ number_format($user->total_points ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="col-md-6 mb-4">
            <div class="card admin-card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-trash display-6 text-success mb-2"></i>
                                <h4 class="mb-0">{{ number_format($totalWaste, 2) }} kg</h4>
                                <small class="text-muted">Total Sampah</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-receipt display-6 text-primary mb-2"></i>
                                <h4 class="mb-0">{{ number_format($totalTransactions) }}</h4>
                                <small class="text-muted">Total Transaksi</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-star-fill display-6 text-warning mb-2"></i>
                                <h4 class="mb-0">{{ number_format($totalPoints) }}</h4>
                                <small class="text-muted">Total Poin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                           class="btn btn-primary btn-action-card">
                            <i class="bi bi-pencil-fill"></i>
                            <span class="small">Edit User</span>
                        </a>
                        <button type="button" 
                                class="btn btn-warning btn-action-card"
                                data-bs-toggle="modal" 
                                data-bs-target="#resetPasswordModal">
                            <i class="bi bi-key-fill"></i>
                            <span class="small">Reset Password</span>
                        </button>
                        <button type="button" 
                                class="btn btn-danger btn-action-card"
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteUserModal">
                            <i class="bi bi-trash-fill"></i>
                            <span class="small">Hapus User</span>
                        </button>
                        
                        <!-- Reset Password Modal -->
                        <div class="modal fade" id="resetPasswordModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title">
                                            <i class="bi bi-key me-2"></i>Reset Password
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin mereset password user <strong>{{ $user->name }}</strong>?</p>
                                        <p class="text-muted mb-0">
                                            <small>Password baru akan dibuat secara otomatis dan ditampilkan setelah reset.</small>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-2"></i>Batal
                                        </button>
                                        <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-check-circle me-2"></i>Ya, Reset Password
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteUserModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Hapus User
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
                                        <div class="alert alert-danger mb-0">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data user akan dihapus secara permanen.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-2"></i>Batal
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
