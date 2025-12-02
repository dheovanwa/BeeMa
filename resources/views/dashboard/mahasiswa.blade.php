<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Dashboard - BeeMa</title>
</head>
<body>
    <h1>Mahasiswa Dashboard</h1>

    <div>
        <p>Welcome, {{ Auth::user()->name }}!</p>
        <p>Email: {{ Auth::user()->email }}</p>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <hr>

    <div style="margin: 20px 0;">
        <a href="{{ route('mahasiswa.my-bookings') }}" style="display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none;">
            Lihat Riwayat Bimbingan Saya
        </a>
    </div>

    <hr>

    <h2>Cari Jadwal Bimbingan</h2>

    <form method="GET" action="{{ route('mahasiswa.dashboard') }}" style="margin: 20px 0;">
        <div style="margin: 10px 0;">
            <label for="dosen_name">Nama Dosen:</label><br>
            <input type="text" id="dosen_name" name="dosen_name" value="{{ request('dosen_name') }}" placeholder="Cari nama dosen..." style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="date">Tanggal:</label><br>
            <input type="date" id="date" name="date" value="{{ request('date') }}" style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">Cari</button>
            <a href="{{ route('mahasiswa.dashboard') }}" style="display: inline-block; background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; margin-left: 10px;">Reset</a>
        </div>
    </form>

    <hr>

    <h2>Jadwal yang Tersedia</h2>

    @if($schedules->count() > 0)
        <div style="display: grid; grid-gap: 20px;">
            @foreach($schedules as $schedule)
                <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9;">
                    <h3>{{ $schedule->dosen->name }}</h3>
                    <p><strong>Tanggal:</strong> {{ $schedule->date->format('d/m/Y') }}</p>
                    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    <p><strong>Kuota:</strong> {{ $schedule->quota }}</p>
                    <p><strong>Status:</strong> 
                        @if($schedule->status === 'open')
                            <span style="background: #28a745; color: white; padding: 5px 10px;">Buka</span>
                        @else
                            <span style="background: #dc3545; color: white; padding: 5px 10px;">Tutup</span>
                        @endif
                    </p>
                    
                    @if($schedule->status === 'open')
                        <a href="{{ route('mahasiswa.booking.form', $schedule->id) }}" style="display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; margin-top: 10px;">
                            Booking
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p>Tidak ada jadwal yang tersedia saat ini.</p>
    @endif

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
