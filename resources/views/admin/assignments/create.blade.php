@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Create Assignment' : 'Buat Penugasan') . ' - BeeMa')

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
        border-bottom: 2px solid #3498db;
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
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .search-container {
        position: relative;
        margin-bottom: 10px;
    }

    .search-input {
        width: 100%;
        padding: 10px;
        border: 2px solid #ecf0f1;
        border-radius: 5px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .search-input:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    .select-box {
        width: 100%;
        height: 150px;
        padding: 10px;
        border: 2px solid #ecf0f1;
        border-radius: 5px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .select-box:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
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
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
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

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<a href="{{ route('admin.assignments.index') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="form-container">
    <h1>{{ app()->getLocale() === 'en' ? 'Create New Assignment' : 'Buat Penugasan Baru' }}</h1>

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

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.assignments.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="dosen_id">{{ app()->getLocale() === 'en' ? 'Select Lecturer' : 'Pilih Dosen' }} <span class="required">*</span></label>
            <div class="search-container">
                <input type="text" id="dosen_search" class="search-input" placeholder="{{ app()->getLocale() === 'en' ? 'Search by name or email...' : 'Cari berdasarkan nama atau email...' }}">
            </div>
            <p class="help-text">{{ app()->getLocale() === 'en' ? 'Type to filter the list of lecturers' : 'Ketik untuk memfilter daftar dosen' }}</p>
            <select name="dosen_id" id="dosen_id" class="select-box" size="5" required>
                <option value="">{{ app()->getLocale() === 'en' ? 'Select Lecturer' : 'Pilih Dosen' }}</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->id }}" data-name="{{ strtolower($dosen->name) }}" data-email="{{ strtolower($dosen->email) }}">
                        {{ $dosen->name }} ({{ $dosen->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="mahasiswa_id">{{ app()->getLocale() === 'en' ? 'Select Student' : 'Pilih Mahasiswa' }} <span class="required">*</span></label>
            <div class="search-container">
                <input type="text" id="mahasiswa_search" class="search-input" placeholder="{{ app()->getLocale() === 'en' ? 'Search by name or email...' : 'Cari berdasarkan nama atau email...' }}">
            </div>
            <p class="help-text">{{ app()->getLocale() === 'en' ? 'Type to filter the list of students' : 'Ketik untuk memfilter daftar mahasiswa' }}</p>
            <select name="mahasiswa_id" id="mahasiswa_id" class="select-box" size="5" required>
                <option value="">{{ app()->getLocale() === 'en' ? 'Select Student' : 'Pilih Mahasiswa' }}</option>
                @foreach($mahasiswas as $mahasiswa)
                    <option value="{{ $mahasiswa->id }}" data-name="{{ strtolower($mahasiswa->name) }}" data-email="{{ strtolower($mahasiswa->email) }}">
                        {{ $mahasiswa->name }} ({{ $mahasiswa->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ app()->getLocale() === 'en' ? 'Create Assignment' : 'Buat Penugasan' }}</button>
        </div>
    </form>
</div>

<script>
    // Search functionality for Dosen
    document.getElementById('dosen_search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const select = document.getElementById('dosen_id');
        const options = select.querySelectorAll('option');

        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }

            const name = option.getAttribute('data-name') || '';
            const email = option.getAttribute('data-email') || '';

            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Search functionality for Mahasiswa
    document.getElementById('mahasiswa_search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const select = document.getElementById('mahasiswa_id');
        const options = select.querySelectorAll('option');

        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }

            const name = option.getAttribute('data-name') || '';
            const email = option.getAttribute('data-email') || '';

            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });
</script>

@endsection
