<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDosen = User::where('role', 'dosen')->count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalAssignments = Assignment::count();

        return view('admin.dashboard', compact('totalDosen', 'totalMahasiswa', 'totalAssignments'));
    }

    public function index()
    {
        $assignments = Assignment::with(['dosen', 'mahasiswa'])->get();
        return view('admin.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $dosens = User::where('role', 'dosen')->get();
        $mahasiswas = User::where('role', 'mahasiswa')->get();

        // Get assigned students for each dosen
        $assignedStudentsByDosen = [];
        foreach ($dosens as $dosen) {
            $assignedStudentsByDosen[$dosen->id] = Assignment::where('dosen_id', $dosen->id)
                ->pluck('mahasiswa_id')
                ->toArray();
        }

        return view('admin.assignments.create', compact('dosens', 'mahasiswas', 'assignedStudentsByDosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'mahasiswa_id' => 'required|array|min:1',
            'mahasiswa_id.*' => 'exists:users,id',
        ], [
            'mahasiswa_id.required' => 'Please select at least one student.',
            'mahasiswa_id.min' => 'Please select at least one student.',
        ]);

        $dosenId = $request->dosen_id;
        $mahasiswaIds = $request->mahasiswa_id;
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($mahasiswaIds as $mahasiswaId) {
            // Check if assignment already exists
            $existingAssignment = Assignment::where('dosen_id', $dosenId)
                ->where('mahasiswa_id', $mahasiswaId)
                ->first();

            if (!$existingAssignment) {
                Assignment::create([
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $mahasiswaId,
                ]);
                $createdCount++;
            } else {
                $skippedCount++;
            }
        }

        $message = "Created $createdCount assignment" . ($createdCount !== 1 ? 's' : '');
        if ($skippedCount > 0) {
            $message .= " ($skippedCount already existed).";
        } else {
            $message .= '.';
        }

        return redirect()->route('admin.assignments.index')->with('success', $message);
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('success', 'Assignment deleted successfully.');
    }
}
