@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Admin Dashboard' : 'Dashboard Admin') . ' - BeeMa')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 40px;
    }

    .dashboard-header h1 {
        color: #2c3e50;
        font-size: 32px;
        margin: 0 0 10px 0;
    }

    .dashboard-header p {
        color: #7f8c8d;
        font-size: 16px;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid #f39c12;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .stat-card.blue {
        border-left-color: #f39c12;
    }

    .stat-card.green {
        border-left-color: #27ae60;
    }

    .stat-card.purple {
        border-left-color: #9b59b6;
    }

    .stat-icon {
        font-size: 40px;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .management-section {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .management-section h2 {
        color: #2c3e50;
        font-size: 22px;
        margin-top: 0;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }

    .management-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .management-link {
        display: inline-block;
        padding: 15px 25px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
    }

    .management-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #2980b9 0%, #1f618d 100%);
    }

    .management-link.secondary {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
    }

    .management-link.secondary:hover {
        background: linear-gradient(135deg, #229954 0%, #1e8449 100%);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
    }

    @media (max-width: 768px) {
        .dashboard-header h1 {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .management-links {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-header">
    <h1>{{ app()->getLocale() === 'en' ? 'Admin Dashboard' : 'Dashboard Admin' }}</h1>
    <p>{{ app()->getLocale() === 'en' ? 'Welcome back! Here\'s an overview of your system.' : 'Selamat datang! Berikut adalah ikhtisar sistem Anda.' }}</p>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">👨‍🏫</div>
        <div class="stat-number">{{ $totalDosen ?? 0 }}</div>
        <div class="stat-label">{{ app()->getLocale() === 'en' ? 'Lecturers' : 'Dosen' }}</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">👨‍🎓</div>
        <div class="stat-number">{{ $totalMahasiswa ?? 0 }}</div>
        <div class="stat-label">{{ app()->getLocale() === 'en' ? 'Students' : 'Mahasiswa' }}</div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">📋</div>
        <div class="stat-number">{{ $totalAssignments ?? 0 }}</div>
        <div class="stat-label">{{ app()->getLocale() === 'en' ? 'Assignments' : 'Penugasan' }}</div>
    </div>
</div>

<div class="management-section">
    <h2>{{ app()->getLocale() === 'en' ? 'Management' : 'Manajemen' }}</h2>
    <div class="management-links">
        <a href="{{ route('admin.assignments.index') }}" class="management-link">
            {{ app()->getLocale() === 'en' ? '📋 Manage Assignments' : '📋 Kelola Penugasan' }}
        </a>
    </div>
</div>

@endsection
