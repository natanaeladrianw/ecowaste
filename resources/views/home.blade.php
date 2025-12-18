<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoWaste - Kelola Sampah dengan Bijak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 40px 0 30px 0;
            border-radius: 15px;
            will-change: transform;
        }
        
        @media (min-width: 768px) {
            .hero-section {
                padding: 80px 0 60px 0;
            }
        }
        
        .hero-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            text-align: center;
        }
        
        @media (min-width: 768px) {
            .hero-content {
                text-align: left;
            }
        }
        
        .hero-carousel {
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .hero-carousel .carousel-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        @media (min-width: 576px) {
            .hero-carousel .carousel-item img {
                height: 300px;
            }
        }
        
        @media (min-width: 768px) {
            .hero-carousel .carousel-item img {
                height: 400px;
            }
        }
        
        .hero-carousel .carousel-inner {
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        .hero-carousel .carousel-inner:active {
            cursor: grabbing;
        }
        
        .hero-carousel .carousel-indicators {
            margin-bottom: 10px;
        }
        
        @media (min-width: 768px) {
            .hero-carousel .carousel-indicators {
                margin-bottom: 20px;
            }
        }
        
        .hero-carousel .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            border: 2px solid white;
        }
        
        .hero-carousel .carousel-indicators button.active {
            background-color: white;
        }
        
        .feature-card {
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        /* Modal Background Blur */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }
        
        @media (min-width: 768px) {
            .modal-header {
                padding: 1.5rem;
            }
        }
        
        .modal-body {
            padding: 1rem 1.5rem;
        }
        
        @media (min-width: 768px) {
            .modal-body {
                padding: 1.5rem;
            }
        }
        
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 0.75rem 1.5rem;
        }
        
        @media (min-width: 768px) {
            .modal-footer {
                padding: 1rem 1.5rem;
            }
        }
        
        .modal-dialog {
            margin: 1rem;
        }
        
        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto;
            }
        }
        
        /* Form styling */
        .form-control.is-invalid,
        .form-control.is-invalid:focus,
        .form-control.is-invalid:not(:disabled):not([readonly]) {
            background-image: none !important;
            background-position: unset !important;
            background-repeat: no-repeat !important;
            background-size: unset !important;
            padding-right: 50px !important;
        }
        
        input[type="password"].form-control,
        input[type="text"].form-control {
            padding-right: 50px !important;
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
            animation: slideDown 0.3s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand fw-bold text-success mb-0 ps-0 ps-md-2" href="/">
                <i class="bi bi-recycle me-2"></i>EcoWaste
            </a>
            <div class="d-flex gap-1 gap-md-2 align-items-center ms-auto pe-0 pe-md-2">
                <button type="button" class="btn btn-light border border-dark btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal" style="border-radius: 8px; white-space: nowrap; font-size: 0.875rem;">
                    <i class="bi bi-person-plus me-1 me-md-2"></i><span class="d-none d-sm-inline">Daftar Sekarang</span><span class="d-sm-none">Daftar</span>
                </button>
                <button type="button" class="btn btn-success border border-white text-white btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal" style="border-radius: 8px; white-space: nowrap; font-size: 0.875rem;">
                    <i class="bi bi-box-arrow-in-right me-1 me-md-2"></i>Masuk
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="px-2 px-md-3 px-lg-4 px-xl-5 mt-3 mt-md-4 mt-lg-5">
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center g-3 g-md-4">
                    <!-- Text Content - Left -->
                    <div class="col-12 col-md-6 order-2 order-md-1">
                        <div class="hero-content">
                            <h1 class="display-5 display-md-4 fw-bold mb-3 mb-md-4">Kelola Sampah dengan Bijak</h1>
                            <p class="lead mb-0" style="font-size: 1rem;">Platform digital untuk mengelola sampah rumah tangga, dapatkan poin, dan berkontribusi untuk lingkungan yang lebih baik</p>
                        </div>
                    </div>
                    
                    <!-- Carousel - Right -->
                    <div class="col-12 col-md-6 order-1 order-md-2">
                        <div class="hero-carousel">
                            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner" id="carouselInner">
                                    <div class="carousel-item active">
                                        <img src="{{ asset('img/gambar 1.jpg') }}" class="d-block w-100" alt="Gambar 1" draggable="false">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('img/gambar 2.jpg') }}" class="d-block w-100" alt="Gambar 2" draggable="false">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="{{ asset('img/gambar 3.jpg') }}" class="d-block w-100" alt="Gambar 3" draggable="false">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Features Section -->
    <section class="py-4 py-md-5">
        <div class="container">
            <h2 class="text-center mb-4 mb-md-5" style="font-size: 1.75rem;">Fitur Utama</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-trash text-success display-4"></i>
                            </div>
                            <h5 class="card-title">Input Data Sampah</h5>
                            <p class="card-text text-muted">Catat jenis dan jumlah sampah harian Anda dengan mudah</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-award text-primary display-4"></i>
                            </div>
                            <h5 class="card-title">Sistem Poin & Reward</h5>
                            <p class="card-text text-muted">Dapatkan poin dari setiap aktivitas dan tukar dengan hadiah menarik</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-geo-alt text-warning display-4"></i>
                            </div>
                            <h5 class="card-title">Lokasi Pengumpulan Sampah</h5>
                            <p class="card-text text-muted">Temukan lokasi pengumpulan sampah terdekat dan lihat rute menuju lokasi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-graph-up text-info display-4"></i>
                            </div>
                            <h5 class="card-title">Statistik & Analisis</h5>
                            <p class="card-text text-muted">Lihat statistik harian, mingguan, dan bulanan sampah Anda</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-lightbulb text-danger display-4"></i>
                            </div>
                            <h5 class="card-title">Edukasi & Tips</h5>
                            <p class="card-text text-muted">Pelajari cara mengelola sampah dengan baik melalui tips dan artikel</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-people text-secondary display-4"></i>
                            </div>
                            <h5 class="card-title">Komunitas</h5>
                            <p class="card-text text-muted">Bergabung dengan komunitas dan bagikan pengalaman Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} EcoWaste. All rights reserved.</p>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div class="w-100 text-center mb-3">
                        <a href="/" class="text-decoration-none text-success">
                            <i class="bi bi-recycle me-2" style="font-size: 2rem;"></i>
                            <span class="fw-bold" style="font-size: 1.5rem;">EcoWaste</span>
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center fw-bold mb-4">Masuk ke Akun</h5>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i><strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any() && (session('open_modal') == 'login' || !session('open_modal')))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="login_email" class="form-label">Email</label>
                            <input id="login_email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="login_password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input id="login_password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" style="padding-right: 50px !important;" />
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                        style="border: none; background: none; z-index: 20 !important; cursor: pointer; color: #6c757d; padding: 0 15px; height: 100%; display: flex; align-items: center; pointer-events: auto;"
                                        onclick="togglePassword('login_password')">
                                    <i class="bi bi-eye" id="login_password-eye" style="font-size: 1.1rem;"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label for="remember_me" class="form-check-label">Ingat saya</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (Route::has('password.request'))
                                <a href="#" class="text-decoration-none text-success" onclick="switchToForgotPassword()">
                                    Lupa password?
                                </a>
                            @else
                                <div></div>
                            @endif

                            <button type="submit" class="btn btn-success">
                                Masuk
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted">Belum punya akun? </span>
                        <a href="#" class="text-decoration-none text-success" onclick="switchToRegister()">Daftar sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div class="w-100 text-center mb-3">
                        <a href="/" class="text-decoration-none text-success">
                            <i class="bi bi-recycle me-2" style="font-size: 2rem;"></i>
                            <span class="fw-bold" style="font-size: 1.5rem;">EcoWaste</span>
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center fw-bold mb-4">Daftar Akun</h5>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i><strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any() && session('open_modal') == 'register')
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="register_name" class="form-label">Nama</label>
                            <input id="register_name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="register_email" class="form-label">Email</label>
                            <input id="register_email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="register_password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input id="register_password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" style="padding-right: 50px !important;" />
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                        style="border: none; background: none; z-index: 20 !important; cursor: pointer; color: #6c757d; padding: 0 15px; height: 100%; display: flex; align-items: center; pointer-events: auto;"
                                        onclick="togglePassword('register_password')">
                                    <i class="bi bi-eye" id="register_password-eye" style="font-size: 1.1rem;"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="register_password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="position-relative">
                                <input id="register_password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" style="padding-right: 50px !important;" />
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                                        style="border: none; background: none; z-index: 20 !important; cursor: pointer; color: #6c757d; padding: 0 15px; height: 100%; display: flex; align-items: center; pointer-events: auto;"
                                        onclick="togglePassword('register_password_confirmation')">
                                    <i class="bi bi-eye" id="register_password_confirmation-eye" style="font-size: 1.1rem;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#" class="text-decoration-none text-success" onclick="switchToLogin()">
                                Sudah punya akun?
                            </a>

                            <button type="submit" class="btn btn-success">
                                Daftar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div class="w-100 text-center mb-3">
                        <a href="/" class="text-decoration-none text-success">
                            <i class="bi bi-recycle me-2" style="font-size: 2rem;"></i>
                            <span class="fw-bold" style="font-size: 1.5rem;">EcoWaste</span>
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center fw-bold mb-4">Lupa Password</h5>

                    <div class="mb-4 text-muted text-center">
                        Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any() && session('open_modal') == 'forgot-password')
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="forgot_email" class="form-label">Email</label>
                            <input id="forgot_email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#" class="text-decoration-none text-success" onclick="switchToLogin()">
                                Kembali ke login
                            </a>

                            <button type="submit" class="btn btn-success">
                                Kirim Link Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth Hero Section Animation
        document.addEventListener('DOMContentLoaded', function() {
            const heroSection = document.querySelector('.hero-section');
            if (!heroSection) return;
            
            let animationId = null;
            let targetHoverOffset = 0;
            let currentHoverOffset = 0;
            let isHovering = false;
            const floatRange = 10; // Range of floating animation (0 to -10px)
            const hoverBaseOffset = -5; // Base offset when hovering
            const hoverRange = 10; // Range when hovering (total: -5px to -15px)
            const transitionSpeed = 0.05; // Smooth transition speed (0-1)
            
            function animate() {
                const time = Date.now() * 0.001; // Convert to seconds
                const cycleTime = 3; // 3 second cycle
                
                // Smooth interpolation for hover offset
                currentHoverOffset += (targetHoverOffset - currentHoverOffset) * transitionSpeed;
                
                // Calculate base offset and range based on current hover state
                const currentBase = currentHoverOffset;
                const currentRange = isHovering ? hoverRange : floatRange;
                
                // Calculate float value with continuous animation
                const currentFloat = Math.sin(time * (2 * Math.PI / cycleTime)) * currentRange;
                
                // Total offset combines base (from hover) and float animation
                const totalOffset = currentBase + currentFloat;
                
                heroSection.style.transform = `translateY(${totalOffset}px)`;
                animationId = requestAnimationFrame(animate);
            }
            
            // Start animation
            animate();
            
            // Smooth hover transition
            heroSection.addEventListener('mouseenter', function() {
                isHovering = true;
                targetHoverOffset = hoverBaseOffset;
            });
            
            heroSection.addEventListener('mouseleave', function() {
                isHovering = false;
                targetHoverOffset = 0;
            });
        });
        
        // Carousel Swipe Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('heroCarousel');
            const carouselInner = document.getElementById('carouselInner');
            
            if (!carousel || !carouselInner) return;
            
            // Initialize carousel
            const carouselInstance = new bootstrap.Carousel(carousel, {
                interval: 3000,
                wrap: true
            });
            
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            let threshold = 50; // Minimum distance to trigger slide

            // Touch events
            carouselInner.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                // Pause carousel when user starts swiping
                carouselInstance.pause();
            }, { passive: true });

            carouselInner.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                currentX = e.touches[0].clientX;
            }, { passive: true });

            carouselInner.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                const diffX = startX - currentX;
                
                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        // Swipe left - next
                        carouselInstance.next();
                    } else {
                        // Swipe right - prev
                        carouselInstance.prev();
                    }
                } else {
                    // Resume carousel if swipe was too small
                    carouselInstance.cycle();
                }
                isDragging = false;
            }, { passive: true });

            // Mouse events for desktop
            carouselInner.addEventListener('mousedown', (e) => {
                startX = e.clientX;
                isDragging = true;
                carouselInstance.pause();
                e.preventDefault();
            });

            carouselInner.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                currentX = e.clientX;
            });

            carouselInner.addEventListener('mouseup', (e) => {
                if (!isDragging) return;
                const diffX = startX - currentX;
                
                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        // Drag left - next
                        carouselInstance.next();
                    } else {
                        // Drag right - prev
                        carouselInstance.prev();
                    }
                } else {
                    // Resume carousel if drag was too small
                    carouselInstance.cycle();
                }
                isDragging = false;
            });

            carouselInner.addEventListener('mouseleave', (e) => {
                if (isDragging) {
                    isDragging = false;
                }
                carouselInstance.cycle();
            });
            
            // Pause on hover, resume on leave
            carouselInner.addEventListener('mouseenter', () => {
                carouselInstance.pause();
            });
        });

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        }

        function switchToRegister() {
            const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            
            if (loginModal) {
                loginModal.hide();
            }
            
            setTimeout(() => {
                registerModal.show();
            }, 300);
        }

        function switchToLogin() {
            const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
            const forgotPasswordModal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            
            if (registerModal) {
                registerModal.hide();
            }
            if (forgotPasswordModal) {
                forgotPasswordModal.hide();
            }
            
            setTimeout(() => {
                loginModal.show();
            }, 300);
        }

        function switchToForgotPassword() {
            const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            const forgotPasswordModal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
            
            if (loginModal) {
                loginModal.hide();
            }
            
            setTimeout(() => {
                forgotPasswordModal.show();
            }, 300);
        }

        // Auto open modal if there are errors from login/register/forgot-password
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('open_modal') == 'login' || ($errors->any() && !session('open_modal')))
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif

            @if(session('open_modal') == 'register')
                const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            @endif

            @if(session('open_modal') == 'forgot-password')
                const forgotPasswordModal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
                forgotPasswordModal.show();
            @endif
        });
    </script>
</body>
</html>

