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
        color: #f39c12;
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
        border-color: #f39c12;
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

    .request-card-file-row {
        font-size: 13px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .request-card-file-info {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .request-card-file-label {
        color: #7f8c8d;
        font-weight: 600;
        display: block;
        margin-bottom: 3px;
        font-size: 12px;
    }

    .request-card-filename {
        color: #2c3e50;
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .request-card-file-icon {
        color: #f39c12;
        text-decoration: none;
        font-size: 18px;
        transition: color 0.3s;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
    }

    .request-card-file-icon:hover {
        color: #d68910;
    }

    .request-card-message {
        margin-bottom: 12px;
    }

    .request-card-message-label {
        color: #7f8c8d;
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
        font-size: 12px;
    }

    .request-card-message-box {
        background: #f8f9fa;
        border: 1px solid #ecf0f1;
        border-radius: 5px;
        padding: 10px;
        height: 60px;
        overflow-y: auto;
        overflow-x: hidden;
        font-size: 13px;
        color: #2c3e50;
        line-height: 1.5;
        word-wrap: break-word;
        white-space: normal;
        cursor: pointer;
        transition: all 0.3s;
    }

    .request-card-message-box:hover {
        background: #ecf0f1;
        border-color: #f39c12;
    }

    .message-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .message-modal-content {
        background: white;
        border-radius: 10px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .message-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        border-bottom: 2px solid #ecf0f1;
    }

    .message-modal-header h2 {
        margin: 0;
        color: #2c3e50;
        font-size: 20px;
    }

    .message-modal-close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
        transition: color 0.3s;
    }

    .message-modal-close:hover {
        color: #000;
    }

    .message-modal-body {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
        color: #2c3e50;
        font-size: 14px;
        line-height: 1.6;
        word-wrap: break-word;
        white-space: pre-wrap;
        height: 300px;
    }

    .message-modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .message-modal-body::-webkit-scrollbar-track {
        background: #ecf0f1;
        border-radius: 3px;
    }

    .message-modal-body::-webkit-scrollbar-thumb {
        background: #bdc3c7;
        border-radius: 3px;
    }

    .message-modal-body::-webkit-scrollbar-thumb:hover {
        background: #95a5a6;
    }

    .request-card-message-box::-webkit-scrollbar {
        width: 6px;
    }

    .request-card-message-box::-webkit-scrollbar-track {
        background: #ecf0f1;
        border-radius: 3px;
    }

    .request-card-message-box::-webkit-scrollbar-thumb {
        background: #bdc3c7;
        border-radius: 3px;
    }

    .request-card-message-box::-webkit-scrollbar-thumb:hover {
        background: #95a5a6;
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

    .request-actions-buttons {
        display: flex;
        gap: 10px;
        flex: 1;
        width: 100%;
    }

    .request-actions-buttons form,
    .request-actions-buttons button {
        flex: 1;
    }

    .request-actions-buttons form button {
        width: 100%;
        padding: 8px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 12px;
        transition: all 0.3s;
    }

    .request-actions-buttons button {
        padding: 8px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 12px;
        transition: all 0.3s;
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
        background: #f39c12;
        color: white;
    }

    .btn-download:hover {
        background: #d68910;
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
        background: #f39c12;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .empty-state a:hover {
        background: #d68910;
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
                <div class="request-card-message">
                    <span class="request-card-message-label">💬 {{ __('messages.message') }}</span>
                    <div class="request-card-message-box" onclick="openMessageModal(this.dataset.message)" data-message="{{ json_encode($booking->message) }}">{{ $booking->message }}</div>
                </div>
                @endif

                @if($booking->file_path)
                    <div class="request-card-file-row">
                        <div class="request-card-file-info">
                            <span class="request-card-file-label">{{ __('messages.file') }}</span>
                            <span class="request-card-filename" title="{{ basename($booking->file_path) }}">{{ basename($booking->file_path) }}</span>
                        </div>
                        <a href="{{ route('booking.download', $booking->id) }}" class="request-card-file-icon" title="{{ __('messages.download') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                            </svg>
                        </a>
                    </div>
                @endif

                <div class="request-actions">
                    <div class="request-actions-buttons">
                        <form method="POST" action="{{ route('dosen.booking.status', $booking->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn-action btn-accept">{{ __('messages.accept') }}</button>
                        </form>
                        <button type="button" class="btn-action btn-reject" onclick="openRejectModal({{ $booking->id }})">{{ __('messages.reject') }}</button>
                    </div>
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

<!-- Message Modal -->
<div id="messageModal" class="message-modal">
    <div class="message-modal-content">
        <div class="message-modal-header">
            <h2>{{ app()->getLocale() === 'en' ? 'Full Message' : 'Pesan Lengkap' }}</h2>
            <button class="message-modal-close" onclick="closeMessageModal()">&times;</button>
        </div>
        <div class="message-modal-body" id="messageModalBody"></div>
    </div>
</div>

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

    // Close rejection modal when clicking outside of it
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    // Message Modal Functions
    function openMessageModal(message) {
        try {
            const parsedMessage = typeof message === 'string' ? JSON.parse(message) : message;
            document.getElementById('messageModal').style.display = 'flex';
            document.getElementById('messageModalBody').textContent = parsedMessage;
        } catch (e) {
            console.error('Error parsing message:', e);
            document.getElementById('messageModal').style.display = 'flex';
            document.getElementById('messageModalBody').textContent = message;
        }
    }

    function closeMessageModal() {
        document.getElementById('messageModal').style.display = 'none';
        document.getElementById('messageModalBody').textContent = '';
    }

    // Close message modal when clicking outside of it
    document.getElementById('messageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMessageModal();
        }
    });
</script>

@endsection
