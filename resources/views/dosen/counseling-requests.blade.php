<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counseling Requests - BeeMa</title>
</head>
<body>
    <h1>Counseling Requests</h1>
    <a href="{{ route('dosen.dashboard') }}">Back to Dashboard</a>

    @if(session('success'))
        <div style="background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <hr>

    <h2>Incoming Counseling Requests</h2>

    @if($requests->count() > 0)
        <table border="1" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr style="background: #f0f0f0;">
                    <th style="padding: 10px;">Mahasiswa</th>
                    <th style="padding: 10px;">Date & Time</th>
                    <th style="padding: 10px;">Message</th>
                    <th style="padding: 10px;">File</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Requested At</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td style="padding: 10px;">
                            <strong>{{ $req->mahasiswa->name }}</strong><br>
                            <small>{{ $req->mahasiswa->email }}</small>
                        </td>
                        <td style="padding: 10px;">
                            <strong>{{ $req->date->format('d/m/Y') }}</strong><br>
                            {{ \Carbon\Carbon::parse($req->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($req->end_time)->format('H:i') }}
                        </td>
                        <td style="padding: 10px;">{{ $req->message ?? 'No message' }}</td>
                        <td style="padding: 10px;">
                            @if($req->file_path)
                                <a href="{{ route('dosen.counseling.download', $req->id) }}" style="color: #007bff;">Download</a>
                            @else
                                No file
                            @endif
                        </td>
                        <td style="padding: 10px;">
                            @if($req->status === 'pending')
                                <span style="background: #ffc107; color: white; padding: 5px 10px;">Pending</span>
                            @elseif($req->status === 'approved')
                                <span style="background: #28a745; color: white; padding: 5px 10px;">Approved</span>
                            @else
                                <span style="background: #dc3545; color: white; padding: 5px 10px;">Rejected</span>
                                @if($req->rejection_reason)
                                    <br><small>Reason: {{ $req->rejection_reason }}</small>
                                @endif
                            @endif
                        </td>
                        <td style="padding: 10px;">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 10px;">
                            @if($req->status === 'pending')
                                <form action="{{ route('dosen.counseling.update-status', $req->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" style="background: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer;">Approve</button>
                                </form>
                                
                                <button onclick="document.getElementById('rejectModal{{ $req->id }}').style.display='block'" style="background: #dc3545; color: white; padding: 5px 10px; border: none; cursor: pointer; margin-left: 5px;">Reject</button>
                                
                                <!-- Reject Modal -->
                                <div id="rejectModal{{ $req->id }}" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
                                    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 50%;">
                                        <span onclick="document.getElementById('rejectModal{{ $req->id }}').style.display='none'" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
                                        <h3>Reject Counseling Request</h3>
                                        <form action="{{ route('dosen.counseling.update-status', $req->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <div style="margin: 10px 0;">
                                                <label for="rejection_reason{{ $req->id }}">Rejection Reason: *</label><br>
                                                <textarea name="rejection_reason" id="rejection_reason{{ $req->id }}" rows="4" style="width: 100%; padding: 5px;" required></textarea>
                                            </div>
                                            <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer;">Reject</button>
                                            <button type="button" onclick="document.getElementById('rejectModal{{ $req->id }}').style.display='none'" style="background: #6c757d; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-left: 10px;">Cancel</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <span style="color: #999;">No actions</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No counseling requests found.</p>
    @endif

    <hr>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
