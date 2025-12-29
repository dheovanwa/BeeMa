@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Incoming Requests' : 'Permintaan Masuk') . ' - BeeMa')

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

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #3498db;
        text-decoration: none;
        font-weight: bold;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .request-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .request-card {
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .request-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-color: #3498db;
    }

    .request-card-student {
        font-size: 16px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ecf0f1;
    }

    .request-card-info {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
    }

    .request-card-info label {
        font-weight: bold;
        color: #34495e;
        min-width: 80px;
    }

    .request-card-info value {
        color: #7f8c8d;
    }

    .badge-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .request-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #ecf0f1;
    }

    .btn-action {
        flex: 1;
        padding: 8px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 12px;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .btn-accept {
        background: #27ae60;
        color: white;
    }

    .btn-accept:hover {
        background: #229954;
    }

    .btn-reject {
        background: #e74c3c;
        color: white;
    }

    .btn-reject:hover {
        background: #c0392b;
    }

    .btn-download {
        background: #3498db;
        color: white;
    }

    .btn-download:hover {
        background: #2980b9;
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

    .empty-state a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .empty-state a:hover {
        background: #2980b9;
    }

    @media (max-width: 768px) {
        .request-cards {
            grid-template-columns: 1fr;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
    }
</style>

<a href="{{ route('dosen.dashboard') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="header-section">
    <div>
        <h1>{{ app()->getLocale() === 'en' ? 'Incoming Requests' : 'Permintaan Masuk' }}</h1>
        <p style="color: #7f8c8d; margin: 5px 0 0 0;">{{ app()->getLocale() === 'en' ? 'Booking requests from your students' : 'Permintaan booking dari mahasiswa Anda' }}</p>
    </div>
</div>

@if($bookings->count() > 0)
    <div class="request-cards">
        @foreach($bookings as $booking)
            <div class="request-card">
                <div class="request-card-student">{{ $booking->mahasiswa->name }}</div>

                <span class="badge-status badge-pending">{{ __('messages.pending') }}</span>

                <div class="request-card-info">
                    <label>📅 {{ __('messages.date') }}:</label>
                    <value>{{ $booking->schedule->date->format('d M Y') }}</value>
                </div>

                <div class="request-card-info">
                    <label>⏰ {{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}:</label>
                    <value>{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }}</value>
                </div>

                <div class="request-card-info">
                    <label>📍 {{ __('messages.location') }}:</label>
                    <value>{{ $booking->schedule->location ?? '-' }}</value>
                </div>

                <div class="request-card-info">
                    <label>📋 {{ app()->getLocale() === 'en' ? 'Booking Date' : 'Tanggal Booking' }}:</label>
                    <value>{{ $booking->created_at->format('d M Y') }}</value>
                </div>

                @if($booking->message)
                <div class="request-card-info">
                    <label>💬 {{ __('messages.message') }}:</label>
                    <value>{{ Str::limit($booking->message, 50) }}</value>
                </div>
                @endif

                <div class="request-actions">
                    @if($booking->file_path)
                        <a href="{{ route('booking.download', $booking->id) }}" class="btn-action btn-download">{{ __('messages.download') }}</a>
                    @endif
                    <form method="POST" action="{{ route('dosen.booking.status', $booking->id) }}" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn-action btn-accept">{{ __('messages.accept') }}</button>
                    </form>
                    <button type="button" class="btn-action btn-reject" onclick="openRejectModal({{ $booking->id }})">{{ __('messages.reject') }}</button>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <h2>{{ app()->getLocale() === 'en' ? 'No Incoming Requests' : 'Tidak Ada Permintaan Masuk' }}</h2>
        <p>{{ app()->getLocale() === 'en' ? 'Your students will submit booking requests here.' : 'Mahasiswa Anda akan mengirimkan permintaan booking di sini.' }}</p>
        <a href="{{ route('dosen.dashboard') }}">{{ app()->getLocale() === 'en' ? 'Back to Dashboard' : 'Kembali ke Dashboard' }}</a>
    </div>
@endif

<!-- Rejection Reason Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
        <h2 style="color: #2c3e50; margin-top: 0; font-size: 20px;">{{ app()->getLocale() === 'en' ? 'Rejection Reason' : 'Alasan Penolakan' }}</h2>
        <p style="color: #7f8c8d; margin-bottom: 15px;">{{ app()->getLocale() === 'en' ? 'Please provide a reason for rejecting this booking request.' : 'Silakan berikan alasan untuk menolak permintaan booking ini.' }}</p>

        <form method="POST" id="rejectForm" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" id="bookingIdInput" name="booking_id">

            <textarea
                name="rejection_reason"
                id="rejectionReason"
                placeholder="{{ app()->getLocale() === 'en' ? 'Enter your rejection reason...' : 'Masukkan alasan penolakan Anda...' }}"
                style="padding: 12px; border: 1px solid #bdc3c7; border-radius: 5px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 120px;"
                required
            ></textarea>

            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeRejectModal()" style="flex: 1; padding: 10px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: all 0.3s;">{{ app()->getLocale() === 'en' ? 'Cancel' : 'Batal' }}</button>
                <button type="submit" style="flex: 1; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: all 0.3s;">{{ app()->getLocale() === 'en' ? 'Reject' : 'Tolak' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(bookingId) {
        document.getElementById('rejectModal').style.display = 'flex';
        document.getElementById('bookingIdInput').value = bookingId;
        document.getElementById('rejectionReason').focus();
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        document.getElementById('rejectionReason').value = '';
    }

    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const bookingId = document.getElementById('bookingIdInput').value;
        const action = "{{ route('dosen.booking.status', ':id') }}".replace(':id', bookingId);

        this.action = action;
        this.submit();
    });

    // Close modal when clicking outside of it
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>

@endsection
