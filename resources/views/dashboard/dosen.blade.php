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
        background: linear-gradient(135deg, #f9a825 0%, #e8860a 100%);
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
        border-bottom: 2px solid #f39c12;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .schedule-table thead {
        background: #b8860b;
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

    .schedule-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .schedule-card {
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }

    .schedule-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        border-color: #f39c12;
    }

    .schedule-card.request-card {
        border-left: 5px solid #f39c12;
        background: #f8f9fa;
    }

    .schedule-card.manual-card {
        border-left: 5px solid #f39c12;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ecf0f1;
    }

    .card-type-badge {
        font-weight: bold;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
    }

    .card-type-badge.manual {
        background: #f39c12;
    }

    .card-type-badge.request {
        background: #f39c12;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 15px;
    }

    .card-row {
        font-size: 13px;
    }

    .card-label {
        color: #7f8c8d;
        font-weight: 600;
        display: block;
        margin-bottom: 3px;
    }

    .card-value {
        color: #2c3e50;
        font-weight: 500;
    }

    .card-student {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        font-size: 13px;
    }

    .card-student strong {
        display: block;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .card-student small {
        color: #7f8c8d;
    }

    .card-footer {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        padding-top: 15px;
        border-top: 1px solid #ecf0f1;
    }

    .card-footer a,
    .card-footer form {
        flex: 1;
        min-width: 80px;
    }

    .card-footer button {
        width: 100%;
        padding: 8px 12px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .card-footer button:hover {
        background: #c82333;
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

    <div class="schedule-cards">
        @foreach($allSchedules as $item)
            @if($item['type'] === 'manual')
                @php $schedule = $item['data']; @endphp
                <div class="schedule-card manual-card">
                    <div class="card-header">
                        <span class="card-type-badge manual">{{ app()->getLocale() === 'en' ? 'Manual' : 'Manual' }}</span>
                        @if($schedule->status === 'open')
                            <span class="badge-status badge-open">{{ __('messages.open') }}</span>
                        @else
                            <span class="badge-status badge-closed">{{ __('messages.closed') }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-row">
                            <span class="card-label">{{ __('messages.date') }}</span>
                            <span class="card-value">{{ $schedule->date->format('d M Y') }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">{{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</span>
                            <span class="card-value">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">{{ __('messages.location') }}</span>
                            <span class="card-value">{{ $schedule->location ?? '-' }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">{{ __('messages.quota') }}</span>
                            <span class="card-value">{{ $schedule->bookings->where('status', 'approved')->count() }} / {{ $schedule->quota  }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dosen.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning" style="padding: 8px 12px; text-align: center; text-decoration: none; background: #ffc107; color: white; border-radius: 5px; font-size: 12px; font-weight: bold;">{{ __('messages.edit') }}</a>
                        <form method="POST" action="{{ route('dosen.schedules.destroy', $schedule->id) }}" style="flex: 1; min-width: 80px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('{{ app()->getLocale() === 'en' ? 'Are you sure?' : 'Yakin ingin menghapus?' }}')">{{ __('messages.delete') }}</button>
                        </form>
                    </div>
                </div>
            @else
                @php $req = $item['data']; @endphp
                <div class="schedule-card request-card">
                    <div class="card-header">
                        <span class="card-type-badge request">{{ app()->getLocale() === 'en' ? 'Request' : 'Request' }}</span>
                        @if($req->status === 'pending')
                            <span class="badge-status badge-pending">{{ __('messages.pending') }}</span>
                        @elseif($req->status === 'approved')
                            <span class="badge-status badge-approved">{{ __('messages.approved') }}</span>
                        @else
                            <span class="badge-status badge-rejected">{{ __('messages.rejected') }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-student">
                            <strong>{{ $req->mahasiswa->name }}</strong>
                            <small>{{ $req->mahasiswa->email }}</small>
                        </div>
                        <div class="card-row">
                            <span class="card-label">{{ __('messages.date') }}</span>
                            <span class="card-value">{{ $req->date->format('d M Y') }}</span>
                        </div>
                        <div class="card-row">
                            <span class="card-label">{{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</span>
                            <span class="card-value">{{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($req->file_path)
                            <a href="{{ route('dosen.counseling.download', $req->id) }}" class="btn btn-sm btn-primary" style="padding: 8px 12px; text-align: center; text-decoration: none; background: #3498db; color: white; border-radius: 5px; font-size: 12px; font-weight: bold;">{{ __('messages.download') }}</a>
                        @else
                            <span style="color: #999; font-size: 12px; padding: 8px 12px;">{{ app()->getLocale() === 'en' ? 'No file' : 'Tidak ada file' }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <div class="empty-state">
        <h3>{{ app()->getLocale() === 'en' ? 'No Schedules Yet' : 'Belum Ada Jadwal' }}</h3>
        <p>{{ app()->getLocale() === 'en' ? 'Create your first schedule to get started.' : 'Buat jadwal pertama Anda untuk memulai.' }}</p>
        <a href="{{ route('dosen.schedules.create') }}" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">+ {{ app()->getLocale() === 'en' ? 'Add Schedule' : 'Tambah Jadwal' }}</a>
    </div>
@endif

@endsection
