<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Bimbingan - BeeMa</title>
</head>
<body>
    <h1>Riwayat Bimbingan Saya</h1>

    <div style="margin: 20px 0;">
        <a href="{{ route('mahasiswa.dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <h2>My Bookings</h2>

    @if($bookings->count() > 0)
        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Dosen</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>File</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Alasan Penolakan</th>
                    <th>Tanggal Booking</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->schedule->dosen->name }}</td>
                    <td>{{ $booking->schedule->date->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}</td>
                    <td>
                        @if($booking->file_path)
                            <a href="{{ route('booking.download', $booking->id) }}" style="color: #007bff;">Download</a>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>{{ $booking->message ?? '-' }}</td>
                    <td>
                        @if($booking->status === 'pending')
                            <span style="background: #ffc107; color: black; padding: 5px 10px; font-weight: bold;">Menunggu</span>
                        @elseif($booking->status === 'approved')
                            <span style="background: #28a745; color: white; padding: 5px 10px; font-weight: bold;">Disetujui</span>
                        @elseif($booking->status === 'rejected')
                            <span style="background: #dc3545; color: white; padding: 5px 10px; font-weight: bold;">Ditolak</span>
                        @elseif($booking->status === 'completed')
                            <span style="background: #17a2b8; color: white; padding: 5px 10px; font-weight: bold;">Selesai</span>
                        @else
                            <span style="background: #6c757d; color: white; padding: 5px 10px;">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->status === 'rejected' && $booking->rejection_reason)
                            <span style="color: #dc3545;">{{ $booking->rejection_reason }}</span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Anda belum memiliki booking.</p>
    @endif

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
