@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard - BeeMa')

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

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
        margin-top: 40px;
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .schedule-card {
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .schedule-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-color: #3498db;
        transform: translateY(-3px);
    }

    .schedule-card-dosen {
        font-size: 16px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ecf0f1;
    }

    .schedule-card-info {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
    }

    .schedule-card-info label {
        font-weight: bold;
        color: #34495e;
        min-width: 70px;
    }

    .schedule-card-info value {
        color: #7f8c8d;
    }

    .schedule-card-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .status-open {
        background: #d4edda;
        color: #155724;
    }

    .status-closed {
        background: #f8d7da;
        color: #721c24;
    }

    .schedule-card-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #ecf0f1;
    }

    .btn-book {
        flex: 1;
        padding: 10px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s;
        display: block;
    }

    .btn-book:hover {
        background: #2980b9;
    }

    .btn-book:disabled {
        background: #95a5a6;
        cursor: not-allowed;
    }

    .empty-state {
        text-align: center;
        padding: 60px 40px;
        background: #f8f9fa;
        border-radius: 10px;
        color: #7f8c8d;
    }

    .empty-state h2 {
        color: #2c3e50;
        margin-bottom: 15px;
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

        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="header-section">
    <div>
        <h1>{{ app()->getLocale() === 'en' ? 'Hello' : 'Halo' }}, {{ Auth::user()->name }}</h1>
        <p style="color: #7f8c8d; margin: 5px 0 0 0;">{{ __('messages.mahasiswa') }} - Semester 5</p>
    </div>
    <div style="font-size: 14px; color: #7f8c8d;">
        {{ now()->format('d M Y') }}
    </div>
</div>

<div class="action-buttons">
    <a href="{{ route('mahasiswa.my-bookings') }}" class="btn btn-primary">{{ app()->getLocale() === 'en' ? 'My Bookings' : 'Booking Saya' }}</a>
    <a href="{{ route('mahasiswa.counseling.create') }}" class="btn btn-secondary">{{ app()->getLocale() === 'en' ? 'Request Counseling' : 'Minta Konseling' }}</a>
</div>

<div class="section-title">
    {{ app()->getLocale() === 'en' ? 'Available Schedules' : 'Jadwal yang Tersedia' }}
</div>

@if($schedules->count() > 0)
    <div class="schedule-grid">
        @foreach($schedules as $schedule)
            <div class="schedule-card">
                <div class="schedule-card-dosen">{{ $schedule->dosen->name }}</div>

                <div class="schedule-card-status {{ $schedule->status === 'open' ? 'status-open' : 'status-closed' }}">
                    {{ $schedule->status === 'open' ? __('messages.open') : __('messages.closed') }}
                </div>

                <div class="schedule-card-info">
                    <label>📅 {{ __('messages.date') }}:</label>
                    <value>{{ $schedule->date->format('d M Y') }}</value>
                </div>

                <div class="schedule-card-info">
                    <label>⏰ {{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}:</label>
                    <value>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</value>
                </div>

                <div class="schedule-card-info">
                    <label>📍 {{ __('messages.location') }}:</label>
                    <value>{{ $schedule->location ?? '-' }}</value>
                </div>

                <div class="schedule-card-info">
                    <label>👥 {{ __('messages.quota') }}:</label>
                    <value>{{ $schedule->bookings->where('status', 'approved')->count() }} / {{ $schedule->quota }}</value>
                </div>

                <div class="schedule-card-actions">
                    @if($schedule->status === 'open')
                        <button onclick="openBookingModal({{ $schedule->id }}, '{{ $schedule->dosen->name }}', '{{ $schedule->date->format('d M Y') }}', '{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}')" class="btn-book">
                            {{ app()->getLocale() === 'en' ? 'Book Now' : 'Booking Sekarang' }}
                        </button>
                    @else
                        <button class="btn-book" disabled>{{ __('messages.closed') }}</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <h2>{{ app()->getLocale() === 'en' ? 'No Available Schedules' : 'Tidak Ada Jadwal Tersedia' }}</h2>
        <p>{{ app()->getLocale() === 'en' ? 'Check back soon or contact your assigned lecturer.' : 'Periksa kembali nanti atau hubungi dosen Anda.' }}</p>
    </div>
@endif

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>{{ app()->getLocale() === 'en' ? 'Book a Schedule' : 'Booking Jadwal' }}</h2>
            <button type="button" class="close-btn" onclick="closeBookingModal()">&times;</button>
        </div>

        <form method="POST" action="{{ route('mahasiswa.booking.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" id="schedule_id" name="schedule_id">

            <div class="form-group">
                <label>{{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}</label>
                <input type="text" id="dosen_name" readonly style="background: #f8f9fa; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label>{{ __('messages.date') }} & {{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</label>
                <input type="text" id="schedule_info" readonly style="background: #f8f9fa; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label for="file">{{ __('messages.upload') }} {{ app()->getLocale() === 'en' ? 'File (PDF)' : 'File (PDF)' }} <span style="color: #e74c3c;">*</span></label>
                <div style="border: 2px dashed #3498db; border-radius: 5px; padding: 20px; text-align: center; cursor: pointer; transition: background 0.3s;" id="dropZone">
                    <p style="margin: 0; color: #7f8c8d;">{{ app()->getLocale() === 'en' ? 'Drag & drop your PDF here or click to browse' : 'Seret & lepas file PDF atau klik untuk memilih' }}</p>
                    <input type="file" id="file" name="file" accept=".pdf" style="display: none;">
                </div>
                <div id="fileName" style="margin-top: 10px; color: #27ae60; font-weight: bold;"></div>
                @error('file')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="message">{{ __('messages.message') }} ({{ app()->getLocale() === 'en' ? 'Optional' : 'Opsional' }})</label>
                <textarea id="message" name="message" rows="4" placeholder="{{ app()->getLocale() === 'en' ? 'Add any notes about your meeting...' : 'Tambahkan catatan tentang pertemuan Anda...' }}"></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeBookingModal()" class="btn btn-secondary" style="flex: 1;">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">{{ __('messages.submit') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');
    const fileName = document.getElementById('fileName');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.style.background = '#ecf0f1';
    }

    function unhighlight(e) {
        dropZone.style.background = 'white';
    }

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileName();
    }

    // Handle file input change
    fileInput.addEventListener('change', updateFileName);

    function updateFileName() {
        if (fileInput.files.length > 0) {
            fileName.textContent = '✓ ' + fileInput.files[0].name;
        } else {
            fileName.textContent = '';
        }
    }

    // Click to browse
    dropZone.addEventListener('click', () => fileInput.click());

    function openBookingModal(scheduleId, dosenName, date, time) {
        document.getElementById('schedule_id').value = scheduleId;
        document.getElementById('dosen_name').value = dosenName;
        document.getElementById('schedule_info').value = date + ' | ' + time;
        document.getElementById('bookingModal').classList.add('show');
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.remove('show');
        document.getElementById('file').value = '';
        document.getElementById('message').value = '';
        fileName.textContent = '';
    }

    // Close modal when clicking outside
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });
</script>

@endsection
