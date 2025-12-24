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

    .assignment-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .assignment-table thead {
        background: #34495e;
        color: white;
    }

    .assignment-table th {
        padding: 15px;
        text-align: left;
        font-weight: bold;
    }

    .assignment-table td {
        padding: 15px;
        border-bottom: 1px solid #ecf0f1;
    }

    .assignment-table tbody tr:hover {
        background: #f8f9fa;
    }

    .user-info {
        font-size: 13px;
    }

    .user-info strong {
        display: block;
        font-weight: bold;
    }

    .user-info small {
        color: #7f8c8d;
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

        .assignment-table {
            font-size: 12px;
        }

        .assignment-table th,
        .assignment-table td {
            padding: 10px;
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

@if($assignments->count() > 0)
    <table class="assignment-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ app()->getLocale() === 'en' ? 'Lecturer' : 'Dosen' }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Student' : 'Mahasiswa' }}</th>
                <th>{{ app()->getLocale() === 'en' ? 'Created' : 'Dibuat' }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td><strong>#{{ $assignment->id }}</strong></td>
                    <td>
                        <div class="user-info">
                            <strong>{{ $assignment->dosen->name }}</strong>
                            <small>{{ $assignment->dosen->email }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="user-info">
                            <strong>{{ $assignment->mahasiswa->name }}</strong>
                            <small>{{ $assignment->mahasiswa->email }}</small>
                        </div>
                    </td>
                    <td>{{ $assignment->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ app()->getLocale() === 'en' ? 'Are you sure?' : 'Anda yakin?' }}')">{{ __('messages.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty-state">
        <h2>{{ app()->getLocale() === 'en' ? 'No Assignments' : 'Tidak Ada Penugasan' }}</h2>
        <p>{{ app()->getLocale() === 'en' ? 'No assignments have been created yet. Create one to get started.' : 'Belum ada penugasan. Buat satu untuk memulai.' }}</p>
    </div>
@endif

@endsection
