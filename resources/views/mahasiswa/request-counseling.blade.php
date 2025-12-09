<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Counseling - BeeMa</title>
</head>
<body>
    <h1>Request Counseling</h1>
    <a href="{{ route('mahasiswa.dashboard') }}">Back to Dashboard</a>

    @if($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.counseling.store') }}" method="POST" enctype="multipart/form-data" style="margin: 20px 0;">
        @csrf
        
        <div style="margin: 10px 0;">
            <label for="dosen_id">Select Dosen: *</label><br>
            <select name="dosen_id" id="dosen_id" required style="width: 300px; padding: 5px;">
                <option value="">Choose Dosen</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->id }}">{{ $dosen->name }} ({{ $dosen->email }})</option>
                @endforeach
            </select>
        </div>

        <div style="margin: 10px 0;">
            <label for="date">Date: *</label><br>
            <input type="date" name="date" id="date" required style="width: 300px; padding: 5px;" min="{{ date('Y-m-d') }}">
        </div>

        <div style="margin: 10px 0;">
            <label for="start_time">Start Time: *</label><br>
            <input type="time" name="start_time" id="start_time" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="end_time">End Time: *</label><br>
            <input type="time" name="end_time" id="end_time" required style="width: 300px; padding: 5px;">
        </div>

        <div style="margin: 10px 0;">
            <label for="file">Upload Draft (PDF, max 10MB):</label><br>
            <input type="file" name="file" id="file" accept=".pdf" style="margin: 5px 0;">
        </div>

        <div style="margin: 10px 0;">
            <label for="message">Message:</label><br>
            <textarea name="message" id="message" rows="5" style="width: 400px; padding: 5px;" placeholder="Enter your message here..."></textarea>
        </div>

        <div style="margin: 10px 0;">
            <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">Submit Request</button>
        </div>
    </form>
</body>
</html>
