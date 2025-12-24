@extends('layouts.app')

@section('title', 'Dosen Dashboard - BeeMa')

@section('content')
<style>
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #ecf0f1;
    }

    .header-section h1 {
        color: #2c3e50;
        font-size: 28px;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        padding: 12px 24px;
        font-size: 14px;
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .schedule-table thead {
        background: #34495e;
        color: white;
    }

    .schedule-table th {
        padding: 15px;
        text-align: left;
        font-weight: bold;
    }

    .schedule-table td {
        padding: 15px;
        border-bottom: 1px solid #ecf0f1;
    }

    .schedule-table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-type {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        color: white;
        display: inline-block;
    }

    .badge-manual {
        background: #3498db;
    }

    .badge-request {
        background: #17a2b8;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        display: inline-block;
    }

    .badge-open {
        background: #d4edda;
        color: #155724;
    }

    .badge-closed {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-approved {
        background: #d4edda;
        color: #155724;
    }

    .badge-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .action-links {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .action-links a,
    .action-links form {
        display: inline;
    }

    .action-links button {
        padding: 5px 10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
    }

    .action-links button:hover {
        background: #c82333;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 10px;
        color: #7f8c8d;
    }

    .student-info {
        font-size: 13px;
    }

    .student-info strong {
        display: block;
        font-weight: bold;
    }

    .student-info small {
        color: #7f8c8d;
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
            text-align: center;
        }

        .schedule-table {
            font-size: 12px;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 10px;
        }
    }
</style>

<div class="header-section">
    <div>
        <h1>{{ app()->getLocale() === 'en' ? 'Hello' : 'Halo' }}, {{ Auth::user()->name }}</h1>
        <p style="color: #7f8c8d; margin: 5px 0 0 0;">{{ Auth::user()->email }}</p>
    </div>
    <div style="font-size: 14px; color: #7f8c8d;">
        {{ now()->format('d M Y') }}
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $activeSchedules }}</div>
        <div class="stat-label">{{ app()->getLocale() === 'en' ? 'Open Timeslots' : 'Slot Terbuka' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $totalBookings }}</div>
        <div class="stat-label">{{ app()->getLocale() === 'en' ? 'Pending Requests' : 'Permintaan Pending' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $pendingCounselingRequests }}</div>
        <div class="stat-label">{{ app()->getLocale() === 'en' ? 'Total Students' : 'Total Mahasiswa' }}</div>
    </div>
</div>

<div class="action-buttons">
    <a href="{{ route('dosen.schedules.create') }}" class="btn btn-primary">+ {{ app()->getLocale() === 'en' ? 'Add Schedule' : 'Tambah Jadwal' }}</a>
    <a href="{{ route('dosen.incoming-requests') }}" class="btn btn-secondary">{{ app()->getLocale() === 'en' ? 'View Booking Requests' : 'Lihat Daftar Pendaftar' }}</a>
    <a href="{{ route('dosen.counseling-requests') }}" class="btn btn-secondary">{{ app()->getLocale() === 'en' ? 'View Counseling Requests' : 'Lihat Permintaan Bimbingan' }}</a>
</div>

<div class="section-title">
    {{ app()->getLocale() === 'en' ? 'Your Schedules & Requests' : 'Jadwal & Permintaan Anda' }}
</div>

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

    <table class="schedule-table">
        <thead>
            <tr>
                <th>{{ app()->getLocale() === 'en' ? 'Type' : 'Tipe' }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Student / Info' : 'Mahasiswa / Info' }}</th>
                <th>{{ __('messages.date') }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</th>
                <th>{{ __('messages.location') }}</th>
                <th>{{ __('messages.quota') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allSchedules as $item)
                @if($item['type'] === 'manual')
                    @php $schedule = $item['data']; @endphp
                    <tr>
                        <td><span class="badge-type badge-manual">{{ app()->getLocale() === 'en' ? 'Manual' : 'Manual' }}</span></td>
                        <td>-</td>
                        <td>{{ $schedule->date->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td>{{ $schedule->location ?? '-' }}</td>
                        <td>{{ $schedule->quota }}</td>
                        <td>
                            @if($schedule->status === 'open')
                                <span class="badge-status badge-open">{{ __('messages.open') }}</span>
                            @else
                                <span class="badge-status badge-closed">{{ __('messages.closed') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('dosen.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                                <form method="POST" action="{{ route('dosen.schedules.destroy', $schedule->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ app()->getLocale() === 'en' ? 'Are you sure?' : 'Yakin ingin menghapus?' }}')">{{ __('messages.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @else
                    @php $req = $item['data']; @endphp
                    <tr style="background: #f0f8ff;">
                        <td><span class="badge-type badge-request">{{ app()->getLocale() === 'en' ? 'Request' : 'Request' }}</span></td>
                        <td>
                            <div class="student-info">
                                <strong>{{ $req->mahasiswa->name }}</strong>
                                <small>{{ $req->mahasiswa->email }}</small>
                            </div>
                        </td>
                        <td>{{ $req->date->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}</td>
                        <td>-</td>
                        <td>1</td>
                        <td>
                            @if($req->status === 'pending')
                                <span class="badge-status badge-pending">{{ __('messages.pending') }}</span>
                            @elseif($req->status === 'approved')
                                <span class="badge-status badge-approved">{{ __('messages.approved') }}</span>
                            @else
                                <span class="badge-status badge-rejected">{{ __('messages.rejected') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($req->file_path)
                                <a href="{{ route('dosen.counseling.download', $req->id) }}" class="btn btn-sm btn-primary">{{ __('messages.download') }}</a>
                            @else
                                <span style="color: #999; font-size: 12px;">{{ app()->getLocale() === 'en' ? 'No file' : 'Tidak ada file' }}</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty-state">
        <h3>{{ app()->getLocale() === 'en' ? 'No Schedules Yet' : 'Belum Ada Jadwal' }}</h3>
        <p>{{ app()->getLocale() === 'en' ? 'Create your first schedule to get started.' : 'Buat jadwal pertama Anda untuk memulai.' }}</p>
        <a href="{{ route('dosen.schedules.create') }}" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">+ {{ app()->getLocale() === 'en' ? 'Add Schedule' : 'Tambah Jadwal' }}</a>
    </div>
@endif

@endsection
