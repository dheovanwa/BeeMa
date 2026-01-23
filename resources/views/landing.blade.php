<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMa - Portal Manajemen Bimbingan & Konsultasi</title>
    @vite(['resources/css/beema.css'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(116.82deg, #FFF2DF 0%, #F09F30 100%);
            min-height: 100vh;
            min-width: 100vg;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .left-section {
            background: linear-gradient(180deg, #F5B964 0%, #F8A22A 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .logo {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .tagline {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .features {
            text-align: left;
            margin-bottom: 40px;
        }

        .feature-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-icon {
            font-size: 20px;
        }

        .right-section {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .lang-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .lang-btn {
            padding: 8px 16px;
            background: #ecf0f1;
            border: 2px solid #bdc3c7;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            color: #34495e;
        }

        .lang-btn.active {
            background: #f39c12;
            color: white;
            border-color: #f39c12;
        }

        .lang-btn:hover {
            border-color: #f39c12;
            color: #f39c12;
        }

        .lang-btn.active:hover {
            background: #d68910;
        }

        h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-primary {
            background: #f39c12;
            color: white;
        }

        .btn-primary:hover {
            background: #d68910;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
        }

        .btn-secondary {
            background: #ecf0f1;
            color: #34495e;
            border: 2px solid #f39c12;
        }

        .btn-secondary:hover {
            background: #f39c12;
            color: white;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #95a5a6;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            display: inline-block;
            width: 40%;
            height: 1px;
            background: #ecf0f1;
            vertical-align: middle;
            margin: 0 10px;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .left-section {
                padding: 40px 20px;
            }

            .right-section {
                padding: 40px 20px;
            }

            .logo {
                font-size: 36px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">🐝 BeeMa</div>
            <p class="tagline">{{ __('messages.welcome') }} {{ app()->getLocale() === 'en' ? 'to' : 'ke' }} BeeMa</p>
            <p class="tagline">
                {{ app()->getLocale() === 'en'
                    ? 'Your all-in-one hub for effortless consultation scheduling. Easily track sessions and submit consultation requests with your supervisor.'
                    : 'Portal terpadu Anda untuk penjadwalan konsultasi yang mudah. Lacak sesi dan ajukan permintaan konsultasi dengan dosen Anda dengan lancar.' }}
            </p>
            <div class="features">
                <div class="feature-item">
                    <span class="feature-icon">📅</span>
                    <span>{{ app()->getLocale() === 'en' ? 'Easy Schedule Management' : 'Manajemen Jadwal Mudah' }}</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📁</span>
                    <span>{{ app()->getLocale() === 'en' ? 'File Management' : 'Manajemen File' }}</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">✅</span>
                    <span>{{ app()->getLocale() === 'en' ? 'Quick Approvals' : 'Persetujuan Cepat' }}</span>
                </div>
            </div>
        </div>

        <div class="right-section">
            <div class="lang-toggle">
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
            </div>

            <h1>{{ __('messages.welcome') }}</h1>

            <div class="button-group">
                <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.login') }}</a>
                <div class="divider">{{ app()->getLocale() === 'en' ? 'or' : 'atau' }}</div>
                <a href="{{ route('register') }}" class="btn btn-secondary">{{ __('messages.register') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
