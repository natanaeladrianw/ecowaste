@extends('layouts.app')

@section('title', 'Tukar Poin dengan Hadiah')

@section('content')
    <div class="container-fluid">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('user.points.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-4">
            <h2 class="mb-0">
                <i class="bi bi-gift me-2"></i>Tukar Poin dengan Hadiah
            </h2>
            <p class="text-muted mb-0">Tukar poin Anda dengan reward menarik</p>
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

        <!-- Current Points Info -->
        <div class="card mb-4" style="background: linear-gradient(135deg, #007bff, #0056b3);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1" style="opacity: 0.9;">
                            <i class="bi bi-star-fill me-2"></i>Poin Anda
                        </h6>
                        <h3 class="mb-0" style="font-size: 2rem; font-weight: 700;">
                            {{ number_format($user->total_points ?? 0) }} Poin
                        </h3>
                    </div>
                    <i class="bi bi-award display-4 opacity-25"></i>
                </div>
            </div>
        </div>

        <!-- Rewards List -->
        @if($rewards->count() > 0)
            <div class="rewards-list">
                @foreach($rewards as $reward)
                    <div class="card mb-3 shadow-sm" style="transition: all 0.3s ease;">
                        <!-- Header Section (Clickable) -->
                        <div class="card-body" data-bs-toggle="collapse" data-bs-target="#reward-detail-{{ $reward->id }}"
                            aria-expanded="false" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-star-fill me-1"></i>
                                            {{ number_format($reward->points_required) }} Poin
                                        </span>
                                        @if($reward->type)
                                            <span class="badge bg-info">{{ $reward->type }}</span>
                                        @endif
                                        @if($reward->stock > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-box-seam me-1"></i>
                                                Stok: {{ $reward->stock }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">Stok Habis</span>
                                        @endif
                                    </div>
                                    <h6 class="mb-1 fw-bold">{{ $reward->name }}</h6>
                                    <div class="d-flex flex-wrap gap-3 text-muted small">
                                        @if($reward->value)
                                            <span><i class="bi bi-currency-exchange me-1"></i>Nilai: {{ $reward->value }}</span>
                                        @endif
                                        <span><i class="bi bi-gift me-1"></i>Reward</span>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-down ms-2 text-muted" style="font-size: 1.2rem;"></i>
                            </div>
                        </div>

                        <!-- Detail Section (Collapsible) -->
                        <div class="collapse" id="reward-detail-{{ $reward->id }}">
                            <div class="card-body border-top bg-light">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3"><i class="bi bi-info-circle me-1"></i>Informasi Reward</h6>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td class="text-muted" style="width: 40%;">Nama:</td>
                                                <td><strong>{{ $reward->name }}</strong></td>
                                            </tr>
                                            @if($reward->type)
                                                <tr>
                                                    <td class="text-muted">Tipe:</td>
                                                    <td><span class="badge bg-info">{{ $reward->type }}</span></td>
                                                </tr>
                                            @endif
                                            @if($reward->value)
                                                <tr>
                                                    <td class="text-muted">Nilai:</td>
                                                    <td><strong>{{ $reward->value }}</strong></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="text-muted">Poin Dibutuhkan:</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-star-fill me-1"></i>
                                                        {{ number_format($reward->points_required) }} Poin
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Stok:</td>
                                                <td>
                                                    @if($reward->stock > 0)
                                                        <span class="badge bg-success">{{ $reward->stock }} tersedia</span>
                                                    @else
                                                        <span class="badge bg-danger">Habis</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($reward->description)
                                                <tr>
                                                    <td class="text-muted">Deskripsi:</td>
                                                    <td>{{ $reward->description }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3"><i class="bi bi-image me-1"></i>Gambar Reward</h6>
                                        @if($reward->image)
                                            <img src="{{ \Illuminate\Support\Str::startsWith($reward->image, 'uploads/') ? asset($reward->image) : asset('storage/' . $reward->image) }}"
                                                alt="{{ $reward->name }}" class="img-fluid rounded shadow-sm"
                                                style="max-height: 250px; width: 100%; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                style="height: 200px; border: 2px dashed #dee2e6;">
                                                <div class="text-center">
                                                    <i class="bi bi-gift display-4 d-block mb-2 text-muted opacity-50"></i>
                                                    <p class="text-muted small mb-0">Tidak ada gambar</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <form action="{{ route('user.points.rewards.claim', $reward->id) }}" method="POST"
                                        class="d-inline w-100" id="claimForm{{ $reward->id }}">
                                        @csrf
                                        @if($user->total_points >= $reward->points_required && $reward->stock > 0)
                                            <button type="button" class="btn btn-success w-100"
                                                onclick="confirmClaimReward({{ $reward->id }}, {{ $reward->points_required }}, '{{ $reward->name }}')">
                                                <i class="bi bi-gift me-1"></i>Tukar {{ number_format($reward->points_required) }} Poin
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary w-100" disabled>
                                                @if($user->total_points < $reward->points_required)
                                                    <i class="bi bi-x-circle me-1"></i>Poin Tidak Cukup (Butuh
                                                    {{ number_format($reward->points_required) }} Poin)
                                                @elseif($reward->stock <= 0)
                                                    <i class="bi bi-x-circle me-1"></i>Stok Habis
                                                @else
                                                    <i class="bi bi-x-circle me-1"></i>Tidak Tersedia
                                                @endif
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-4 d-block mb-3 text-muted opacity-50"></i>
                    <p class="fs-5 mb-0 text-muted">Belum ada reward tersedia</p>
                    <p class="text-muted small mt-2">Reward akan ditambahkan oleh admin</p>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .rewards-list .card {
                transition: all 0.3s ease;
            }

            .rewards-list .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }

            .rewards-list .card-body[data-bs-toggle="collapse"]:hover {
                background-color: #f8f9fa;
            }

            .rewards-list .bi-chevron-down {
                transition: transform 0.3s ease;
            }

            .rewards-list .card-body[data-bs-toggle="collapse"][aria-expanded="true"] .bi-chevron-down {
                transform: rotate(180deg);
            }

            /* SweetAlert2 Button Spacing */
            .swal2-actions {
                gap: 15px !important;
                margin-top: 20px !important;
            }

            .swal2-actions button {
                margin: 0 !important;
                padding: 10px 20px !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function confirmClaimReward(rewardId, pointsRequired, rewardName) {
                Swal.fire({
                    title: 'Konfirmasi Tukar Reward',
                    html: `
                        <div class="text-start">
                            <p>Apakah Anda yakin ingin menukar <strong>${pointsRequired.toLocaleString('id-ID')} poin</strong> untuk reward ini?</p>
                            <p class="mb-0"><strong>Reward:</strong> ${rewardName}</p>
        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-check-circle me-1"></i>Ya, Tukar',
                    cancelButtonText: '<i class="bi bi-x-circle me-1"></i>Batal',
                    reverseButtons: true,
                    focusConfirm: false,
                    customClass: {
                        popup: 'swal2-popup-custom',
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-secondary',
                        actions: 'swal2-actions-custom'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form
                        document.getElementById('claimForm' + rewardId).submit();
                    }
                });
            }
        </script>
    @endpush
@endsection