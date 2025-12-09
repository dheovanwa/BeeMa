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
        <p>Email: {{ Auth::user()->email }}</p>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <hr>

    <h2>Ringkasan</h2>
    <div>
        <p><strong>Jumlah Slot Aktif:</strong> {{ $activeSchedules }}</p>
        <p><strong>Jumlah Mahasiswa Mendaftar:</strong> {{ $totalBookings }}</p>
        <p><strong>Permintaan Bimbingan Pending:</strong> {{ $pendingCounselingRequests }}</p>
    </div>

    <hr>

    <h2>Manajemen Jadwal</h2>
    
    <div style="margin: 20px 0;">
        <a href="{{ route('dosen.schedules.create') }}" style="display: inline-block; background: #007bff; color: white; padding: 15px 30px; text-decoration: none; font-size: 18px; font-weight: bold;">
            + Tambah Jadwal
        </a>
    </div>

    <div style="margin: 20px 0;">
        <a href="{{ route('dosen.incoming-requests') }}" style="display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; margin-right: 10px;">
            Lihat Daftar Pendaftar
        </a>
        <a href="{{ route('dosen.counseling-requests') }}" style="display: inline-block; background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none;">
            Lihat Permintaan Bimbingan
        </a>
    </div>

    <h3>Daftar Jadwal</h3>
    
    @if($schedules->count() > 0 || $counselingRequests->count() > 0)
        @php
            // Combine and sort all schedules by date (nearest first)
            $allSchedules = collect();
            
            foreach($schedules as $schedule) {
                $allSchedules->push([
                    'type' => 'manual',
                    'date' => $schedule->date,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'data' => $schedule
                ]);
            }
            
            foreach($counselingRequests as $request) {
                $allSchedules->push([
                    'type' => 'request',
                    'date' => $request->date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'data' => $request
                ]);
            }
            
            // Sort by date (ascending) then by start_time (ascending)
            $allSchedules = $allSchedules->sortBy([
                ['date', 'asc'],
                ['start_time', 'asc']
            ]);
        @endphp
        
        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Mahasiswa/Info</th>
                    <th>Tanggal</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Lokasi</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allSchedules as $item)
                    @if($item['type'] === 'manual')
                        @php $schedule = $item['data']; @endphp
                        <tr>
                            <td><span style="background: #007bff; color: white; padding: 5px 10px;">Jadwal Manual</span></td>
                            <td>-</td>
                            <td>{{ $schedule->date->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                            <td>{{ $schedule->location ?? '-' }}</td>
                            <td>{{ $schedule->quota }}</td>
                            <td>
                                @if($schedule->status === 'open')
                                    <span style="background: #28a745; color: white; padding: 5px 10px;">Buka</span>
                                @else
                                    <span style="background: #dc3545; color: white; padding: 5px 10px;">Tutup</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dosen.schedules.edit', $schedule->id) }}" style="background: #ffc107; color: black; padding: 5px 10px; text-decoration: none;">Edit</a>
                                <form method="POST" action="{{ route('dosen.schedules.destroy', $schedule->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" style="background: #dc3545; color: white; padding: 5px 10px; border: none; cursor: pointer;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @else
                        @php $request = $item['data']; @endphp
                        <tr style="background: #f0f8ff;">
                            <td><span style="background: #17a2b8; color: white; padding: 5px 10px;">Request Bimbingan</span></td>
                            <td>
                                <strong>{{ $request->mahasiswa->name }}</strong><br>
                                <small>{{ $request->mahasiswa->email }}</small>
                            </td>
                            <td>{{ $request->date->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->start_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->end_time)->format('H:i') }}</td>
                            <td>-</td>
                            <td>1</td>
                            <td>
                                @if($request->status === 'pending')
                                    <span style="background: #ffc107; color: black; padding: 5px 10px;">Menunggu</span>
                                @elseif($request->status === 'approved')
                                    <span style="background: #28a745; color: white; padding: 5px 10px;">Disetujui</span>
                                @else
                                    <span style="background: #dc3545; color: white; padding: 5px 10px;">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($request->file_path)
                                    <a href="{{ route('dosen.counseling.download', $request->id) }}" style="background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none;">Download File</a>
                                @else
                                    <span style="color: #999;">No file</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada jadwal yang dibuat atau disetujui.</p>
    @endif

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
