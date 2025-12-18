<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo a {
            text-decoration: none;
            color: #2E7D32;
            font-size: 2rem;
            font-weight: bold;
        }
        .auth-logo a:hover {
            color: #1B5E20;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* Remove Bootstrap default validation icon completely */
        .form-control.is-invalid,
        .form-control.is-invalid:focus,
        .form-control.is-invalid:not(:disabled):not([readonly]) {
            background-image: none !important;
            background-position: unset !important;
            background-repeat: no-repeat !important;
            background-size: unset !important;
            padding-right: 50px !important;
        }
        .form-control.is-valid,
        .form-control.is-valid:focus,
        .form-control.is-valid:not(:disabled):not([readonly]) {
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
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <a href="/">
                    <i class="bi bi-recycle me-2"></i>EcoWaste
                </a>
            </div>
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

