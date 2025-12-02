<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking - BeeMa</title>
</head>
<body>
    <h1>Form Booking Bimbingan</h1>

    <div style="margin: 20px 0;">
        <a href="{{ route('mahasiswa.dashboard') }}">&larr; Kembali ke Dashboard</a>
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

    <hr>

    <h2>Detail Jadwal</h2>
    <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; margin: 20px 0;">
        <p><strong>Dosen:</strong> {{ $schedule->dosen->name }}</p>
        <p><strong>Tanggal:</strong> {{ $schedule->date->format('d/m/Y') }}</p>
        <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
        <p><strong>Kuota:</strong> {{ $schedule->quota }}</p>
    </div>

    <hr>

    <h2>Form Booking</h2>

    <form method="POST" action="{{ route('mahasiswa.booking.store') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

        <div style="margin: 20px 0;">
            <label for="file"><strong>Upload Draft PDF (Wajib):</strong></label><br>
            <p style="color: #666; font-size: 14px;">
                Instruksi: Upload file draft dalam format PDF. Maksimal ukuran file 10MB.
            </p>
            <input type="file" id="file" name="file" accept=".pdf" required style="padding: 10px; border: 2px dashed #ddd; width: 100%; max-width: 500px;">
        </div>

        <div style="margin: 20px 0;">
            <label for="message">Pesan (Opsional):</label><br>
            <textarea id="message" name="message" rows="5" placeholder="Tambahkan pesan atau catatan untuk dosen..." style="width: 100%; max-width: 500px; padding: 10px;">{{ old('message') }}</textarea>
        </div>

        <div style="margin: 20px 0;">
            <button type="submit" style="background: #007bff; color: white; padding: 15px 30px; border: none; cursor: pointer; font-size: 16px; font-weight: bold;">
                Submit Booking
            </button>
        </div>
    </form>
</body>
</html>
