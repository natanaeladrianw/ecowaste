@extends('layouts.admin')

@section('title', 'Daftar Users')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="mb-0">
            <i class="bi bi-people me-2"></i>Daftar Pengguna
        </h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i><span class="d-none d-md-inline">Tambah Pengguna</span><span class="d-md-none">Tambah</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card admin-card">
        <div class="card-header bg-white">
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex flex-column flex-md-row gap-2">
                <div class="flex-grow-1">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari user berdasarkan nama atau email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1 flex-md-grow-0">
                        <i class="bi bi-search"></i> <span class="d-none d-md-inline">Cari</span>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i> <span class="d-none d-md-inline">Reset</span>
                    </a>
                @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Total Sampah</th>
                                <th>Total Poin</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $totalWaste = $user->wastes->sum(function($waste) {
                                                return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
                                            });
                                        @endphp
                                        <span class="badge bg-info">{{ number_format($totalWaste, 2) }} kg</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-star-fill me-1"></i>{{ number_format($user->total_points ?? 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center align-items-center">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn btn-sm btn-info btn-action" 
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-primary btn-action" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#resetPasswordModal{{ $user->id }}"
                                                    title="Reset Password">
                                                <i class="bi bi-key"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteUserModal{{ $user->id }}"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modals for Desktop View -->
                @foreach($users as $user)
                    <!-- Reset Password Modal -->
                    <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
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
                    <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
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
                @endforeach

                <!-- Mobile List View -->
                <div class="d-md-none user-list">
                    @foreach($users as $user)
                        @php
                            $totalWaste = $user->wastes->sum(function($waste) {
                                return $waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount;
                            });
                        @endphp
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $user->name }}</h6>
                                        <small class="text-muted d-block mb-2">
                                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                        </small>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                            <span class="badge bg-info">{{ number_format($totalWaste, 2) }} kg</span>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i>{{ number_format($user->total_points ?? 0) }} Poin
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-1 justify-content-end mt-3">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="btn btn-sm btn-info btn-action" 
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-sm btn-primary btn-action" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                            class="btn btn-sm btn-warning btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#resetPasswordModal{{ $user->id }}"
                                                    title="Reset Password">
                                                <i class="bi bi-key"></i>
                                            </button>
                                            <button type="button" 
                                            class="btn btn-sm btn-danger btn-action" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteUserModal{{ $user->id }}"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                </div>
                            </div>
                        </div>
                                            
                                            <!-- Reset Password Modal -->
                                            <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
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
                                            <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
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
                            @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    @if(request('search'))
                        Tidak ada user yang ditemukan untuk pencarian "{{ request('search') }}"
                    @else
                        Belum ada data user
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
