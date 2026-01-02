@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Manage Assignments' : 'Kelola Penugasan') . ' - BeeMa')

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

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    .btn-success {
        background: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background: #229954;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
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

    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border: 1px solid #ecf0f1;
    }

    .filter-section h3 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        font-size: 16px;
        font-weight: bold;
    }

    .filter-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr auto auto;
        gap: 10px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 13px;
    }

    .filter-group input {
        padding: 8px 12px;
        border: 1px solid #ecf0f1;
        border-radius: 5px;
        font-size: 13px;
        transition: border-color 0.3s;
    }

    .filter-group input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.2);
    }

    .filter-buttons {
        display: flex;
        gap: 8px;
    }

    .filter-buttons .btn {
        padding: 8px 16px;
        font-size: 13px;
    }

    .btn-filter {
        background: #3498db;
        color: white;
    }

    .btn-filter:hover {
        background: #2980b9;
    }

    .btn-reset {
        background: #95a5a6;
        color: white;
    }

    .btn-reset:hover {
        background: #7f8c8d;
    }

    .assignment-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .assignment-card {
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border-left: 5px solid #3498db;
    }

    .assignment-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        border-color: #3498db;
    }

    .assignment-id {
        background: #ecf0f1;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        color: #2c3e50;
        display: inline-block;
        margin-bottom: 15px;
    }

    .assignment-section {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ecf0f1;
    }

    .assignment-section:last-of-type {
        border-bottom: none;
    }

    .assignment-label {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .user-info {
        font-size: 13px;
    }

    .user-info strong {
        display: block;
        font-weight: bold;
        color: #2c3e50;
    }

    .user-info small {
        color: #7f8c8d;
        display: block;
        margin-top: 3px;
    }

    .assignment-meta {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 8px;
    }

    .assignment-footer {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #ecf0f1;
    }

    .assignment-footer form {
        flex: 1;
    }

    .assignment-footer button {
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

    .assignment-footer button:hover {
        background: #c82333;
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

    .no-results {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 8px;
        color: #7f8c8d;
        margin-top: 20px;
    }

    .no-results h3 {
        color: #2c3e50;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .action-buttons {
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
            text-align: center;
        }

        .filter-inputs {
            grid-template-columns: 1fr;
        }

        .filter-buttons {
            width: 100%;
            flex-direction: column;
        }

        .filter-buttons .btn {
            width: 100%;
        }
    }
</style>

<a href="{{ route('admin.dashboard') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="header-section">
    <div>
        <h1>{{ app()->getLocale() === 'en' ? 'Manage Assignments' : 'Kelola Penugasan' }}</h1>
        <p style="color: #7f8c8d; margin: 5px 0 0 0;">{{ app()->getLocale() === 'en' ? 'Assign lecturers to students' : 'Tetapkan dosen untuk mahasiswa' }}</p>
    </div>
    <div class="action-buttons">
        <a href="{{ route('admin.assignments.create') }}" class="btn btn-success">+ {{ app()->getLocale() === 'en' ? 'Create New Assignment' : 'Buat Penugasan Baru' }}</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="filter-section">
    <h3>{{ app()->getLocale() === 'en' ? 'Filter Assignments' : 'Filter Penugasan' }}</h3>
    <form method="GET" action="{{ route('admin.assignments.index') }}">
        <div class="filter-inputs">
            <div class="filter-group">
                <label for="lecturer">{{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}</label>
                <input type="text" id="lecturer" name="lecturer" value="{{ request('lecturer') }}" placeholder="{{ app()->getLocale() === 'en' ? 'Search by name or email' : 'Cari berdasarkan nama atau email' }}">
            </div>

            <div class="filter-group">
                <label for="student">{{ app()->getLocale() === 'en' ? 'Student' : 'Mahasiswa' }}</label>
                <input type="text" id="student" name="student" value="{{ request('student') }}" placeholder="{{ app()->getLocale() === 'en' ? 'Search by name or email' : 'Cari berdasarkan nama atau email' }}">
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn btn-filter">{{ __('messages.search') }}</button>
                <a href="{{ route('admin.assignments.index') }}" class="btn btn-reset">{{ __('messages.reset') }}</a>
            </div>
        </div>
    </form>
</div>

@if($assignments->count() > 0)
    <div class="assignment-cards">
        @foreach($assignments as $assignment)
            <div class="assignment-card">
                <div class="assignment-id">#{{ $assignment->id }}</div>

                <div class="assignment-section">
                    <span class="assignment-label">👨‍🏫 {{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}</span>
                    <div class="user-info">
                        <strong>{{ $assignment->dosen->name }}</strong>
                        <small>{{ $assignment->dosen->email }}</small>
                    </div>
                </div>

                <div class="assignment-section">
                    <span class="assignment-label">👨‍🎓 {{ app()->getLocale() === 'en' ? 'Student' : 'Mahasiswa' }}</span>
                    <div class="user-info">
                        <strong>{{ $assignment->mahasiswa->name }}</strong>
                        <small>{{ $assignment->mahasiswa->email }}</small>
                    </div>
                </div>

                <div class="assignment-meta">
                    <span class="assignment-label">📅 {{ app()->getLocale() === 'en' ? 'Created' : 'Dibuat' }}</span>
                    {{ $assignment->created_at->format('d M Y H:i') }}
                </div>

                <div class="assignment-footer">
                    <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('{{ app()->getLocale() === 'en' ? 'Are you sure?' : 'Anda yakin?' }}')">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    @if(request('lecturer') || request('student'))
        <div class="no-results">
            <h3>{{ app()->getLocale() === 'en' ? 'No Assignments Found' : 'Tidak Ada Penugasan Ditemukan' }}</h3>
            <p>{{ app()->getLocale() === 'en' ? 'Try adjusting your filters to find what you\'re looking for.' : 'Coba sesuaikan filter Anda untuk menemukan apa yang Anda cari.' }}</p>
        </div>
    @else
        <div class="empty-state">
            <h2>{{ app()->getLocale() === 'en' ? 'No Assignments' : 'Tidak Ada Penugasan' }}</h2>
            <p>{{ app()->getLocale() === 'en' ? 'No assignments have been created yet. Create one to get started.' : 'Belum ada penugasan. Buat satu untuk memulai.' }}</p>
        </div>
    @endif
@endif

@endsection
