@extends('layouts.app')

@section('title', 'Forum Komunitas')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-chat-dots me-2"></i>Forum Komunitas
            </h2>
            <p class="text-muted mb-0">Diskusikan topik lingkungan bersama komunitas</p>
        </div>
        <button type="button" class="btn btn-success text-nowrap flex-shrink-0 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#createPostModal">
            <i class="bi bi-plus-circle me-1"></i>Buat Topik Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Forum Posts List -->
    @if($posts->count() > 0)
        <div class="row g-3">
            @foreach($posts as $post)
                <div class="col-12">
                    <div class="card shadow-sm" style="transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    @if($post->is_pinned)
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="bi bi-pin-angle-fill me-1"></i>Pinned
                                        </span>
                                    @endif
                                    @if($post->category)
                                        <span class="badge bg-info me-2">{{ $post->category }}</span>
                                    @endif
                                    <h5 class="card-title mb-1 fw-bold">
                                        <a href="#" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#postDetailModal{{ $post->id }}">
                                            {{ $post->title }}
                                        </a>
                                    </h5>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                                </small>
                            </div>
                            
                            <p class="card-text text-muted mb-3">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span>
                                        <i class="bi bi-person me-1"></i>
                                        Oleh: <strong>{{ $post->user->name }}</strong>
                                    </span>
                                    <span>•</span>
                                    <span>
                                        <i class="bi bi-chat-left-text me-1"></i>
                                        {{ $post->comments_count }} Balasan
                                    </span>
                                    <span>•</span>
                                    <span>
                                        <i class="bi bi-heart me-1"></i>
                                        {{ $post->likes_count }} Suka
                                    </span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <form action="{{ route('user.community.posts.like', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $post->isLikedBy($user->id) ? 'btn-danger' : 'btn-outline-danger' }}">
                                            <i class="bi bi-heart{{ $post->isLikedBy($user->id) ? '-fill' : '' }} me-1"></i>
                                            {{ $post->isLikedBy($user->id) ? 'Tidak Suka' : 'Suka' }}
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#postDetailModal{{ $post->id }}">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Detail Modal -->
                <div class="modal fade" id="postDetailModal{{ $post->id }}" tabindex="-1" aria-labelledby="postDetailModalLabel{{ $post->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="postDetailModalLabel{{ $post->id }}">
                                    @if($post->is_pinned)
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="bi bi-pin-angle-fill me-1"></i>Pinned
                                        </span>
                                    @endif
                                    {{ $post->title }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-3 text-muted small mb-2">
                                        <span>
                                            <i class="bi bi-person me-1"></i>
                                            Oleh: <strong>{{ $post->user->name }}</strong>
                                        </span>
                                        <span>•</span>
                                        <span>
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $post->created_at->format('d M Y, H:i') }}
                                        </span>
                                        @if($post->category)
                                            <span>•</span>
                                            <span class="badge bg-info">{{ $post->category }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                                
                                <hr>
                                
                                <div class="mb-3">
                                    <h6 class="mb-3">
                                        <i class="bi bi-chat-left-text me-2"></i>Komentar ({{ $post->comments_count }})
                                    </h6>
                                    
                                    @if($post->comments->count() > 0)
                                        <div class="comments-list">
                                            @foreach($post->comments->take(5) as $comment)
                                                <div class="card mb-2">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <strong class="small">{{ $comment->user->name }}</strong>
                                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <p class="mb-0 small">{{ $comment->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @if($post->comments->count() > 5)
                                                <p class="text-muted small text-center mt-2">
                                                    Dan {{ $post->comments->count() - 5 }} komentar lainnya...
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted small">Belum ada komentar. Jadilah yang pertama!</p>
                                    @endif
                                </div>
                                
                                <form action="{{ route('user.community.posts.comment', $post->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="content" class="form-control" rows="3" placeholder="Tulis komentar Anda..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-send me-1"></i>Kirim Komentar
                                    </button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('user.community.posts.like', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn {{ $post->isLikedBy($user->id) ? 'btn-danger' : 'btn-outline-danger' }}">
                                        <i class="bi bi-heart{{ $post->isLikedBy($user->id) ? '-fill' : '' }} me-1"></i>
                                        {{ $post->isLikedBy($user->id) ? 'Tidak Suka' : 'Suka' }} ({{ $post->likes_count }})
                                    </button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-dots display-4 d-block mb-3 text-muted opacity-50"></i>
                <p class="fs-5 mb-0 text-muted">Belum ada topik diskusi</p>
                <p class="text-muted small mt-2">Jadilah yang pertama membuat topik diskusi!</p>
                <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createPostModal">
                    <i class="bi bi-plus-circle me-1"></i>Buat Topik Baru
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Buat Topik Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.community.posts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Topik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Masukkan judul topik" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori (Opsional)</label>
                        <input type="text" class="form-control" id="category" name="category" 
                               value="{{ old('category') }}" 
                               placeholder="Contoh: Tips, Pertanyaan, Diskusi">
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="8" 
                                  placeholder="Tulis konten topik Anda di sini..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Gunakan format teks biasa. Line breaks akan dipertahankan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Buat Topik
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }
    
    .card-title a:hover {
        color: #28a745 !important;
    }
</style>
@endpush
@endsection
