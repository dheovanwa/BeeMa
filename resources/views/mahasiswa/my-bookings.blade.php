@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'My Bookings' : 'Booking Saya') . ' - BeeMa')

@section('content')
<style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</style>

<a href="{{ route('mahasiswa.dashboard') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="header-section">
    <div>
        <h1>{{ app()->getLocale() === 'en' ? 'My Bookings' : 'Booking Saya' }}</h1>
        <p style="color: #7f8c8d; margin: 5px 0 0 0;">{{ app()->getLocale() === 'en' ? 'History of your consultation requests' : 'Riwayat permintaan konsultasi Anda' }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('mahasiswa.dashboard') }}" style="background: #f39c12; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block; transition: all 0.3s;" onmouseover="this.style.background='#d68910'" onmouseout="this.style.background='#f39c12'">
            📅 {{ app()->getLocale() === 'en' ? 'Book Schedule' : 'Booking Jadwal' }}
        </a>
        <a href="{{ route('mahasiswa.counseling.create') }}" style="background: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block; transition: all 0.3s;" onmouseover="this.style.background='#229954'" onmouseout="this.style.background='#27ae60'">
            💬 {{ app()->getLocale() === 'en' ? 'Request Counseling' : 'Minta Konsultasi' }}
        </a>
    </div>
</div>

<div class="section-title">
    {{ app()->getLocale() === 'en' ? 'Upcoming & Past Bookings' : 'Booking Mendatang & Terdahulu' }}
</div>

@if($bookings->count() > 0)
    <table class="booking-table">
        <thead>
            <tr>
                <th>{{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}</th>
                <th>{{ __('messages.date') }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</th>
                <th>{{ __('messages.location') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>
                        <div class="lecturer-info">
                            <strong>{{ $booking->schedule->dosen->name }}</strong>
                            <small>{{ $booking->schedule->dosen->email }}</small>
                        </div>
                    </td>
                    <td>{{ $booking->schedule->date->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}</td>
                    <td>{{ $booking->schedule->location ?? '-' }}</td>
                    <td>
                        @if($booking->status === 'pending')
                            <span class="badge-status badge-pending">{{ __('messages.pending') }}</span>
                        @elseif($booking->status === 'approved')
                            <span class="badge-status badge-accepted">{{ __('messages.accepted') }}</span>
                        @else
                            <span class="badge-status badge-rejected">{{ __('messages.rejected') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->file_path)
                            <a href="{{ route('booking.download', $booking->id) }}" class="action-link">{{ __('messages.download') }}</a>
                        @else
                            <span style="color: #999; font-size: 12px;">{{ app()->getLocale() === 'en' ? 'No file' : 'Tidak ada file' }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty-state">
        <h2>{{ app()->getLocale() === 'en' ? 'No Bookings Yet' : 'Belum Ada Booking' }}</h2>
        <p>{{ app()->getLocale() === 'en' ? 'Start by booking a schedule from your dashboard.' : 'Mulai dengan booking jadwal dari dashboard Anda.' }}</p>
        <a href="{{ route('mahasiswa.dashboard') }}">{{ app()->getLocale() === 'en' ? 'Browse Schedules' : 'Jelajahi Jadwal' }}</a>
    </div>
@endif

<div class="section-title">
    {{ app()->getLocale() === 'en' ? 'My Counseling Requests' : 'Permintaan Konsultasi Saya' }}
</div>

@if($counselingRequests->count() > 0)
    <table class="booking-table">
        <thead>
            <tr>
                <th>{{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}</th>
                <th>{{ __('messages.date') }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</th>
                <th>{{ __('messages.location') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($counselingRequests as $request)
                <tr>
                    <td>
                        <div class="lecturer-info">
                            <strong>{{ $request->dosen->name }}</strong>
                            <small>{{ $request->dosen->email }}</small>
                        </div>
                    </td>
                    <td>{{ $request->date->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($request->end_time)->format('H:i') }}</td>
                    <td>{{ $request->location ?? '-' }}</td>
                    <td>
                        @if($request->status === 'pending')
                            <span class="badge-status badge-pending">{{ __('messages.pending') }}</span>
                        @elseif($request->status === 'approved')
                            <span class="badge-status badge-accepted">{{ __('messages.accepted') }}</span>
                        @else
                            <span class="badge-status badge-rejected">{{ __('messages.rejected') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($request->file_path)
                            <a href="{{ route('mahasiswa.counseling.download', $request->id) }}" class="action-link">{{ __('messages.download') }}</a>
                        @else
                            <span style="color: #999; font-size: 12px;">{{ app()->getLocale() === 'en' ? 'No file' : 'Tidak ada file' }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty-state">
        <h2>{{ app()->getLocale() === 'en' ? 'No Requests Yet' : 'Belum Ada Permintaan' }}</h2>
        <p>{{ app()->getLocale() === 'en' ? 'Create a counseling request from your dashboard.' : 'Buat permintaan konsultasi dari dashboard Anda.' }}</p>
    </div>
@endif

@endsection
