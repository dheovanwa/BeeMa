<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - BeeMa</title>
</head>
<body>
    <h1>Edit Jadwal</h1>

    <div style="margin: 20px 0;">
        <a href="{{ route('dosen.dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>

    @if ($errors->any())
        <div style="background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dosen.schedules.update', $schedule->id) }}">
        @csrf
        @method('PUT')

        <div style="margin: 10px 0;">
            <label for="date">Tanggal:</label><br>
            <input type="date" id="date" name="date" value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="start_time">Waktu Mulai:</label><br>
            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="end_time">Waktu Selesai:</label><br>
            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="quota">Kuota:</label><br>
            <input type="number" id="quota" name="quota" value="{{ old('quota', $schedule->quota) }}" min="1" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="status">Status:</label><br>
            <select id="status" name="status" required style="width: 300px; padding: 5px;">
                <option value="open" {{ old('status', $schedule->status) == 'open' ? 'selected' : '' }}>Buka</option>
                <option value="closed" {{ old('status', $schedule->status) == 'closed' ? 'selected' : '' }}>Tutup</option>
            </select>
        </div>

        <div style="margin: 20px 0;">
            <button type="submit" style="background: #007bff; color: white; padding: 10px 30px; border: none; cursor: pointer; font-size: 16px;">
                Update Jadwal
            </button>
        </div>
    </form>
</body>
</html>
