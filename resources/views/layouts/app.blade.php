<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EcoWaste - Pengelolaan Sampah')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    
    @stack('styles')
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #8BC34A;
            --light-green: #C8E6C9;
            --dark-green: #1B5E20;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            margin-left: 2rem;
            padding-left: 1rem;
        }
        
        @media (max-width: 991.98px) {
            .navbar-brand {
                margin-left: 0.5rem;
                padding-left: 0.25rem;
            }
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
        }
        
        .sidebar {
            background-color: white;
            min-height: calc(100vh - 56px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            transition: all 0.3s ease;
            position: relative;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            transform: translateX(5px) translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.5),
                        0 4px 8px rgba(46, 125, 50, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3),
                        inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-green));
            color: white;
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.5),
                        0 4px 8px rgba(46, 125, 50, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3),
                        inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        
        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }
        
        /* Offcanvas Sidebar Styling - Same as Desktop Sidebar */
        .offcanvas.offcanvas-start {
            width: 250px;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            border: none !important;
            border-right: none !important;
            outline: none !important;
            border-left: none !important;
        }
        
        /* Offcanvas Backdrop Styling - Lighter and smoother */
        .offcanvas-backdrop {
            background-color: rgba(0, 0, 0, 0.25) !important;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        
        .offcanvas-backdrop.show {
            opacity: 1 !important;
        }
        
        /* Remove any default borders or outlines */
        .offcanvas {
            border: none !important;
            outline: none !important;
            border-left: none !important;
            border-right: none !important;
        }
        
        /* Remove border from offcanvas header and body */
        .offcanvas-header {
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Ensure body doesn't have dark background when offcanvas is open */
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }
        
        body.modal-open .offcanvas-backdrop {
            background-color: rgba(0, 0, 0, 0.25) !important;
        }
        
        /* Remove any dark borders or shadows that might appear */
        .offcanvas.show {
            border: none !important;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1) !important;
        }
        
        .offcanvas-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background: #ffffff !important;
        }
        
        .offcanvas-header .offcanvas-title {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            font-size: 1.25rem;
        }
        
        .offcanvas-header .offcanvas-title i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .offcanvas-header .btn-close {
            opacity: 0.5;
        }
        
        .offcanvas-header .btn-close:hover {
            opacity: 1;
        }
        
        .offcanvas-body {
            padding: 0;
            overflow-y: auto;
        }
        
        .offcanvas-body .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            transition: all 0.3s ease;
            position: relative;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        .offcanvas-body .nav-link:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            transform: translateX(5px) translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.5),
                        0 4px 8px rgba(46, 125, 50, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3),
                        inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }
        
        .offcanvas-body .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-green));
            color: white;
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.5),
                        0 4px 8px rgba(46, 125, 50, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3),
                        inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }
        
        .offcanvas-body .nav-link i {
            width: 20px;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        
        .offcanvas-body .nav-link:hover i {
            transform: scale(1.1);
        }
        
        .offcanvas-body .nav {
            padding: 0 0 20px 0;
        }
        
        /* User Info Styling */
        .user-info {
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            position: relative;
        }
        
        .user-info .user-details {
            flex: 1;
            padding-right: 100px;
        }
        
        .user-info .user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }
        
        .user-info .user-name i {
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .user-info .user-points {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .user-info .user-points i {
            margin-right: 6px;
            color: var(--primary-color);
        }
        
        .user-info .settings-icon {
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: #007bff;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
            text-decoration: none;
        }
        
        .user-info .settings-icon:hover {
            background: #0056b3;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
            color: white;
        }
        
        .user-info .logout-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: #dc3545;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }
        
        .user-info .logout-icon:hover {
            background: #c82333;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }
        
        .user-info .logout-form {
            display: none;
        }
        
        .offcanvas-body .user-info {
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            margin: 15px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        
        .offcanvas-body .user-info .user-details {
            flex: 1;
            padding-right: 100px;
        }
        
        .offcanvas-body .user-info .user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }
        
        .offcanvas-body .user-info .user-name i {
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .offcanvas-body .user-info .user-points {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .offcanvas-body .user-info .user-points i {
            margin-right: 6px;
            color: var(--primary-color);
        }
        
        .offcanvas-body .user-info {
            position: relative;
        }
        
        .offcanvas-body .user-info .user-details {
            padding-right: 50px;
        }
        
        .offcanvas-body .user-info .settings-icon {
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: #007bff;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
            text-decoration: none;
        }
        
        .offcanvas-body .user-info .settings-icon:hover {
            background: #0056b3;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
            color: white;
        }
        
        .offcanvas-body .user-info .logout-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: #dc3545;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }
        
        .offcanvas-body .user-info .logout-icon:hover {
            background: #c82333;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }
        
        .offcanvas-body .user-info .logout-form {
            display: none;
        }
        
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .progress-bar-green {
            background-color: var(--accent-color);
        }
        
        .eco-badge {
            background-color: var(--light-green);
            color: var(--dark-green);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .forum-post {
            border-left: 4px solid var(--accent-color);
        }
        
        /* Dropdown menu positioning */
        .navbar-nav .dropdown-menu {
            right: 0;
            left: auto;
            margin-top: 0.5rem;
        }
        
        @media (max-width: 991.98px) {
            .navbar-nav .dropdown-menu {
                right: auto;
                left: 0;
            }
            
            .sidebar {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
            }
            
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
                max-width: 100%;
                overflow-x: hidden;
            }
            
            main {
                width: 100% !important;
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                <i class="bi bi-recycle me-2"></i>EcoWaste
            </a>
            
            @auth
            @if(auth()->user()->role === 'user')
            <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="offcanvas" 
                    data-bs-target="#sidebarOffcanvas" aria-label="Toggle menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            @endif
            @endauth
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav ms-auto">
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            @if(auth()->check() && auth()->user()->role === 'user')
            <!-- Sidebar untuk User (Desktop) -->
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <!-- User Info -->
                    <div class="user-info mb-3">
                        <div class="user-details">
                            <div class="user-name">
                                <i class="bi bi-person-circle"></i>{{ Auth::user()->name }}
                            </div>
                            <div class="user-points">
                                <i class="bi bi-award"></i>{{ number_format(Auth::user()->total_points ?? 0) }} Poin
                            </div>
                        </div>
                        <a href="{{ route('user.profile.index') }}" class="settings-icon" title="Pengaturan Profil">
                            <i class="bi bi-gear"></i>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                        </form>
                        <div class="logout-icon" onclick="this.previousElementSibling.submit();" title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}" 
                               href="{{ route('user.dashboard') }}">
                                <i class="bi bi-house-door"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/waste*') ? 'active' : '' }}" 
                               href="{{ route('user.waste.index') }}">
                                <i class="bi bi-trash"></i> Data Sampah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/statistics*') ? 'active' : '' }}" 
                               href="{{ route('user.statistics.daily') }}">
                                <i class="bi bi-bar-chart"></i> Statistik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/bank-sampah*') ? 'active' : '' }}" 
                               href="{{ route('user.bank-sampah.index') }}">
                                <i class="bi bi-geo"></i> Bank Sampah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/education*') && !Request::is('user/education/challenges*') ? 'active' : '' }}" 
                               href="{{ route('user.education.tips') }}">
                                <i class="bi bi-lightbulb"></i> Edukasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/education/challenges*') ? 'active' : '' }}" 
                               href="{{ route('user.education.challenges') }}">
                                <i class="bi bi-trophy"></i> Tantangan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/points*') ? 'active' : '' }}" 
                               href="{{ route('user.points.index') }}">
                                <i class="bi bi-award"></i> Poin & Reward
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('user/community*') ? 'active' : '' }}" 
                               href="{{ route('user.community.forum') }}">
                                <i class="bi bi-chat-dots"></i> Komunitas
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <main class="{{ auth()->check() && auth()->user()->role === 'user' ? 'col-md-9 col-lg-10' : 'col-12' }} ms-sm-auto px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'user')
    <!-- Offcanvas Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">
                <i class="bi bi-recycle me-2"></i>EcoWaste
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- User Info -->
            <div class="user-info">
                <div class="user-details">
                    <div class="user-name">
                        <i class="bi bi-person-circle"></i>{{ Auth::user()->name }}
                    </div>
                    <div class="user-points">
                        <i class="bi bi-award"></i>{{ number_format(Auth::user()->total_points ?? 0) }} Poin
                    </div>
                </div>
                <a href="{{ route('user.profile.index') }}" class="settings-icon" title="Pengaturan Profil">
                    <i class="bi bi-gear"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                </form>
                <div class="logout-icon" onclick="this.previousElementSibling.submit();" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
            </div>
            
            <div class="p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}" 
                           href="{{ route('user.dashboard') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/waste*') ? 'active' : '' }}" 
                           href="{{ route('user.waste.index') }}">
                            <i class="bi bi-trash"></i> Data Sampah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/statistics*') ? 'active' : '' }}" 
                           href="{{ route('user.statistics.daily') }}">
                            <i class="bi bi-bar-chart"></i> Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/bank-sampah*') ? 'active' : '' }}" 
                           href="{{ route('user.bank-sampah.index') }}">
                            <i class="bi bi-geo"></i> Bank Sampah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/education*') && !Request::is('user/education/challenges*') ? 'active' : '' }}" 
                           href="{{ route('user.education.tips') }}">
                            <i class="bi bi-lightbulb"></i> Edukasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/education/challenges*') ? 'active' : '' }}" 
                           href="{{ route('user.education.challenges') }}">
                            <i class="bi bi-trophy"></i> Tantangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/points*') ? 'active' : '' }}" 
                           href="{{ route('user.points.index') }}">
                            <i class="bi bi-award"></i> Poin & Reward
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('user/community*') ? 'active' : '' }}" 
                           href="{{ route('user.community.forum') }}">
                            <i class="bi bi-chat-dots"></i> Komunitas
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Global CSRF Token for AJAX (jQuery)
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Delete confirmation
        function confirmDelete(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            } else {
                if (confirm(message)) {
                    document.getElementById(formId).submit();
                }
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>