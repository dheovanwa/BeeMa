<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BeeMa')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    @auth
    <header>
        <div class="logo">BeeMa</div>
        <div class="nav-right">
            <div class="lang-switcher">
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
            </div>
            <span>{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">{{ __('messages.logout') }}</button>
            </form>
        </div>
    </header>
    @endauth

    <div class="container">
        @if (session('success'))
            <div class="alert success">
                <strong>{{ __('messages.success') }}</strong>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert error">
                <strong>{{ __('messages.error') }}</strong>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert error">
                <strong>{{ __('messages.errors') }}</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
