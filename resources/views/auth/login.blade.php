@extends('layouts.app')

@section('title', __('messages.login') . ' - BeeMa')

@section('content')
<style>
    .login-container {
        max-width: 500px;
        margin: 0 auto;
    }

    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .login-header h1 {
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

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #bdc3c7;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    .form-actions {
        display: flex;
        gap: 10px;
    }

    .btn-login {
        flex: 1;
        padding: 12px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-login:hover {
        background: #2980b9;
    }

    .register-link {
        text-align: center;
        margin-top: 20px;
    }

    .register-link a {
        color: #3498db;
        text-decoration: none;
        font-weight: bold;
    }

    .register-link a:hover {
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
        color: #3498db;
    }
</style>

<div class="login-container">
    <a href="{{ route('landing') }}" class="back-link">← {{ __('messages.back') }}</a>

    <div class="login-header">
        <h1>🐝 BeeMa</h1>
        <p>{{ __('messages.welcome') }} Back!</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('messages.email') }}</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="name@example.com"
                value="{{ old('email') }}"
                required
                autofocus
            >
            @error('email')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('messages.password') }}</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >
            @error('password')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-login">{{ __('messages.login') }}</button>
        </div>
    </form>

    <div class="register-link">
        {{ app()->getLocale() === 'en' ? "Don't have an account?" : 'Belum punya akun?' }}
        <a href="{{ route('register') }}">{{ __('messages.register') }} {{ app()->getLocale() === 'en' ? 'here' : 'di sini' }}</a>
    </div>
</div>

@endsection
