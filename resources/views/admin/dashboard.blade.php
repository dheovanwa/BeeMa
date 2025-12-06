<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}!</p>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <h2>Statistics</h2>
    <p>Total Dosen: {{ $totalDosen }}</p>
    <p>Total Mahasiswa: {{ $totalMahasiswa }}</p>
    <p>Total Assignments: {{ $totalAssignments }}</p>

    <h2>Management</h2>
    <a href="{{ route('admin.assignments.index') }}">Manage Assignments</a>
</body>
</html>
