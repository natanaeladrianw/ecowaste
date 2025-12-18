@extends('layouts.admin')

@section('title', 'Tips Edukasi')

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
            <i class="bi bi-lightbulb me-2"></i>Daftar Tips
        </h2>
        <a href="{{ route('admin.education.tips.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Tips</span><span class="d-md-none">Tambah</span>
        </a>
        </div>

    <!-- Tips List -->
    <div class="row">
        @forelse($tips as $tip)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card admin-card h-100">
                    @if($tip->image)
                        <img src="{{ asset('storage/' . $tip->image) }}" 
                             class="card-img-top" 
                             alt="{{ $tip->title }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <h5 class="card-title mb-0">{{ $tip->title }}</h5>
                            <div>
                                @if($tip->is_featured)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill me-1"></i>Featured
                                    </span>
                                @endif
                                @if($tip->is_active)
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
                        @if($tip->category)
                            <span class="badge bg-info mb-2">{{ $tip->category }}</span>
                        @endif
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit(strip_tags($tip->content), 100) }}
                        </p>
                        <div class="mt-auto">
                            <small class="text-muted">
                                <i class="bi bi-eye me-1"></i>{{ $tip->views_count ?? 0 }} views
                                <span class="ms-2">
                                    <i class="bi bi-calendar me-1"></i>{{ $tip->created_at->format('d/m/Y') }}
                                </span>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.education.tips.edit', $tip->id) }}" 
                               class="btn btn-sm btn-warning d-flex align-items-center gap-1 flex-grow-1 justify-content-center">
                                <i class="bi bi-pencil-fill"></i>
                                <span>Edit</span>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger d-flex align-items-center gap-1 flex-grow-1 justify-content-center"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteTipModal{{ $tip->id }}">
                                <i class="bi bi-trash-fill"></i>
                                <span>Hapus</span>
                            </button>
                            
                            <!-- Delete Tip Modal -->
                            <div class="modal fade" id="deleteTipModal{{ $tip->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle me-2"></i>Hapus Tips
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus tips <strong>{{ $tip->title }}</strong>?</p>
                                            <div class="alert alert-danger mb-0">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Data tips akan dihapus secara permanen.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>Batal
                    </button>
                                            <form action="{{ route('admin.education.tips.destroy', $tip->id) }}" method="POST" class="d-inline">
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
        @empty
            <div class="col-12">
                <div class="card admin-card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox display-6 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Belum ada tips</h5>
                        <p class="text-muted">Mulai dengan menambahkan tips pertama Anda</p>
                        <a href="{{ route('admin.education.tips.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Tips
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($tips->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $tips->links() }}
        </div>
    @endif
</div>
@endsection

