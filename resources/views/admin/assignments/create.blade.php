<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
    <style>
        .search-container {
            position: relative;
            margin-bottom: 10px;
        }
        .search-input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .select-box {
            width: 100%;
            height: 150px;
            padding: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>Create New Assignment</h1>
    <a href="{{ route('admin.assignments.index') }}">Back to Assignments</a>

    @if($errors->any())
        <div style="color: red; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.assignments.store') }}" method="POST">
        @csrf
        <div>
            <label for="dosen_id">Dosen:</label>
            <div class="search-container">
                <input type="text" id="dosen_search" class="search-input" placeholder="Search dosen by name or email...">
            </div>
            <select name="dosen_id" id="dosen_id" class="select-box" size="5" required>
                <option value="">Select Dosen</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->id }}" data-name="{{ strtolower($dosen->name) }}" data-email="{{ strtolower($dosen->email) }}">
                        {{ $dosen->name }} ({{ $dosen->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="mahasiswa_id">Mahasiswa:</label>
            <div class="search-container">
                <input type="text" id="mahasiswa_search" class="search-input" placeholder="Search mahasiswa by name or email...">
            </div>
            <select name="mahasiswa_id" id="mahasiswa_id" class="select-box" size="5" required>
                <option value="">Select Mahasiswa</option>
                @foreach($mahasiswas as $mahasiswa)
                    <option value="{{ $mahasiswa->id }}" data-name="{{ strtolower($mahasiswa->name) }}" data-email="{{ strtolower($mahasiswa->email) }}">
                        {{ $mahasiswa->name }} ({{ $mahasiswa->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">Create Assignment</button>
    </form>

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
</body>
</html>
