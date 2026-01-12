@extends('layouts.admin')

@section('title', 'Laporan Sampah')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <h2 class="mb-0">
                <i class="bi bi-file-bar-graph me-2"></i>Laporan Sampah
            </h2>
        </div>

        <!-- Filter Section -->
        <div class="card admin-card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-funnel me-2"></i>Filter Laporan
                </h5>

                <!-- Filter Buttons -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Periode Cepat:</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @php
                            $params = [];
                            if ($bankSampahId) {
                                $params['bank_sampah_id'] = $bankSampahId;
                            }
                        @endphp
                        <a href="{{ route('admin.waste.reports', array_merge($params, ['period' => 'daily'])) }}"
                            class="btn {{ $period === 'daily' ? 'btn-success' : 'btn-outline-secondary' }}">
                            <i class="bi bi-calendar-day me-1"></i>Harian
                        </a>
                        <a href="{{ route('admin.waste.reports', array_merge($params, ['period' => 'weekly'])) }}"
                            class="btn {{ $period === 'weekly' ? 'btn-success' : 'btn-outline-secondary' }}">
                            <i class="bi bi-calendar-week me-1"></i>Mingguan
                        </a>
                        <a href="{{ route('admin.waste.reports', array_merge($params, ['period' => 'monthly'])) }}"
                            class="btn {{ $period === 'monthly' ? 'btn-success' : 'btn-outline-secondary' }}">
                            <i class="bi bi-calendar-month me-1"></i>Bulanan
                        </a>
                    </div>
                </div>

                <hr>

                <!-- Filter Bank Sampah -->
                <div class="mb-3">
                    <label for="bank_sampah_id" class="form-label fw-bold">
                        <i class="bi bi-geo-alt me-1"></i>Lokasi Bank Sampah:
                    </label>
                    <select class="form-select" id="bank_sampah_id" name="bank_sampah_id">
                        <option value="">Semua Bank Sampah</option>
                        @foreach($bankSampahList as $bank)
                            <option value="{{ $bank->id }}" {{ $bankSampahId == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }} - {{ $bank->location_name ?? $bank->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @php
                    $categories = \App\Models\WasteCategory::orderBy('name')->get();
                @endphp

                <hr>

                <!-- Date Range Picker -->
                <div class="row">
                    <div class="col-12">
                        <label class="form-label fw-bold">Pilih Rentang Tanggal:</label>
                    </div>
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $startDate ?? '' }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $endDate ?? '' }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary w-100" id="filterDateBtn">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.waste.reports') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row mb-4">
            <div class="col-12 col-md-4 mb-3 mb-md-0">
                <div class="card admin-card border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <div class="card-body text-white position-relative" style="min-height: 120px;">
                        <div class="position-absolute top-0 end-0 p-3">
                            <i class="bi bi-trash-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                        <div class="position-relative" style="z-index: 1;">
                            <h6 class="card-subtitle mb-2 text-white"
                                style="font-size: 0.875rem; font-weight: 500; opacity: 0.9;">
                                Total Sampah
                            </h6>
                            <h2 class="card-title mb-0 text-white" style="font-size: 2rem; font-weight: 700;">
                                {{ number_format($totalWaste, 2) }} kg
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-3 mb-md-0">
                <div class="card admin-card border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #007bff, #0056b3);">
                    <div class="card-body text-white position-relative" style="min-height: 120px;">
                        <div class="position-absolute top-0 end-0 p-3">
                            <i class="bi bi-receipt-cutoff" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                        <div class="position-relative" style="z-index: 1;">
                            <h6 class="card-subtitle mb-2 text-white"
                                style="font-size: 0.875rem; font-weight: 500; opacity: 0.9;">
                                Total Transaksi
                            </h6>
                            <h2 class="card-title mb-0 text-white" style="font-size: 2rem; font-weight: 700;">
                                {{ number_format($totalTransactions) }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card admin-card border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                    <div class="card-body text-white position-relative" style="min-height: 120px;">
                        <div class="position-absolute top-0 end-0 p-3">
                            <i class="bi bi-star-fill" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                        <div class="position-relative" style="z-index: 1;">
                            <h6 class="card-subtitle mb-2 text-white"
                                style="font-size: 0.875rem; font-weight: 500; opacity: 0.9;">
                                Total Poin Diberikan
                            </h6>
                            <h2 class="card-title mb-0 text-white" style="font-size: 2rem; font-weight: 700;">
                                {{ number_format($totalPoints) }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="card admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Detail Laporan
                </h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.waste.reports.export-pdf') }}" method="GET" class="d-inline">
                        @foreach(request()->all() as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
                        </button>
                    </form>
                    <form action="{{ route('admin.waste.reports.export') }}" method="GET" class="d-inline">
                        @foreach(request()->all() as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>Download Excel
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($wastes->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 12%;">Tanggal</th>
                                    <th style="width: 12%;">User</th>
                                    <th style="width: 12%;">Jenis</th>
                                    <th class="text-center" style="width: 10%;">Berat (kg)</th>
                                    <th style="width: 20%;">Lokasi Bank Sampah</th>
                                    <th class="text-center" style="width: 10%;">Poin</th>
                                    <th class="text-center" style="width: 10%;">Status</th>
                                    <th class="text-center" style="width: 14%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wastes as $waste)
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                {{ $waste->created_at->format('d/m/Y') }}<br>
                                                <span class="text-muted">{{ $waste->created_at->format('H:i') }}</span>
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $waste->user->name ?? 'N/A' }}</strong>
                                            @if($waste->user && $waste->user->email)
                                                <br><small class="text-muted">{{ Str::limit($waste->user->email, 25) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $waste->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($waste->bankSampah)
                                                <div>
                                                    <strong class="d-block mb-1">{{ $waste->bankSampah->name }}</strong>
                                                    <small class="text-muted">
                                                        <i
                                                            class="bi bi-geo-alt me-1"></i>{{ $waste->bankSampah->location_name ?? Str::limit($waste->bankSampah->address, 40) }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i>{{ $waste->points_earned ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($waste->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($waste->status === 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center align-items-center">
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-sm btn-warning btn-action" title="Edit"
                                                    onclick="openEditModal({{ $waste->id }}, '{{ $waste->category_id }}', '{{ $waste->amount }}', '{{ $waste->unit }}', '{{ $waste->points_earned }}', '{{ $waste->bank_sampah_id }}', '{{ $waste->status }}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @if($waste->status === 'pending')
                                                    <form action="{{ route('admin.waste.approve', $waste->id) }}" method="POST"
                                                        class="d-inline" id="approve-form-{{ $waste->id }}">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-success btn-action" title="Setujui"
                                                            onclick="confirmApprove({{ $waste->id }})">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.waste.reject', $waste->id) }}" method="POST"
                                                        class="d-inline" id="reject-form-{{ $waste->id }}">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-danger btn-action" title="Tolak"
                                                            onclick="confirmReject({{ $waste->id }})">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile List View -->
                    <div class="d-md-none waste-reports-list">
                        @foreach($wastes as $waste)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                @if($waste->category)
                                                    <span class="badge bg-info">{{ $waste->category->name }}</span>
                                                @endif
                                                @if($waste->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($waste->status === 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @endif
                                            </div>
                                            <h6 class="mb-1 fw-bold">{{ $waste->user->name ?? 'N/A' }}</h6>
                                            @if($waste->user && $waste->user->email)
                                                <small class="text-muted d-block mb-2">
                                                    <i class="bi bi-envelope me-1"></i>{{ Str::limit($waste->user->email, 30) }}
                                                </small>
                                            @endif
                                            <div class="d-flex flex-wrap gap-2 text-muted small mb-2">
                                                <span><i
                                                        class="bi bi-calendar3 me-1"></i>{{ $waste->created_at->format('d/m/Y') }}</span>
                                                <span><i class="bi bi-clock me-1"></i>{{ $waste->created_at->format('H:i') }}</span>
                                                <span><i
                                                        class="bi bi-box-seam me-1"></i>{{ number_format($waste->unit === 'gram' ? $waste->amount / 1000 : $waste->amount, 2) }}
                                                    kg</span>
                                                <span><i
                                                        class="bi bi-star-fill text-warning me-1"></i>{{ $waste->points_earned ?? 0 }}
                                                    Poin</span>
                                            </div>
                                            @if($waste->bankSampah)
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $waste->bankSampah->name }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1 justify-content-end mt-3">
                                        <!-- Edit Button Mobile -->
                                        <button type="button" class="btn btn-sm btn-warning btn-action" title="Edit"
                                            onclick="openEditModal({{ $waste->id }}, '{{ $waste->category_id }}', '{{ $waste->amount }}', '{{ $waste->unit }}', '{{ $waste->points_earned }}', '{{ $waste->bank_sampah_id }}', '{{ $waste->status }}')">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        @if($waste->status === 'pending')
                                            <form action="{{ route('admin.waste.approve', $waste->id) }}" method="POST" class="d-inline"
                                                id="approve-form-mobile-{{ $waste->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-success btn-action" title="Setujui"
                                                    onclick="confirmApprove({{ $waste->id }})">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.waste.reject', $waste->id) }}" method="POST" class="d-inline"
                                                id="reject-form-mobile-{{ $waste->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-danger btn-action" title="Tolak"
                                                    onclick="confirmReject({{ $waste->id }})">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                        <span class="fs-5">Belum ada laporan</span>
                    </div>
                @endif

                <!-- Pagination -->
                @if($wastes->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $wastes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editWasteModal" tabindex="-1" aria-labelledby="editWasteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editWasteModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Detail Laporan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editWasteForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label fw-bold">Jenis Sampah</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="mb-3">
                                    <label for="edit_amount" class="form-label fw-bold">Berat</label>
                                    <input type="number" class="form-control" id="edit_amount" name="amount" step="0.01"
                                        min="0.01" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="edit_unit" class="form-label fw-bold">Satuan</label>
                                    <select class="form-select" id="edit_unit" name="unit" required>
                                        <option value="kg">kg</option>
                                        <option value="gram">gram</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_points_earned" class="form-label fw-bold">Poin</label>
                            <input type="number" class="form-control" id="edit_points_earned" name="points_earned" min="0"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bank_sampah_id" class="form-label fw-bold">Bank Sampah</label>
                            <select class="form-select" id="edit_bank_sampah_id" name="bank_sampah_id">
                                <option value="">-- Pilih Bank Sampah --</option>
                                @foreach($bankSampahList as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} -
                                        {{ $bank->location_name ?? $bank->address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label fw-bold">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="pending">Menunggu</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const filterBtn = document.getElementById('filterDateBtn');

                // Set max date untuk end_date berdasarkan start_date
                startDateInput.addEventListener('change', function () {
                    if (this.value) {
                        endDateInput.min = this.value;
                        if (endDateInput.value && endDateInput.value < this.value) {
                            endDateInput.value = this.value;
                        }
                    }
                });

                // Set min date untuk start_date berdasarkan end_date
                endDateInput.addEventListener('change', function () {
                    if (this.value && startDateInput.value && startDateInput.value > this.value) {
                        startDateInput.value = this.value;
                    }
                });

                // Filter button click handler
                filterBtn.addEventListener('click', function () {
                    const startDate = startDateInput.value;
                    const endDate = endDateInput.value;

                    if (!startDate || !endDate) {
                        alert('Silakan pilih tanggal mulai dan tanggal akhir!');
                        return;
                    }

                    if (startDate > endDate) {
                        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                        return;
                    }

                    // Redirect dengan parameter tanggal dan bank sampah
                    const url = new URL('{{ route("admin.waste.reports") }}', window.location.origin);
                    url.searchParams.set('start_date', startDate);
                    url.searchParams.set('end_date', endDate);

                    // Tambahkan filter bank sampah jika dipilih
                    const bankSampahId = document.getElementById('bank_sampah_id').value;
                    if (bankSampahId) {
                        url.searchParams.set('bank_sampah_id', bankSampahId);
                    }

                    window.location.href = url.toString();
                });

                // Enter key handler
                [startDateInput, endDateInput].forEach(input => {
                    input.addEventListener('keypress', function (e) {
                        if (e.key === 'Enter') {
                            filterBtn.click();
                        }
                    });
                });

                // Filter bank sampah change handler
                document.getElementById('bank_sampah_id').addEventListener('change', function () {
                    const url = new URL('{{ route("admin.waste.reports") }}', window.location.origin);

                    // Preserve existing filters
                    if (startDateInput.value && endDateInput.value) {
                        url.searchParams.set('start_date', startDateInput.value);
                        url.searchParams.set('end_date', endDateInput.value);
                    } else {
                        const currentPeriod = '{{ $period }}';
                        if (currentPeriod !== 'custom') {
                            url.searchParams.set('period', currentPeriod);
                        }
                    }

                    // Add bank sampah filter
                    if (this.value) {
                        url.searchParams.set('bank_sampah_id', this.value);
                    }

                    window.location.href = url.toString();
                });
            });

            // Confirm Approve with SweetAlert2
            function confirmApprove(wasteId) {
                // Try to find form (desktop or mobile)
                const desktopForm = document.getElementById('approve-form-' + wasteId);
                const mobileForm = document.getElementById('approve-form-mobile-' + wasteId);
                const form = desktopForm || mobileForm;

                if (!form) {
                    console.error('Form not found for waste ID:', wasteId);
                    return;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Persetujuan',
                        text: 'Apakah Anda yakin ingin menyetujui data sampah ini? Poin akan ditambahkan ke user.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Setujui',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menyetujui data sampah ini? Poin akan ditambahkan ke user.')) {
                        form.submit();
                    }
                }
            }

            // Confirm Reject with SweetAlert2
            function confirmReject(wasteId) {
                // Try to find form (desktop or mobile)
                const desktopForm = document.getElementById('reject-form-' + wasteId);
                const mobileForm = document.getElementById('reject-form-mobile-' + wasteId);
                const form = desktopForm || mobileForm;

                if (!form) {
                    console.error('Form not found for waste ID:', wasteId);
                    return;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Penolakan',
                        text: 'Apakah Anda yakin ingin menolak data sampah ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tolak',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menolak data sampah ini?')) {
                        form.submit();
                    }
                }
            }

            // Open Edit Modal Function
            function openEditModal(wasteId, categoryId, amount, unit, pointsEarned, bankSampahId, status) {
                // Set form action
                const form = document.getElementById('editWasteForm');
                form.action = '{{ url("admin/waste") }}/' + wasteId;

                // Populate form fields
                document.getElementById('edit_category_id').value = categoryId;
                document.getElementById('edit_amount').value = amount;
                document.getElementById('edit_unit').value = unit;
                document.getElementById('edit_points_earned').value = pointsEarned;
                document.getElementById('edit_bank_sampah_id').value = bankSampahId || '';
                document.getElementById('edit_status').value = status;

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('editWasteModal'));
                modal.show();
            }
        </script>
    @endpush
@endsection