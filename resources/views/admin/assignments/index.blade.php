<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments</title>
</head>
<body>
    <h1>Manage Assignments</h1>
    <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
    <a href="{{ route('admin.assignments.create') }}">Create New Assignment</a>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Dosen</th>
                <th>Mahasiswa</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->id }}</td>
                    <td>{{ $assignment->dosen->name }} ({{ $assignment->dosen->email }})</td>
                    <td>{{ $assignment->mahasiswa->name }} ({{ $assignment->mahasiswa->email }})</td>
                    <td>{{ $assignment->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No assignments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
