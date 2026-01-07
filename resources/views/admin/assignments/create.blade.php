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
        color: #f39c12;
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
        border-bottom: 2px solid #f39c12;
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
        border-color: #f39c12;
        outline: none;
        box-shadow: 0 0 8px rgba(243, 156, 18, 0.2);
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
        border-color: #f39c12;
        outline: none;
        box-shadow: 0 0 8px rgba(243, 156, 18, 0.2);
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
        border-color: #f39c12;
        box-shadow: 0 0 8px rgba(243, 156, 18, 0.2);
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
        margin-right: 8px;
        margin-left: 0px;
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
        border-left: 4px solid #f39c12;
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
        background: linear-gradient(135deg, #f39c12 0%, #d68910 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(243, 156, 18, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-secondary {
        background: white;
        color: #f39c12;
        border: 2px solid #f39c12;
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
                    @php
                        $isAssigned = in_array($mahasiswa->id, $assignedStudentsByDosen[$selectedDosenId ?? 0] ?? []);
                    @endphp
                    <label class="select-item mahasiswa-option" data-mahasiswa-id="{{ $mahasiswa->id }}" style="{{ $isAssigned ? 'position: relative; opacity: 0.5; pointer-events: none;' : '' }}">
                        @if($isAssigned)
                            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(52,152,219,0.75); border-radius: 6px; font-weight: bold; color: white; font-size: 13px; backdrop-filter: blur(2px); padding: 10px; text-align: center;">{{ __('messages.assigned_student') }}</div>
                        @endif
                        <input
                            type="checkbox"
                            name="mahasiswa_id[]"
                            value="{{ $mahasiswa->id }}"
                            data-name="{{ strtolower($mahasiswa->name) }}"
                            data-email="{{ strtolower($mahasiswa->email) }}"
                            data-full-name="{{ $mahasiswa->name }}"
                            data-full-email="{{ $mahasiswa->email }}"
                            class="mahasiswa-input"
                            {{ $isAssigned ? 'disabled' : '' }}
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
    // Make assigned students data available globally
    window.assignedStudentsByDosen = @json($assignedStudentsByDosen);
    window.assignedStudentMessage = '{{ __('messages.assigned_student') }}';

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

    // Update Mahasiswa Preview (for multiple checkboxes)
    function updateMahasiswaPreview() {
        const checkedItems = document.querySelectorAll('.mahasiswa-input:checked');
        const preview = document.getElementById('mahasiswaPreview');

        if (checkedItems.length === 0) {
            preview.innerHTML = '<span class="preview-name">-</span>';
            preview.className = 'preview-item empty';
        } else {
            let html = '';
            checkedItems.forEach((item, index) => {
                const name = item.getAttribute('data-full-name');
                const email = item.getAttribute('data-full-email');
                html += `<div style="background: white; padding: 12px 15px; border-radius: 6px; border-left: 4px solid #3498db; margin-bottom: ${index < checkedItems.length - 1 ? '8px' : '0'};"><span style="font-weight: bold; color: #2c3e50; display: block;">${name}</span><span style="color: #7f8c8d; font-size: 12px; display: block; margin-top: 3px;">${email}</span></div>`;
            });
            preview.innerHTML = html;
            preview.className = 'preview-item';
        }
    }

    // Update assigned students blocking when dosen changes
    function updateMahasiswaAssignedStatus() {
        const selectedDosen = document.querySelector('.dosen-input:checked');
        const mahasiswaItems = document.querySelectorAll('.mahasiswa-option');

        if (!selectedDosen) {
            // No dosen selected, disable all
            mahasiswaItems.forEach(item => {
                item.style.position = 'relative';
                item.style.opacity = '0.5';
                item.style.pointerEvents = 'none';
                let blurDiv = item.querySelector('.assigned-blur-overlay');
                if (!blurDiv) {
                    blurDiv = document.createElement('div');
                    blurDiv.className = 'assigned-blur-overlay';
                    blurDiv.style.position = 'absolute';
                    blurDiv.style.inset = '0';
                    blurDiv.style.display = 'flex';
                    blurDiv.style.alignItems = 'center';
                    blurDiv.style.justifyContent = 'center';
                    blurDiv.style.background = 'rgba(52,152,219,0.75)';
                    blurDiv.style.borderRadius = '6px';
                    blurDiv.style.fontWeight = 'bold';
                    blurDiv.style.color = 'white';
                    blurDiv.style.fontSize = '13px';
                    blurDiv.style.backdropFilter = 'blur(2px)';
                    blurDiv.style.padding = '10px';
                    blurDiv.style.textAlign = 'center';
                    blurDiv.textContent = window.assignedStudentMessage;
                    item.appendChild(blurDiv);
                } else {
                    blurDiv.style.display = 'flex';
                }
            });
            return;
        }

        // Fetch assigned students for this dosen
        const dosenId = selectedDosen.value;
        const assignedStudents = window.assignedStudentsByDosen[dosenId] || [];

        mahasiswaItems.forEach(item => {
            const mahasiswaId = parseInt(item.getAttribute('data-mahasiswa-id'));
            const isAssigned = assignedStudents.includes(mahasiswaId);
            const checkbox = item.querySelector('.mahasiswa-input');
            let blurDiv = item.querySelector('.assigned-blur-overlay');

            if (isAssigned) {
                item.style.position = 'relative';
                item.style.opacity = '0.5';
                item.style.pointerEvents = 'none';
                checkbox.disabled = true;

                if (!blurDiv) {
                    blurDiv = document.createElement('div');
                    blurDiv.className = 'assigned-blur-overlay';
                    blurDiv.style.position = 'absolute';
                    blurDiv.style.inset = '0';
                    blurDiv.style.display = 'flex';
                    blurDiv.style.alignItems = 'center';
                    blurDiv.style.justifyContent = 'center';
                    blurDiv.style.background = 'rgba(52,152,219,0.75)';
                    blurDiv.style.borderRadius = '6px';
                    blurDiv.style.fontWeight = 'bold';
                    blurDiv.style.color = 'white';
                    blurDiv.style.fontSize = '13px';
                    blurDiv.style.backdropFilter = 'blur(2px)';
                    blurDiv.style.padding = '10px';
                    blurDiv.style.textAlign = 'center';
                    blurDiv.textContent = window.assignedStudentMessage;
                    item.appendChild(blurDiv);
                } else {
                    blurDiv.style.display = 'flex';
                }

                checkbox.checked = false;
            } else {
                item.style.opacity = '1';
                item.style.pointerEvents = 'auto';
                checkbox.disabled = false;
                if (blurDiv) blurDiv.style.display = 'none';
            }
        });

        updateMahasiswaPreview();
    }

    // Update dosen selection to trigger mahasiswa status update
    document.querySelectorAll('.dosen-input').forEach(input => {
        input.addEventListener('change', function() {
            updateDosenPreview();
            updateMahasiswaAssignedStatus();
        });
    });

    // Update mahasiswa checkbox changes to update preview
    document.querySelectorAll('.mahasiswa-input').forEach(input => {
        input.addEventListener('change', function() {
            updateMahasiswaPreview();
        });
    });

    // Initialize previews on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateDosenPreview();
        updateMahasiswaPreview();
        updateMahasiswaAssignedStatus();
    });
</script>

@endsection
