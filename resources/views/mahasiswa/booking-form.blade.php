@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Book Schedule' : 'Booking Jadwal') . ' - BeeMa')

@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .form-container h1 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f39c12;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #f39c12;
        text-decoration: none;
        font-weight: bold;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-error ul {
        margin: 10px 0 0 20px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        color: #2c3e50;
        font-size: 18px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ecf0f1;
    }

    .schedule-details {
        background: #f8f9fa;
        border: 2px solid #ecf0f1;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .detail-item {
        margin-bottom: 12px;
        display: flex;
        gap: 10px;
    }

    .detail-item label {
        font-weight: bold;
        color: #2c3e50;
        min-width: 100px;
    }

    .detail-item value {
        color: #555;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .form-group input[type="file"],
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 2px solid #ecf0f1;
        border-radius: 5px;
        font-family: inherit;
        font-size: 14px;
    }

    .form-group input[type="file"]:focus,
    .form-group textarea:focus {
        border-color: #f39c12;
        outline: none;
        box-shadow: 0 0 5px rgba(243, 156, 18, 0.3);
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .help-text {
        color: #7f8c8d;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        justify-content: flex-end;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #f39c12;
        color: white;
    }

    .btn-primary:hover {
        background: #d68910;
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
    }

    .required {
        color: #dc3545;
    }
</style>

<a href="{{ route('mahasiswa.dashboard') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="form-container">
    <h1>{{ app()->getLocale() === 'en' ? 'Book a Schedule' : 'Booking Jadwal' }}</h1>

    @if ($errors->any())
        <div class="alert-error">
            <strong>{{ app()->getLocale() === 'en' ? 'Please fix the following errors:' : 'Mohon perbaiki kesalahan berikut:' }}</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-section">
        <h3>{{ app()->getLocale() === 'en' ? 'Schedule Details' : 'Detail Jadwal' }}</h3>
        <div class="schedule-details">
            <div class="detail-item">
                <label>👨‍🏫 {{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}:</label>
                <value>{{ $schedule->dosen->name }}</value>
            </div>
            <div class="detail-item">
                <label>📅 {{ __('messages.date') }}:</label>
                <value>{{ $schedule->date->format('d M Y') }}</value>
            </div>
            <div class="detail-item">
                <label>⏰ {{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}:</label>
                <value>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</value>
            </div>
            <div class="detail-item">
                <label>📍 {{ __('messages.location') }}:</label>
                <value>{{ $schedule->location ?? '-' }}</value>
            </div>
            <div class="detail-item">
                <label>👥 {{ __('messages.quota') }}:</label>
                <value>{{ $schedule->bookings->where('status', 'approved')->count() }} / {{ $schedule->quota }}</value>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('mahasiswa.booking.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

        <div class="form-section">
            <h3>{{ app()->getLocale() === 'en' ? 'Booking Details' : 'Detail Booking' }}</h3>

            <div class="form-group">
                <label>{{ app()->getLocale() === 'en' ? 'Upload File' : 'Upload File' }} <span class="required">*</span></label>
                <input type="file" name="file" required accept=".pdf,.docx,.doc">
                <p class="help-text">{{ app()->getLocale() === 'en' ? 'Upload a file (PDF, DOCX, or DOC) Maximum 10MB' : 'Upload file (PDF, DOCX, atau DOC) Maksimal 10MB' }}</p>
            </div>

            <div class="form-group">
                <label>{{ app()->getLocale() === 'en' ? 'Message' : 'Pesan' }} ({{ app()->getLocale() === 'en' ? 'Optional' : 'Opsional' }})</label>
                <textarea name="message" placeholder="{{ app()->getLocale() === 'en' ? 'Add message or notes for the lecturer...' : 'Tambahkan pesan atau catatan untuk dosen...' }}">{{ old('message') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
        </div>
    </form>
</div>

@endsection
