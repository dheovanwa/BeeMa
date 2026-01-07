@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Counseling Requests' : 'Permintaan Konsultasi') . ' - BeeMa')

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

    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .request-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .request-table thead {
        background: #34495e;
        color: white;
    }

    .request-table th {
        padding: 15px;
        text-align: left;
        font-weight: bold;
    }

    .request-table td {
        padding: 15px;
        border-bottom: 1px solid #ecf0f1;
    }

    .request-table tbody tr:hover {
        background: #f8f9fa;
    }

    .request-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .request-card {
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border-left: 5px solid #17a2b8;
    }

    .request-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        border-color: #17a2b8;
    }

    .request-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ecf0f1;
        gap: 10px;
    }

    .request-card-student {
        flex: 1;
    }

    .request-card-student strong {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .request-card-student small {
        color: #7f8c8d;
        display: block;
    }

    .request-card-status {
        font-size: 12px;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        white-space: nowrap;
    }

    .request-card-body {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 15px;
    }

    .request-card-row {
        font-size: 13px;
    }

    .request-card-file-row {
        font-size: 13px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .request-card-file-info {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .request-card-label {
        color: #7f8c8d;
        font-weight: 600;
        display: block;
        margin-bottom: 3px;
    }

    .request-card-value {
        color: #2c3e50;
        font-weight: 500;
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
        color: #3498db;
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
        color: #2980b9;
    }

    .request-card-footer {
        display: flex;
        gap: 8px;
        padding-top: 15px;
        border-top: 1px solid #ecf0f1;
    }

    .request-card-actions {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .request-card-actions form,
    .request-card-actions button {
        flex: 1;
    }

    .request-card-actions form button {
        width: 100%;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .request-card-actions button {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .request-card-footer .btn-approve {
        background: #27ae60;
        color: white;
    }

    .request-card-footer .btn-approve:hover {
        background: #229954;
    }

    .request-card-footer .btn-reject {
        background: #e74c3c;
        color: white;
    }

    .request-card-footer .btn-reject:hover {
        background: #c0392b;
    }

    .request-card-footer .btn-download {
        background: #3498db;
        color: white;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: bold;
    }

    .request-card-footer .btn-download:hover {
        background: #2980b9;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
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

    .action-btn {
        display: inline-block;
        padding: 8px 12px;
        margin-right: 5px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-approve {
        background: #28a745;
        color: white;
    }

    .btn-approve:hover {
        background: #218838;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background: #c82333;
    }

    .btn-download {
        background: #3498db;
        color: white;
        text-decoration: none;
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

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 50px auto;
        padding: 30px;
        border: 1px solid #888;
        border-radius: 5px;
        width: 80%;
        max-width: 500px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }

    .modal-header h3 {
        margin: 0;
        color: #2c3e50;
    }

    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
    }

    .close:hover {
        color: #000;
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

    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 2px solid #ecf0f1;
        border-radius: 5px;
        font-family: inherit;
        font-size: 14px;
        min-height: 120px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    @media (max-width: 768px) {
        .request-table {
            font-size: 12px;
        }

        .request-table th,
        .request-table td {
            padding: 10px;
        }
    }
</style>

<a href="{{ route('dosen.dashboard') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="header-section">
    <div>
        <h1>{{ app()->getLocale() === 'en' ? 'Counseling Requests' : 'Permintaan Konsultasi' }}</h1>
        <p style="color: #7f8c8d; margin: 5px 0 0 0;">{{ app()->getLocale() === 'en' ? 'Manage student counseling requests' : 'Kelola permintaan konsultasi mahasiswa' }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

@if($requests->count() > 0)
    <div class="request-cards">
        @foreach($requests as $req)
            <div class="request-card">
                <div class="request-card-header">
                    <div class="request-card-student">
                        <strong>{{ $req->mahasiswa->name }}</strong>
                        <small>{{ $req->mahasiswa->email }}</small>
                    </div>
                    @if($req->status === 'pending')
                        <span class="request-card-status badge badge-pending">{{ __('messages.pending') }}</span>
                    @elseif($req->status === 'approved')
                        <span class="request-card-status badge badge-approved">{{ __('messages.accepted') }}</span>
                    @else
                        <span class="request-card-status badge badge-rejected">{{ __('messages.rejected') }}</span>
                    @endif
                </div>

                <div class="request-card-body">
                    <div class="request-card-row">
                        <span class="request-card-label">{{ __('messages.date') }} & {{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</span>
                        <span class="request-card-value">
                            {{ $req->date->format('d M Y') }} | {{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}
                        </span>
                    </div>

                    <div class="request-card-row">
                        <span class="request-card-label">{{ __('messages.message') }}</span>
                        <span class="request-card-value">{{ $req->message ?? '-' }}</span>
                    </div>

                    @if($req->file_path)
                        <div class="request-card-file-row">
                            <div class="request-card-file-info">
                                <span class="request-card-label">{{ __('messages.file') }}</span>
                                <span class="request-card-filename" title="{{ basename($req->file_path) }}">{{ basename($req->file_path) }}</span>
                            </div>
                            <a href="{{ route('dosen.counseling.download', $req->id) }}" class="request-card-file-icon" title="{{ __('messages.download') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                            </a>
                        </div>
                    @endif

                    <div class="request-card-row">
                        <span class="request-card-label">{{ app()->getLocale() === 'en' ? 'Requested' : 'Permintaan' }}</span>
                        <span class="request-card-value">{{ $req->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="request-card-footer">
                    @if($req->status === 'pending')
                        <div class="request-card-actions">
                            <form action="{{ route('dosen.counseling.update-status', $req->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn-approve">{{ __('messages.accept') }}</button>
                            </form>

                            <button onclick="openRejectModal({{ $req->id }})" class="btn-reject">{{ __('messages.reject') }}</button>
                        </div>
                    @else
                        <span style="color: #999; width: 100%;">-</span>
                    @endif
                </div>

                <!-- Reject Modal -->
                <div id="rejectModal{{ $req->id }}" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>{{ app()->getLocale() === 'en' ? 'Reject Request' : 'Tolak Permintaan' }}</h3>
                            <button class="close" onclick="closeRejectModal({{ $req->id }})">&times;</button>
                        </div>
                        <form action="{{ route('dosen.counseling.update-status', $req->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <div class="form-group">
                                <label for="rejection_reason{{ $req->id }}">{{ app()->getLocale() === 'en' ? 'Rejection Reason' : 'Alasan Penolakan' }} <span style="color: #dc3545;">*</span></label>
                                <textarea name="rejection_reason" id="rejection_reason{{ $req->id }}" required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" onclick="closeRejectModal({{ $req->id }})" class="action-btn" style="background: #95a5a6; color: white;">{{ __('messages.cancel') }}</button>
                                <button type="submit" class="action-btn btn-reject">{{ __('messages.reject') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <h2>{{ app()->getLocale() === 'en' ? 'No Counseling Requests' : 'Tidak Ada Permintaan Konsultasi' }}</h2>
        <p>{{ app()->getLocale() === 'en' ? 'Students will submit counseling requests here.' : 'Mahasiswa akan mengirimkan permintaan konsultasi di sini.' }}</p>
    </div>
@endif

<script>
    function openRejectModal(requestId) {
        document.getElementById('rejectModal' + requestId).style.display = 'block';
    }

    function closeRejectModal(requestId) {
        document.getElementById('rejectModal' + requestId).style.display = 'none';
    }

    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
</script>

@endsection
