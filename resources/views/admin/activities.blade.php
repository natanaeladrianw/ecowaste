@extends('layouts.admin')

@section('title', 'Aktivitas Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="bi bi-activity me-2"></i>Aktivitas Pengguna
        </h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Semua Aktivitas</h5>
        </div>
        <div class="card-body">
            @if($activities->count() > 0)
                            @foreach($activities as $activity)
                    @php
                        $activityLabels = [
                            'waste_input' => 'Input Sampah',
                            'waste_approved' => 'Sampah Disetujui',
                            'waste_rejected' => 'Sampah Ditolak',
                            'waste_updated' => 'Update Sampah',
                            'waste_deleted' => 'Hapus Sampah',
                            'point_earned' => 'Poin Diperoleh',
                            'point_spent' => 'Poin Digunakan',
                            'reward_claimed' => 'Reward Diklaim',
                            'profile_updated' => 'Update Profil',
                            'user_registered' => 'Registrasi User',
                            'login' => 'Login',
                            'logout' => 'Logout',
                        ];
                        $label = $activityLabels[$activity->activity_type] ?? ucfirst(str_replace('_', ' ', $activity->activity_type));
                    @endphp
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-activity"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span class="badge bg-primary me-2">{{ $label }}</span>
                                    <span class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>{{ $activity->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-1">
                                        @if($activity->user)
                                            <strong>{{ $activity->user->name }}</strong>
                                    <small class="text-muted d-block">{{ $activity->user->email }}</small>
                                        @else
                                            <span class="text-muted">User tidak ditemukan</span>
                                        @endif
                            </div>
                            @if($activity->description)
                                <div class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>{{ Str::limit($activity->description, 80) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 ms-2">
                                        @if($activity->metadata)
                                <button class="btn btn-sm btn-outline-info" type="button" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal{{ $activity->id }}">
                                                <i class="bi bi-eye"></i> Lihat
                                            </button>
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Aktivitas</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                @php
                                                    $metadata = $activity->metadata;
                                                @endphp
                                                
                                                @if(isset($metadata['waste_id']))
                                                    @php
                                                        $waste = \App\Models\Waste::with(['category', 'bankSampah', 'user'])->find($metadata['waste_id']);
                                                    @endphp
                                                    
                                                    @if($waste)
                                                        <div class="mb-3">
                                                            <h6 class="text-muted mb-3">
                                                                <i class="bi bi-info-circle me-2"></i>Informasi Sampah
                                                            </h6>
                                                            <div class="list-group list-group-flush">
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">ID Sampah</small>
                                                                    <strong>#{{ $waste->id }}</strong>
                                                                </div>
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Jenis Sampah</small>
                                                                    <strong>{{ $waste->type }}</strong>
                                                                </div>
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Kategori</small>
                                                                    @if($waste->category)
                                                                        <span class="badge" style="background-color: {{ $waste->category->color ?? '#007bff' }};">
                                                                            {{ $waste->category->name }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </div>
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Jumlah</small>
                                                                    <strong>{{ number_format($waste->amount, 2) }} {{ $waste->unit }}</strong>
                                                                </div>
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Tanggal</small>
                                                                    <strong>{{ $waste->date->format('d M Y') }}</strong>
                                                                </div>
                                                                @if($waste->time)
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Waktu</small>
                                                                    <strong>{{ $waste->time }}</strong>
                                                                </div>
                                                                @endif
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Status</small>
                                                                    @if($waste->status === 'approved')
                                                                        <span class="badge bg-success">Disetujui</span>
                                                                    @elseif($waste->status === 'rejected')
                                                                        <span class="badge bg-danger">Ditolak</span>
                                                                    @else
                                                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                                                    @endif
                                                                </div>
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Poin</small>
                                                                    <strong class="text-success">{{ number_format($waste->points_earned ?? 0) }} poin</strong>
                                                                </div>
                                                                @if($waste->bankSampah)
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">Lokasi Bank Sampah</small>
                                                                    <strong>{{ $waste->bankSampah->name }}</strong>
                                                                </div>
                                                                @endif
                                                                @if($waste->description)
                                                                <div class="list-group-item px-0 py-2">
                                                                    <small class="text-muted d-block mb-1">Deskripsi</small>
                                                                    <div>{{ $waste->description }}</div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                                            Data sampah dengan ID {{ $metadata['waste_id'] }} tidak ditemukan atau sudah dihapus.
                                                        </div>
                                                        <div class="mt-3">
                                                            <h6 class="text-muted mb-2">Metadata Raw</h6>
                                                            <pre class="bg-light p-3 rounded small">{{ json_encode($metadata, JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    @endif
                                                @elseif(!empty($metadata))
                                                    <div class="mb-3">
                                                        <h6 class="text-muted mb-3">
                                                            <i class="bi bi-info-circle me-2"></i>Informasi Detail
                                                        </h6>
                                                        <div class="list-group list-group-flush">
                                                            @foreach($metadata as $key => $value)
                                                                <div class="list-group-item px-0 py-2 border-bottom">
                                                                    <small class="text-muted d-block mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                                                                    <div>{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        Tidak ada informasi detail tambahan untuk aktivitas ini.
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                <span class="text-muted small">-</span>
                                        @endif
                        </div>
                    </div>
                            @endforeach

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Belum ada aktivitas yang tercatat.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
