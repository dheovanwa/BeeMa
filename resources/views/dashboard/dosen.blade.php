<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dosen Dashboard - BeeMa</title>
</head>
<body>
    <h1>Dosen Dashboard</h1>

    <div>
        <p>Welcome, {{ Auth::user()->name }}!</p>
        <p>Role: {{ ucfirst(Auth::user()->role) }}</p>
        <p>Email: {{ Auth::user()->email }}</p>
    </div>

    <div>
        <h2>Your Information</h2>
        <p>As a Dosen, you can manage your schedules here.</p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
