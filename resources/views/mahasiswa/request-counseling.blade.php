@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Request Counseling' : 'Permintaan Konsultasi') . ' - BeeMa')

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

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .form-group input[type="date"],
    .form-group input[type="time"],
    .form-group input[type="file"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 2px solid #ecf0f1;
        border-radius: 5px;
        font-family: inherit;
        font-size: 14px;
    }

    .form-group input[type="date"]:focus,
    .form-group input[type="time"]:focus,
    .form-group input[type="file"]:focus,
    .form-group select:focus,
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
        text-decoration: none;
        display: inline-block;
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
    <h1>{{ app()->getLocale() === 'en' ? 'Request Counseling' : 'Permintaan Konsultasi' }}</h1>

    @if($errors->any())
        <div class="alert-error">
            <strong>{{ app()->getLocale() === 'en' ? 'Please fix the following errors:' : 'Mohon perbaiki kesalahan berikut:' }}</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.counseling.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="dosen_id">{{ app()->getLocale() === 'en' ? 'Select Lecturer' : 'Pilih Dosen' }} <span class="required">*</span></label>
            <select name="dosen_id" id="dosen_id" required>
                <option value="">{{ app()->getLocale() === 'en' ? 'Choose a lecturer' : 'Pilih dosen' }}</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->id }}">{{ $dosen->name }} ({{ $dosen->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">{{ __('messages.date') }} <span class="required">*</span></label>
            <input type="date" name="date" id="date" required min="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group">
            <label for="start_time">{{ app()->getLocale() === 'en' ? 'Start Time' : 'Waktu Mulai' }} <span class="required">*</span></label>
            <input type="time" name="start_time" id="start_time" required>
        </div>

        <div class="form-group">
            <label for="end_time">{{ app()->getLocale() === 'en' ? 'End Time' : 'Waktu Akhir' }} <span class="required">*</span></label>
            <input type="time" name="end_time" id="end_time" required>
        </div>

        <div class="form-group">
            <label for="file">{{ __('messages.file') }} ({{ app()->getLocale() === 'en' ? 'Optional' : 'Opsional' }})</label>
            <input type="file" name="file" id="file" accept=".pdf,.docx,.doc">
            <p class="help-text">{{ app()->getLocale() === 'en' ? 'Upload a file (PDF, DOCX, or DOC) Maximum 10MB' : 'Upload file (PDF, DOCX, atau DOC) Maksimal 10MB' }}</p>
        </div>

        <div class="form-group">
            <label for="message">{{ __('messages.message') }} ({{ app()->getLocale() === 'en' ? 'Optional' : 'Opsional' }})</label>
            <textarea name="message" id="message" placeholder="{{ app()->getLocale() === 'en' ? 'Enter your message here...' : 'Masukkan pesan Anda di sini...' }}"></textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
        </div>
    </form>
</div>

@endsection
