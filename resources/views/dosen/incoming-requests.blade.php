<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pendaftar - BeeMa</title>
</head>
<body>
    <h1>Daftar Pendaftar (Incoming Requests)</h1>

    <div style="margin: 20px 0;">
        <a href="{{ route('dosen.dashboard') }}">&larr; Kembali ke Dashboard</a>
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

    <h2>Daftar Booking dari Mahasiswa</h2>

    @if($bookings->count() > 0)
        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Tanggal Jadwal</th>
                    <th>Waktu</th>
                    <th>Tanggal Booking</th>
                    <th>File</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->mahasiswa->name }}</td>
                    <td>{{ $booking->schedule->date->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}</td>
                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($booking->file_path)
                            <a href="{{ route('booking.download', $booking->id) }}" style="color: #007bff;">Download File</a>
                        @else
                            <span style="color: #999;">Tidak ada file</span>
                        @endif
                    </td>
                    <td>{{ $booking->message ?? '-' }}</td>
                    <td>
                        @if($booking->status === 'pending')
                            <span style="background: #ffc107; color: black; padding: 5px 10px;">Menunggu</span>
                        @elseif($booking->status === 'approved')
                            <span style="background: #28a745; color: white; padding: 5px 10px;">Disetujui</span>
                        @elseif($booking->status === 'rejected')
                            <span style="background: #dc3545; color: white; padding: 5px 10px;">Ditolak</span>
                        @else
                            <span style="background: #17a2b8; color: white; padding: 5px 10px;">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('dosen.booking.status', $booking->id) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" style="background: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer;">Setuju</button>
                            </form>
                            <button onclick="showRejectModal({{ $booking->id }})" style="background: #dc3545; color: white; padding: 5px 10px; border: none; cursor: pointer;">Tolak</button>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada booking dari mahasiswa.</p>
    @endif

    <!-- Rejection Modal -->
    <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 5px; min-width: 400px;">
            <h3>Alasan Penolakan</h3>
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                
                <div style="margin: 20px 0;">
                    <label for="rejection_reason">Masukkan alasan penolakan:</label><br>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required style="width: 100%; padding: 10px; margin-top: 10px;"></textarea>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-right: 10px;">Tolak Booking</button>
                    <button type="button" onclick="closeRejectModal()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; cursor: pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(bookingId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = '/dosen/booking/' + bookingId + '/status';
            modal.style.display = 'block';
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.style.display = 'none';
            document.getElementById('rejection_reason').value = '';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('rejectModal');
            if (event.target == modal) {
                closeRejectModal();
            }
        }
    </script>

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
