<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeeMa - Booking Meeting Application</title>
</head>
<body>
    <div style="text-align: center; padding: 50px;">
        <h1>Welcome to BeeMa</h1>
        <h2>Booking Meeting Application</h2>
        <p>Platform untuk manajemen bimbingan antara Dosen dan Mahasiswa</p>

        <div style="margin: 40px 0;">
            <a href="{{ route('login') }}" style="display: inline-block; background: #007bff; color: white; padding: 15px 40px; text-decoration: none; font-size: 18px; margin: 10px; border-radius: 5px;">
                Login
            </a>
            <a href="{{ route('register') }}" style="display: inline-block; background: #28a745; color: white; padding: 15px 40px; text-decoration: none; font-size: 18px; margin: 10px; border-radius: 5px;">
                Register
            </a>
        </div>

        <div style="margin-top: 50px;">
            <h3>Language / Bahasa</h3>
            <a href="{{ route('lang.switch', 'en') }}" style="margin: 0 10px; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 3px;">English</a>
            <a href="{{ route('lang.switch', 'id') }}" style="margin: 0 10px; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 3px;">Bahasa Indonesia</a>
        </div>
    </div>
</body>
</html>
