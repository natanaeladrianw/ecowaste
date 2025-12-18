@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div>
    <h2 class="text-center mb-4 fw-bold">Reset Password</h2>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <div class="position-relative">
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" style="padding-right: 50px !important;" />
                <button type="button" 
                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                        style="border: none; background: none; z-index: 20 !important; cursor: pointer; color: #6c757d; padding: 0 15px; height: 100%; display: flex; align-items: center; pointer-events: auto;"
                        onclick="togglePassword('password')">
                    <i class="bi bi-eye" id="password-eye" style="font-size: 1.1rem;"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="position-relative">
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" style="padding-right: 50px !important;" />
                <button type="button" 
                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y" 
                        style="border: none; background: none; z-index: 20 !important; cursor: pointer; color: #6c757d; padding: 0 15px; height: 100%; display: flex; align-items: center; pointer-events: auto;"
                        onclick="togglePassword('password_confirmation')">
                    <i class="bi bi-eye" id="password_confirmation-eye" style="font-size: 1.1rem;"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success">
                Reset Password
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection

