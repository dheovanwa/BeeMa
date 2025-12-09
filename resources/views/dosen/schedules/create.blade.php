<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal - BeeMa</title>
</head>
<body>
    <h1>Tambah Jadwal Baru</h1>

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

    <form method="POST" action="{{ route('dosen.schedules.store') }}">
        @csrf

        <div style="margin: 10px 0;">
            <label for="date">Tanggal:</label><br>
            <input type="date" id="date" name="date" value="{{ old('date') }}" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="start_time">Waktu Mulai:</label><br>
            <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="end_time">Waktu Selesai:</label><br>
            <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="quota">Kuota:</label><br>
            <input type="number" id="quota" name="quota" value="{{ old('quota', 1) }}" min="1" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="location">Lokasi:</label><br>
            <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Ruang 301, Gedung A" style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="status">Status:</label><br>
            <select id="status" name="status" required style="width: 300px; padding: 5px;">
                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Buka</option>
                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Tutup</option>
            </select>
        </div>

        <div style="margin: 20px 0;">
            <button type="submit" style="background: #007bff; color: white; padding: 10px 30px; border: none; cursor: pointer; font-size: 16px;">
                Simpan Jadwal
            </button>
        </div>
    </form>
</body>
</html>
