@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Create Assignment' : 'Buat Penugasan') . ' - BeeMa')

@section('content')
<style>
    .page-header {
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #ecf0f1;
    }

    .page-header h1 {
        color: #2c3e50;
        font-size: 32px;
        margin: 0 0 10px 0;
    }

    .page-header p {
        color: #7f8c8d;
        font-size: 14px;
        margin: 0;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #3498db;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 30px;
    }

    .form-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #ecf0f1;
    }

    .form-card h2 {
        color: #2c3e50;
        font-size: 18px;
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #3498db;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card h2::before {
        font-size: 22px;
    }

    .dosen-card h2::before {
        content: '👨‍🏫';
    }

    .mahasiswa-card h2::before {
        content: '👨‍🎓';
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        margin-bottom: 20px;
        grid-column: 1 / -1;
    }

    .alert-error ul {
        margin: 10px 0 0 20px;
    }

    .alert-error li {
        margin-bottom: 5px;
    }

    .search-container {
        position: relative;
        margin-bottom: 15px;
    }

    .search-input {
        width: 100%;
        padding: 12px 40px 12px 15px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s;
        background: #f8f9fa;
    }

    .search-input::placeholder {
        color: #95a5a6;
    }

    .search-input:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
        background: white;
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #95a5a6;
        pointer-events: none;
        font-size: 16px;
    }

    .help-text {
        color: #7f8c8d;
        font-size: 12px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .select-box {
        width: 100%;
        height: 180px;
        padding: 12px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        transition: all 0.3s;
        background: white;
    }

    .select-box:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
    }

    .select-box option {
        padding: 12px 10px;
        background: white;
        color: #2c3e50;
        line-height: 1.8;
        border-radius: 4px;
        margin-bottom: 2px;
    }

    .select-box option:hover {
        background: linear-gradient(#3498db, #3498db);
        color: white;
    }

    .select-box option:checked {
        background: linear-gradient(#3498db, #3498db);
        background-color: #3498db;
        color: white;
        font-weight: bold;
    }

    /* Custom styled list appearance */
    .custom-select-wrapper {
        position: relative;
        margin-top: 12px;
    }

    .select-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 280px;
        overflow-y: auto;
        padding: 8px;
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .select-list:focus-within {
        border-color: #3498db;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
    }

    .select-item {
        display: flex;
        align-items: center;
        padding: 14px 12px;
        background: #f8f9fa;
        border: 2px solid #ecf0f1;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }

    .select-item:hover {
        background: #e8f4f8;
        border-color: #3498db;
        transform: translateX(4px);
    }

    .select-item input[type="checkbox"],
    .select-item input[type="radio"] {
        width: 24px;
        height: 24px;
        min-width: 24px;
        margin-right: 16px;
        margin-left: -8px;
        cursor: pointer;
        accent-color: #3498db;
        flex-shrink: 0;
    }

    .select-item-text {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
        text-align: center;
    }

    .select-item-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 15px;
        line-height: 1.4;
    }

    .select-item-email {
        font-size: 13px;
        color: #7f8c8d;
        line-height: 1.3;
    }

    .select-item input[type="checkbox"]:checked ~ .select-item-text .select-item-name,
    .select-item input[type="radio"]:checked ~ .select-item-text .select-item-name {
        color: #3498db;
    }

    .selection-preview {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        border: 2px dashed #ecf0f1;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .preview-label {
        font-size: 12px;
        color: #95a5a6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .preview-item {
        background: white;
        padding: 12px 15px;
        border-radius: 6px;
        border-left: 4px solid #3498db;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .preview-item.empty {
        color: #95a5a6;
        border-left-color: #ecf0f1;
    }

    .preview-name {
        font-weight: bold;
        color: #2c3e50;
        display: block;
    }

    .preview-email {
        color: #7f8c8d;
        font-size: 12px;
        display: block;
        margin-top: 3px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 40px;
        grid-column: 1 / -1;
        justify-content: flex-end;
    }

    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(52, 152, 219, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-secondary {
        background: white;
        color: #3498db;
        border: 2px solid #3498db;
    }

    .btn-secondary:hover {
        background: #ecf0f1;
    }

    .required {
        color: #dc3545;
    }

    @media (max-width: 1024px) {
        .content-wrapper {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .form-actions {
            grid-column: 1;
        }
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 24px;
        }

        .form-card {
            padding: 20px;
        }

        .btn {
            flex: 1;
            justify-content: center;
        }

        .form-actions {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<a href="{{ route('admin.assignments.index') }}" class="back-link">← {{ __('messages.back') }}</a>

<div class="page-header">
    <h1>{{ app()->getLocale() === 'en' ? 'Create New Assignment' : 'Buat Penugasan Baru' }}</h1>
    <p>{{ app()->getLocale() === 'en' ? 'Assign a lecturer to a student' : 'Tetapkan seorang dosen untuk seorang mahasiswa' }}</p>
</div>

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

<form action="{{ route('admin.assignments.store') }}" method="POST" id="assignmentForm">
    @csrf

    <div class="content-wrapper">
        <!-- Lecturer Selection -->
        <div class="form-card dosen-card">
            <h2>{{ app()->getLocale() === 'en' ? 'Select Lecturer' : 'Pilih Dosen' }} <span class="required">*</span></h2>

            <div class="search-container">
                <input
                    type="text"
                    id="dosen_search"
                    class="search-input"
                    placeholder="{{ app()->getLocale() === 'en' ? 'Search lecturers...' : 'Cari dosen...' }}"
                >
                <div class="search-icon">🔍</div>
            </div>
            <p class="help-text">💡 {{ app()->getLocale() === 'en' ? 'Type name or email to filter' : 'Ketik nama atau email untuk memfilter' }}</p>

            <div class="select-list" id="dosenList">
                <label class="select-item" style="opacity: 0.5; cursor: default;">
                    <div class="select-item-text">
                        <span class="select-item-name">{{ app()->getLocale() === 'en' ? 'Select a lecturer...' : 'Pilih seorang dosen...' }}</span>
                    </div>
                </label>
                @foreach($dosens as $dosen)
                    <label class="select-item dosen-option">
                        <input
                            type="radio"
                            name="dosen_id"
                            value="{{ $dosen->id }}"
                            data-name="{{ strtolower($dosen->name) }}"
                            data-email="{{ strtolower($dosen->email) }}"
                            data-full-name="{{ $dosen->name }}"
                            data-full-email="{{ $dosen->email }}"
                            class="dosen-input"
                            onchange="updateDosenPreview()"
                        >
                        <div class="select-item-text">
                            <span class="select-item-name">{{ $dosen->name }}</span>
                            <span class="select-item-email">{{ $dosen->email }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="selection-preview">
                <div class="preview-label">{{ app()->getLocale() === 'en' ? 'Selected Lecturer' : 'Dosen Terpilih' }}</div>
                <div class="preview-item empty" id="dosenPreview">
                    <span class="preview-name">-</span>
                </div>
            </div>
        </div>

        <!-- Student Selection -->
        <div class="form-card mahasiswa-card">
            <h2>{{ app()->getLocale() === 'en' ? 'Select Student' : 'Pilih Mahasiswa' }} <span class="required">*</span></h2>

            <div class="search-container">
                <input
                    type="text"
                    id="mahasiswa_search"
                    class="search-input"
                    placeholder="{{ app()->getLocale() === 'en' ? 'Search students...' : 'Cari mahasiswa...' }}"
                >
                <div class="search-icon">🔍</div>
            </div>
            <p class="help-text">💡 {{ app()->getLocale() === 'en' ? 'Type name or email to filter' : 'Ketik nama atau email untuk memfilter' }}</p>

            <div class="select-list" id="mahasiswaList">
                <label class="select-item" style="opacity: 0.5; cursor: default;">
                    <div class="select-item-text">
                        <span class="select-item-name">{{ app()->getLocale() === 'en' ? 'Select a student...' : 'Pilih seorang mahasiswa...' }}</span>
                    </div>
                </label>
                @foreach($mahasiswas as $mahasiswa)
                    <label class="select-item mahasiswa-option">
                        <input
                            type="radio"
                            name="mahasiswa_id"
                            value="{{ $mahasiswa->id }}"
                            data-name="{{ strtolower($mahasiswa->name) }}"
                            data-email="{{ strtolower($mahasiswa->email) }}"
                            data-full-name="{{ $mahasiswa->name }}"
                            data-full-email="{{ $mahasiswa->email }}"
                            class="mahasiswa-input"
                            onchange="updateMahasiswaPreview()"
                        >
                        <div class="select-item-text">
                            <span class="select-item-name">{{ $mahasiswa->name }}</span>
                            <span class="select-item-email">{{ $mahasiswa->email }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="selection-preview">
                <div class="preview-label">{{ app()->getLocale() === 'en' ? 'Selected Student' : 'Mahasiswa Terpilih' }}</div>
                <div class="preview-item empty" id="mahasiswaPreview">
                    <span class="preview-name">-</span>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">
                ✕ {{ __('messages.cancel') }}
            </a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                ✓ {{ app()->getLocale() === 'en' ? 'Create Assignment' : 'Buat Penugasan' }}
            </button>
        </div>
    </div>
</form>

<script>
    // Search functionality for Dosen
    document.getElementById('dosen_search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.dosen-option');

        items.forEach(item => {
            const input = item.querySelector('.dosen-input');
            const name = input.getAttribute('data-name') || '';
            const email = input.getAttribute('data-email') || '';

            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Search functionality for Mahasiswa
    document.getElementById('mahasiswa_search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.mahasiswa-option');

        items.forEach(item => {
            const input = item.querySelector('.mahasiswa-input');
            const name = input.getAttribute('data-name') || '';
            const email = input.getAttribute('data-email') || '';

            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Update Dosen Preview
    function updateDosenPreview() {
        const checked = document.querySelector('.dosen-input:checked');
        const preview = document.getElementById('dosenPreview');

        if (!checked) {
            preview.innerHTML = '<span class="preview-name">-</span>';
            preview.className = 'preview-item empty';
        } else {
            const name = checked.getAttribute('data-full-name');
            const email = checked.getAttribute('data-full-email');
            preview.innerHTML = `<span class="preview-name">${name}</span><span class="preview-email">${email}</span>`;
            preview.className = 'preview-item';
        }
    }

    // Update Mahasiswa Preview
    function updateMahasiswaPreview() {
        const checked = document.querySelector('.mahasiswa-input:checked');
        const preview = document.getElementById('mahasiswaPreview');

        if (!checked) {
            preview.innerHTML = '<span class="preview-name">-</span>';
            preview.className = 'preview-item empty';
        } else {
            const name = checked.getAttribute('data-full-name');
            const email = checked.getAttribute('data-full-email');
            preview.innerHTML = `<span class="preview-name">${name}</span><span class="preview-email">${email}</span>`;
            preview.className = 'preview-item';
        }
    }

    // Initialize previews on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateDosenPreview();
        updateMahasiswaPreview();
    });
</script>

@endsection
