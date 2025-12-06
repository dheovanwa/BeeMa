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
        return view('admin.assignments.create', compact('dosens', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'mahasiswa_id' => 'required|exists:users,id',
        ]);

        // Check if assignment already exists
        $existingAssignment = Assignment::where('dosen_id', $request->dosen_id)
            ->where('mahasiswa_id', $request->mahasiswa_id)
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['error' => 'This assignment already exists.'])->withInput();
        }

        Assignment::create([
            'dosen_id' => $request->dosen_id,
            'mahasiswa_id' => $request->mahasiswa_id,
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('success', 'Assignment deleted successfully.');
    }
}
