@extends('layouts.admin')

@section('title', 'Artikel Edukasi')

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
            <i class="bi bi-newspaper me-2"></i>Daftar Artikel
        </h2>
        <a href="{{ route('admin.education.articles.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i><span class="d-none d-md-inline">Tambah Artikel</span><span class="d-md-none">Tambah</span>
        </a>
    </div>

    <!-- Articles List -->
    <div class="row">
        @forelse($articles as $article)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card admin-card h-100">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" 
                             class="card-img-top" 
                             alt="{{ $article->title }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <h5 class="card-title mb-0">{{ $article->title }}</h5>
                            <div>
                                @if($article->is_published)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Published
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Draft
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($article->category)
                            <span class="badge bg-info mb-2">{{ $article->category }}</span>
                        @endif
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($article->excerpt ?? strip_tags($article->content), 100) }}
                        </p>
                        <div class="mt-auto">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>{{ $article->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.education.articles.edit', $article->id) }}" 
                               class="btn btn-sm btn-warning d-flex align-items-center gap-1 flex-grow-1 justify-content-center">
                                <i class="bi bi-pencil-fill"></i>
                                <span>Edit</span>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger d-flex align-items-center gap-1 flex-grow-1 justify-content-center"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteArticleModal{{ $article->id }}">
                                <i class="bi bi-trash-fill"></i>
                                <span>Hapus</span>
                            </button>
                            
                            <!-- Delete Article Modal -->
                            <div class="modal fade" id="deleteArticleModal{{ $article->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle me-2"></i>Hapus Artikel
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus artikel <strong>{{ $article->title }}</strong>?</p>
                                            <div class="alert alert-danger mb-0">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Data artikel akan dihapus secara permanen.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>Batal
                                            </button>
                                            <form action="{{ route('admin.education.articles.destroy', $article->id) }}" method="POST" class="d-inline">
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
                        <h5 class="text-muted">Belum ada artikel</h5>
                        <p class="text-muted">Mulai dengan menambahkan artikel pertama Anda</p>
                        <a href="{{ route('admin.education.articles.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Artikel
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection

