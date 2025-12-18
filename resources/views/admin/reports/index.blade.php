@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h2 class="mb-0">
            <i class="bi bi-file-bar-graph me-2"></i>Laporan
        </h2>
    </div>

    <!-- Report Options -->
    <div class="row">
        <div class="col-md-12">
            <div class="card admin-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Pilih Jenis Laporan
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.generate') }}" id="reportForm">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="type" class="form-label">
                                    <i class="bi bi-calendar me-1"></i>Jenis Laporan
                                </label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="daily">Harian</option>
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly" selected>Bulanan</option>
                                    <option value="custom">Custom (Pilih Tanggal)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3" id="startDateGroup" style="display: none;">
                                <label for="start_date" class="form-label">
                                    <i class="bi bi-calendar3 me-1"></i>Dari Tanggal
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                            </div>
                            
                            <div class="col-md-4 mb-3" id="endDateGroup" style="display: none;">
                                <label for="end_date" class="form-label">
                                    <i class="bi bi-calendar3 me-1"></i>Sampai Tanggal
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>Generate Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-12 col-md-4 mb-4">
            <div class="card admin-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Laporan Sampah</h5>
                    <p class="card-text text-muted">Lihat laporan detail data sampah</p>
                    <a href="{{ route('admin.waste.reports') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card admin-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up display-4 text-success mb-3"></i>
                    <h5 class="card-title">Statistik</h5>
                    <p class="card-text text-muted">Lihat statistik dan analisis data</p>
                    <a href="{{ route('admin.statistics.index') }}" class="btn btn-success">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Statistik
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card admin-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history display-4 text-info mb-3"></i>
                    <h5 class="card-title">Aktivitas</h5>
                    <p class="card-text text-muted">Lihat aktivitas pengguna</p>
                    <a href="{{ route('admin.activities') }}" class="btn btn-info">
                        <i class="bi bi-arrow-right me-1"></i>Lihat Aktivitas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const startDateGroup = document.getElementById('startDateGroup');
        const endDateGroup = document.getElementById('endDateGroup');
        
        function toggleDateInputs() {
            if (typeSelect.value === 'custom') {
                startDateGroup.style.display = 'block';
                endDateGroup.style.display = 'block';
                document.getElementById('start_date').required = true;
                document.getElementById('end_date').required = true;
            } else {
                startDateGroup.style.display = 'none';
                endDateGroup.style.display = 'none';
                document.getElementById('start_date').required = false;
                document.getElementById('end_date').required = false;
            }
        }
        
        typeSelect.addEventListener('change', toggleDateInputs);
        toggleDateInputs(); // Initialize on page load
    });
</script>
@endpush
@endsection

