@extends('layouts.app')

@section('title', 'Tips Pengelolaan Sampah')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="mb-0">
            <i class="bi bi-lightbulb me-2"></i>Tips Pengelolaan Sampah
        </h2>
        <p class="text-muted mb-0">Pelajari tips dan trik untuk mengelola sampah dengan baik</p>
    </div>

    @if($tips->count() > 0)
        <div class="row g-4">
            @foreach($tips as $tip)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm" style="transition: all 0.3s ease;">
                        @if($tip->is_featured)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </span>
                            </div>
                        @endif
                        
                        @if($tip->image)
                            <img src="{{ asset('storage/' . $tip->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $tip->title }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-lightbulb display-4 text-muted opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title mb-3 fw-bold">{{ $tip->title }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($tip->content ?? $tip->description, 120) }}
                            </p>
                            
                            @if($tip->category)
                                <span class="badge bg-primary mb-2">
                                    {{ $tip->category }}
                                </span>
                            @endif
                            
                            @if($tip->tags)
                                <div class="mb-2">
                                    @foreach(explode(',', $tip->tags) as $tag)
                                        <span class="badge bg-secondary me-1">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $tip->created_at->format('d M Y') }}
                                </small>
                                @if($tip->content || $tip->description)
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#tipModal{{ $tip->id }}">
                                        <i class="bi bi-eye me-1"></i>Baca Selengkapnya
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal for Full Content -->
                    @if($tip->content || $tip->description)
                    <div class="modal fade" id="tipModal{{ $tip->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-lightbulb me-2"></i>{{ $tip->title }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @if($tip->image)
                                        <img src="{{ asset('storage/' . $tip->image) }}" 
                                             class="img-fluid rounded mb-3" 
                                             alt="{{ $tip->title }}">
                                    @endif
                                    <div class="content">
                                        {!! nl2br(e($tip->content ?? $tip->description)) !!}
                                    </div>
                                    @if($tip->category || $tip->tags)
                                        <div class="mt-3 pt-3 border-top">
                                            @if($tip->category)
                                                <span class="badge bg-primary me-2">
                                                    {{ $tip->category }}
                                                </span>
                                            @endif
                                            @if($tip->tags)
                                                @foreach(explode(',', $tip->tags) as $tag)
                                                    <span class="badge bg-secondary me-1">
                                                        {{ trim($tag) }}
                                                    </span>
                                                @endforeach
                                            @endif
                    </div>
                                    @endif
                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i>Tutup
                                    </button>
                    </div>
                </div>
            </div>
        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($tips->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $tips->links() }}
            </div>
        @endif
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                <p class="fs-5 mb-0 text-muted">Belum ada tips tersedia</p>
    </div>
</div>
    @endif
</div>

@push('styles')
<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .card-img-top {
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px);
    }
</style>
@endpush
@endsection
