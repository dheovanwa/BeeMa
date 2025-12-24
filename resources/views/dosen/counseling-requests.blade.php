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
    <table class="request-table">
        <thead>
            <tr>
                <th>{{ app()->getLocale() === 'en' ? 'Student' : 'Mahasiswa' }}</th>
                <th>{{ __('messages.date') }} & {{ app()->getLocale() === 'en' ? 'Time' : 'Waktu' }}</th>
                <th>{{ __('messages.message') }}</th>
                <th>{{ __('messages.file') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Requested' : 'Permintaan' }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td>
                        <div class="student-info">
                            <strong>{{ $req->mahasiswa->name }}</strong>
                            <small>{{ $req->mahasiswa->email }}</small>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $req->date->format('d M Y') }}</strong><br>
                        {{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}
                    </td>
                    <td>{{ Str::limit($req->message ?? '-', 30) }}</td>
                    <td>
                        @if($req->file_path)
                            <a href="{{ route('dosen.counseling.download', $req->id) }}" class="action-btn btn-download">{{ __('messages.download') }}</a>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($req->status === 'pending')
                            <span class="badge badge-pending">{{ __('messages.pending') }}</span>
                        @elseif($req->status === 'approved')
                            <span class="badge badge-approved">{{ __('messages.accepted') }}</span>
                        @else
                            <span class="badge badge-rejected">{{ __('messages.rejected') }}</span>
                        @endif
                    </td>
                    <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($req->status === 'pending')
                            <form action="{{ route('dosen.counseling.update-status', $req->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="action-btn btn-approve">{{ __('messages.accept') }}</button>
                            </form>

                            <button onclick="openRejectModal({{ $req->id }})" class="action-btn btn-reject">{{ __('messages.reject') }}</button>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>

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
            @endforeach
        </tbody>
    </table>
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
