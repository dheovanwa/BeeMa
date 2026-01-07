@extends('layouts.app')

@section('title', (app()->getLocale() === 'en' ? 'Edit Schedule' : 'Edit Jadwal') . ' - BeeMa')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
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

    .form-title {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #2c3e50;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #bdc3c7;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #f39c12;
        box-shadow: 0 0 5px rgba(243, 156, 18, 0.3);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-save {
        background: #f39c12;
        color: white;
    }

    .btn-save:hover {
        background: #d68910;
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
    }

    .help-text {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 5px;
    }

    .error {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
    }
</style>

<div class="form-container">
    <a href="{{ route('dosen.dashboard') }}" class="back-link">← {{ __('messages.back') }}</a>

    <h1 class="form-title">{{ app()->getLocale() === 'en' ? 'Edit Schedule' : 'Edit Jadwal' }}</h1>

    <form method="POST" action="{{ route('dosen.schedules.update', $schedule->id) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="date">{{ __('messages.date') }} <span style="color: #e74c3c;">*</span></label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    value="{{ old('date', $schedule->date->format('Y-m-d')) }}"
                    required
                >
                @error('date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.status') }} <span style="color: #e74c3c;">*</span></label>
                <select name="status" required>
                    <option value="open" {{ old('status', $schedule->status) == 'open' ? 'selected' : '' }}>{{ __('messages.open') }}</option>
                    <option value="closed" {{ old('status', $schedule->status) == 'closed' ? 'selected' : '' }}>{{ __('messages.closed') }}</option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_time">{{ app()->getLocale() === 'en' ? 'Start Time' : 'Waktu Mulai' }} <span style="color: #e74c3c;">*</span></label>
                <input
                    type="time"
                    id="start_time"
                    name="start_time"
                    value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}"
                    required
                >
                @error('start_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_time">{{ app()->getLocale() === 'en' ? 'End Time' : 'Waktu Selesai' }} <span style="color: #e74c3c;">*</span></label>
                <input
                    type="time"
                    id="end_time"
                    name="end_time"
                    value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}"
                    required
                >
                <p class="help-text">{{ app()->getLocale() === 'en' ? 'Must be after start time' : 'Harus setelah waktu mulai' }}</p>
                @error('end_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="quota">{{ __('messages.quota') }} <span style="color: #e74c3c;">*</span></label>
                <input
                    type="number"
                    id="quota"
                    name="quota"
                    value="{{ old('quota', $schedule->quota) }}"
                    min="1"
                    max="100"
                    required
                >
                <p class="help-text">{{ app()->getLocale() === 'en' ? 'Number of students who can book this schedule' : 'Jumlah mahasiswa yang dapat booking jadwal ini' }}</p>
                @error('quota')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">{{ __('messages.location') }} ({{ app()->getLocale() === 'en' ? 'Optional' : 'Opsional' }})</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location', $schedule->location) }}"
                    placeholder="{{ app()->getLocale() === 'en' ? 'e.g., Room 301' : 'Contoh: Ruang 301' }}"
                >
                @error('location')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-save">{{ app()->getLocale() === 'en' ? 'Update Schedule' : 'Update Jadwal' }}</button>
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-cancel">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>

<script>
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    let endTimeErrorDiv = document.querySelector('[data-end-time-error]');

    // Create error div if it doesn't exist
    if (!endTimeErrorDiv) {
        endTimeErrorDiv = document.createElement('div');
        endTimeErrorDiv.className = 'error';
        endTimeErrorDiv.setAttribute('data-end-time-error', 'true');
        endTimeErrorDiv.style.display = 'none';
        endTimeInput.parentNode.appendChild(endTimeErrorDiv);
    }

    function validateEndTime() {
        if (!startTimeInput.value || !endTimeInput.value) {
            endTimeErrorDiv.style.display = 'none';
            return;
        }

        const startTime = new Date(`2000-01-01 ${startTimeInput.value}`);
        const endTime = new Date(`2000-01-01 ${endTimeInput.value}`);

        if (endTime <= startTime) {
            endTimeErrorDiv.textContent = "{{ app()->getLocale() === 'en' ? 'End time must be after start time' : 'Waktu selesai harus setelah waktu mulai' }}";
            endTimeErrorDiv.style.display = 'block';
            endTimeInput.style.borderColor = '#e74c3c';
        } else {
            endTimeErrorDiv.style.display = 'none';
            endTimeInput.style.borderColor = '#bdc3c7';
        }
    }

    startTimeInput.addEventListener('change', validateEndTime);
    endTimeInput.addEventListener('change', validateEndTime);
</script>

@endsection
