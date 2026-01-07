@extends('layouts.app')

@section('title', __('messages.register') . ' - BeeMa')

@section('content')
<style>
    .register-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .register-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .register-header h1 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #2c3e50;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #bdc3c7;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #f39c12;
        box-shadow: 0 0 5px rgba(243, 156, 18, 0.3);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-actions {
        display: flex;
        gap: 10px;
    }

    .btn-register {
        flex: 1;
        padding: 12px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-register:hover {
        background: #229954;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
    }

    .login-link a {
        color: #f39c12;
        text-decoration: none;
        font-weight: bold;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #95a5a6;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link:hover {
        color: #f39c12;
    }
</style>

<div class="register-container">
    <a href="{{ route('landing') }}" class="back-link">← {{ __('messages.back') }}</a>

    <div class="register-header">
        <h1>{{ __('messages.register') }} {{ __('messages.account') ?? 'Account' }}</h1>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">{{ __('messages.name') }}</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="Full name"
                value="{{ old('name') }}"
                required
            >
            @error('name')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">{{ __('messages.email') }}</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="name@example.com"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">{{ __('messages.password') }}</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Min. 8 characters"
                    required
                >
                @error('password')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('messages.confirm_password') }}</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm password"
                    required
                >
                @error('password_confirmation')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="role">{{ __('messages.role') }}</label>
            <select id="role" name="role" required>
                <option value="">{{ app()->getLocale() === 'en' ? 'Select Your Role' : 'Pilih Peran Anda' }}</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>{{ __('messages.dosen') }}</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>{{ __('messages.mahasiswa') }}</option>
            </select>
            @error('role')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-register">{{ __('messages.register') }}</button>
        </div>
    </form>

    <div class="login-link">
        {{ app()->getLocale() === 'en' ? 'Already have an account?' : 'Sudah punya akun?' }}
        <a href="{{ route('login') }}">{{ __('messages.login') }} {{ app()->getLocale() === 'en' ? 'here' : 'di sini' }}</a>
    </div>
</div>

@endsection
