<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - EcoWaste')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
            max-width: 100vw;
            width: 100%;
        }

        body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100%;
            position: relative;
        }

        :root {
            --admin-primary: #1976D2;
            --admin-secondary: #2196F3;
            --admin-accent: #64B5F6;
            --admin-dark: #0D47A1;
            --admin-green: #28a745;
            --admin-green-dark: #218838;
            --admin-green-light: #34ce57;
        }

        .admin-sidebar {
            background: #ffffff;
            color: #333;
            height: 100vh;
            width: 250px;
            position: fixed;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .admin-sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .admin-sidebar .sidebar-header h4 {
            color: var(--admin-green);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-sidebar .sidebar-header h4 i {
            color: var(--admin-green);
            font-size: 1.5rem;
        }

        .admin-sidebar .user-info {
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            position: relative;
        }

        .admin-sidebar .user-info .user-details {
            flex: 1;
            padding-right: 100px;
        }

        .admin-sidebar .user-info .user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .user-info .user-name i {
            margin-right: 8px;
            color: var(--admin-green);
        }

        .admin-sidebar .user-info .user-role {
            font-size: 0.8rem;
            color: var(--admin-green);
            font-weight: 500;
            text-transform: uppercase;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .user-info .user-role i {
            margin-right: 6px;
        }

        .admin-sidebar .user-info .settings-icon {
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

        .admin-sidebar .user-info .settings-icon:hover {
            background: #0056b3;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
            color: white;
        }

        .admin-sidebar .user-info .logout-icon {
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

        .admin-sidebar .user-info .logout-icon:hover {
            background: #c82333;
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }

        .admin-sidebar .user-info .logout-form {
            display: none;
        }

        .admin-sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            transition: all 0.3s ease;
            position: relative;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .nav-link:hover {
            background: linear-gradient(135deg, var(--admin-green-light), var(--admin-green));
            color: white;
            transform: translateX(5px) translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.5),
                0 4px 8px rgba(40, 167, 69, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--admin-green), var(--admin-green-dark));
            color: white;
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.5),
                0 4px 8px rgba(40, 167, 69, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        .admin-sidebar .nav-link:hover i {
            transform: scale(1.1);
        }

        .admin-sidebar .nav-item small {
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Offcanvas Sidebar Styling - Same as Desktop Sidebar */
        .offcanvas.offcanvas-start {
            width: 250px;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .offcanvas-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background: #ffffff !important;
        }

        .offcanvas-header .offcanvas-title {
            color: var(--admin-green);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            font-size: 1.25rem;
        }

        .offcanvas-header .offcanvas-title i {
            color: var(--admin-green);
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

        .offcanvas-body .user-info {
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            margin: 15px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            position: relative;
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
            color: var(--admin-green);
        }

        .offcanvas-body .user-info .user-role {
            font-size: 0.8rem;
            color: var(--admin-green);
            font-weight: 500;
            text-transform: uppercase;
            display: flex;
            align-items: center;
        }

        .offcanvas-body .user-info .user-role i {
            margin-right: 6px;
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

        .offcanvas-body .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            transition: all 0.3s ease;
            position: relative;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .offcanvas-body .nav-link:hover {
            background: linear-gradient(135deg, var(--admin-green-light), var(--admin-green));
            color: white;
            transform: translateX(5px) translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.5),
                0 4px 8px rgba(40, 167, 69, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        }

        .offcanvas-body .nav-link.active {
            background: linear-gradient(135deg, var(--admin-green), var(--admin-green-dark));
            color: white;
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.5),
                0 4px 8px rgba(40, 167, 69, 0.4),
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

        .offcanvas-body .nav-item small {
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .offcanvas-body .nav {
            padding: 0 0 20px 0;
        }

        .admin-main {
            margin-left: 250px;
            background-color: #f5f7fa;
            min-height: 100vh;
            width: calc(100% - 250px);
            overflow-x: hidden;
            position: relative;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .admin-main {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw;
            }

            .admin-sidebar {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
            }

            body,
            html {
                overflow-x: hidden !important;
                max-width: 100vw;
                position: relative;
            }

            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
                max-width: 100%;
                overflow-x: hidden;
            }

            .d-flex {
                flex-wrap: nowrap;
            }
        }

        .admin-navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--admin-green) !important;
            margin-left: 2rem;
            padding-left: 1rem;
        }

        @media (max-width: 991.98px) {
            .navbar-brand {
                margin-left: 0.5rem;
                padding-left: 0.25rem;
            }
        }

        .admin-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }

        /* Action Buttons in Detail Pages */
        .btn-action-card {
            min-width: 140px;
            height: 80px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-action-card:active {
            transform: translateY(-1px);
        }

        .btn-action-card i {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .btn-action-card .small {
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.2;
        }

        @media (max-width: 767.98px) {
            .btn-action-card {
                min-width: 120px;
                height: 70px;
                padding: 0.5rem;
            }

            .btn-action-card i {
                font-size: 1.25rem;
            }

            .btn-action-card .small {
                font-size: 0.75rem;
            }
        }

        /* Action Buttons Styling */
        .table td .d-flex {
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
        }

        .table .btn-action {
            min-width: 36px;
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .table .btn-action i {
            font-size: 1rem;
            line-height: 1;
            margin: 0;
        }

        .table .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .table .btn-action:active {
            transform: translateY(0);
        }

        /* Ensure action column is centered and has consistent width */
        .table th.text-center {
            text-align: center;
        }

        .table td:last-child {
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .table .btn-action {
                min-width: 32px;
                width: 32px;
                height: 32px;
            }

            .table .btn-action i {
                font-size: 0.875rem;
            }
        }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
            position: relative;
            border-radius: 0.375rem;
        }

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        @media (max-width: 767.98px) {
            .admin-main {
                width: 100% !important;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                display: block;
                width: 100%;
                max-width: 100%;
                margin: 0;
            }

            .table {
                font-size: 0.8rem;
                width: 100%;
                min-width: 600px;
                /* Minimum width untuk menjaga struktur tabel */
                margin-bottom: 0;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.4rem;
                white-space: nowrap;
                vertical-align: middle;
            }

            .table th {
                font-size: 0.75rem;
                font-weight: 600;
            }

            .card-body {
                padding: 0.75rem;
                overflow-x: hidden;
                max-width: 100%;
            }

            .card-body .table-responsive {
                margin: -0.75rem;
                padding: 0.75rem;
                width: calc(100% + 1.5rem);
            }

            .card {
                overflow: hidden;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }

            /* Make action buttons smaller on mobile */
            .table .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            /* Stack badges vertically if needed */
            .table .badge {
                font-size: 0.7rem;
                padding: 0.25em 0.5em;
            }
        }

        @media (max-width: 575.98px) {
            .table {
                min-width: 500px;
            }

            .table th,
            .table td {
                padding: 0.4rem 0.3rem;
                font-size: 0.75rem;
            }
        }

        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        }

        /* Remove Bootstrap default validation icon for password fields */
        .form-control.is-invalid[type="password"],
        .form-control.is-invalid[type="text"],
        .form-control.is-invalid[type="password"]:focus,
        .form-control.is-invalid[type="text"]:focus,
        .form-control.is-invalid[type="password"]:not(:disabled):not([readonly]),
        .form-control.is-invalid[type="text"]:not(:disabled):not([readonly]) {
            background-image: none !important;
            background-position: unset !important;
            background-repeat: no-repeat !important;
            background-size: unset !important;
            padding-right: 50px !important;
        }

        .form-control.is-valid[type="password"],
        .form-control.is-valid[type="text"],
        .form-control.is-valid[type="password"]:focus,
        .form-control.is-valid[type="text"]:focus,
        .form-control.is-valid[type="password"]:not(:disabled):not([readonly]),
        .form-control.is-valid[type="text"]:not(:disabled):not([readonly]) {
            background-image: none !important;
            background-position: unset !important;
            background-repeat: no-repeat !important;
            background-size: unset !important;
            padding-right: 50px !important;
        }

        /* Ensure password field with eye icon has proper padding */
        input[type="password"].form-control,
        input[type="text"].form-control {
            padding-right: 50px !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="d-flex position-relative">
        <!-- Sidebar -->
        <div class="admin-sidebar d-none d-lg-block" style="z-index: 1000;">
            <div class="sidebar-header">
                <h4>
                    <i class="bi bi-recycle me-2"></i>EcoWaste
                </h4>
            </div>

            <div class="user-info">
                <div class="user-details">
                    <div class="user-name">
                        <i class="bi bi-person-circle"></i>{{ Auth::user()->name }}
                    </div>
                    <div class="user-role">
                        <i class="bi bi-shield-check"></i>{{ ucfirst(Auth::user()->role) }}
                    </div>
                </div>
                <a href="{{ route('admin.profile.index') }}" class="settings-icon" title="Pengaturan Profil">
                    <i class="bi bi-gear"></i>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                    @csrf
                </form>
                <div class="logout-icon" onclick="this.previousElementSibling.submit();" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
            </div>

            <div class="p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <small class="text-uppercase text-muted ms-3">Manajemen</small>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people"></i> Pengguna
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/waste/reports*') ? 'active' : '' }}"
                            href="{{ route('admin.waste.reports') }}">
                            <i class="bi bi-trash"></i> Data Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/bank-sampah*') ? 'active' : '' }}"
                            href="{{ route('admin.bank-sampah.index') }}">
                            <i class="bi bi-geo-alt"></i> Bank Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/waste-types*') ? 'active' : '' }}"
                            href="{{ route('admin.waste-types.index') }}">
                            <i class="bi bi-diagram-3"></i> Tipe Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/waste/categories*') ? 'active' : '' }}"
                            href="{{ route('admin.waste.categories.index') }}">
                            <i class="bi bi-tags"></i> Kategori&nbsp;Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/rewards*') ? 'active' : '' }}"
                            href="{{ route('admin.rewards.index') }}">
                            <i class="bi bi-gift"></i> Rewards
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <small class="text-uppercase text-muted ms-3">Konten</small>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/education*') && !Request::is('admin/education/challenges*') ? 'active' : '' }}"
                            href="{{ route('admin.education.tips.index') }}">
                            <i class="bi bi-lightbulb"></i> Tips & Artikel
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/education/challenges*') ? 'active' : '' }}"
                            href="{{ route('admin.education.challenges.index') }}">
                            <i class="bi bi-trophy"></i> Challenges
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <small class="text-uppercase text-muted ms-3">Laporan</small>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}"
                            href="{{ route('admin.reports.index') }}">
                            <i class="bi bi-file-bar-graph"></i> Laporan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/statistics*') ? 'active' : '' }}"
                            href="{{ route('admin.statistics.index') }}">
                            <i class="bi bi-graph-up"></i> Statistik
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-main flex-grow-1">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-recycle me-2"></i>EcoWaste
                </a>

                <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarOffcanvas" aria-label="Toggle menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <!-- Content -->
        <div class="container-fluid p-3 p-md-4" style="max-width: 100%; overflow-x: hidden;">
            @yield('content')
        </div>
    </div>
    </div>

    <!-- Offcanvas Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">
                <i class="bi bi-recycle me-2"></i>EcoWaste
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="user-info">
                <div class="user-details">
                    <div class="user-name">
                        <i class="bi bi-person-circle"></i>{{ Auth::user()->name }}
                    </div>
                    <div class="user-role">
                        <i class="bi bi-shield-check"></i>{{ ucfirst(Auth::user()->role) }}
                    </div>
                </div>
                <a href="{{ route('admin.profile.index') }}" class="settings-icon" title="Pengaturan Profil">
                    <i class="bi bi-gear"></i>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                    @csrf
                </form>
                <div class="logout-icon" onclick="document.querySelector('.offcanvas-body .logout-form').submit();"
                    title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
            </div>

            <div class="p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <small class="text-uppercase text-muted ms-3">Manajemen</small>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people"></i> Pengguna
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/waste/reports*') ? 'active' : '' }}"
                            href="{{ route('admin.waste.reports') }}">
                            <i class="bi bi-trash"></i> Data Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/bank-sampah*') ? 'active' : '' }}"
                            href="{{ route('admin.bank-sampah.index') }}">
                            <i class="bi bi-geo-alt"></i> Bank Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/waste-types*') ? 'active' : '' }}"
                            href="{{ route('admin.waste-types.index') }}">
                            <i class="bi bi-diagram-3"></i> Tipe Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/waste/categories*') ? 'active' : '' }}"
                            href="{{ route('admin.waste.categories.index') }}">
                            <i class="bi bi-tags"></i> Kategori Sampah
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/rewards*') ? 'active' : '' }}"
                            href="{{ route('admin.rewards.index') }}">
                            <i class="bi bi-gift"></i> Rewards
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <small class="text-uppercase text-muted ms-3">Konten</small>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/education*') && !Request::is('admin/education/challenges*') ? 'active' : '' }}"
                            href="{{ route('admin.education.tips.index') }}">
                            <i class="bi bi-lightbulb"></i> Tips & Artikel
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/education/challenges*') ? 'active' : '' }}"
                            href="{{ route('admin.education.challenges.index') }}">
                            <i class="bi bi-trophy"></i> Challenges
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <small class="text-uppercase text-muted ms-3">Laporan</small>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}"
                            href="{{ route('admin.reports.index') }}">
                            <i class="bi bi-file-bar-graph"></i> Laporan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/statistics*') ? 'active' : '' }}"
                            href="{{ route('admin.statistics.index') }}">
                            <i class="bi bi-graph-up"></i> Statistik
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- SweetAlert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @stack('scripts')
</body>

</html>