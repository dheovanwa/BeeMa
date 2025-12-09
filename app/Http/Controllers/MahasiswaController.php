<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Booking;
use App\Models\User;
use App\Models\CounselingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Show the mahasiswa dashboard with available schedules.
     */
    public function dashboard(Request $request)
    {
        $mahasiswa = Auth::user();
        
        // Get IDs of assigned dosen
        $assignedDosenIds = $mahasiswa->assignedDosens()->pluck('users.id');

        // Only show schedules from assigned dosen
        $schedules = Schedule::with('dosen')
            ->whereIn('user_id', $assignedDosenIds)
            ->where('status', 'open')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('dashboard.mahasiswa', compact('schedules'));
    }

    /**
     * Show the booking form for a specific schedule.
     */
    public function showBookingForm(Schedule $schedule)
    {
        $mahasiswa = Auth::user();
        
        // Check if this dosen is assigned to the mahasiswa
        $isAssigned = $mahasiswa->assignedDosens()->where('users.id', $schedule->user_id)->exists();
        
        if (!$isAssigned) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak memiliki akses ke dosen ini!');
        }
        
        // Check if schedule is open
        if ($schedule->status !== 'open') {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Jadwal ini sudah ditutup!');
        }

        // Check if user already has a booking for this schedule
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('schedule_id', $schedule->id)
            ->first();

        if ($existingBooking) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda sudah melakukan booking untuk jadwal ini!');
        }

        return view('mahasiswa.booking-form', compact('schedule'));
    }

    /**
     * Store a new booking.
     * POST /mahasiswa/booking
     */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'message' => 'nullable|string|max:1000',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Check if schedule is open
        if ($schedule->status !== 'open') {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Jadwal ini sudah ditutup!');
        }

        // Check if user already has a booking for this schedule
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('schedule_id', $schedule->id)
            ->first();

        if ($existingBooking) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda sudah melakukan booking untuk jadwal ini!');
        }

        // Upload file
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bookings', $fileName, 'public');
        }

        Booking::create([
            'user_id' => Auth::id(),
            'schedule_id' => $schedule->id,
            'file_path' => $filePath,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.my-bookings')->with('success', 'Booking berhasil dibuat! Menunggu persetujuan dosen.');
    }

    /**
     * Show the user's booking history.
     */
    public function myBookings()
    {
        $bookings = Booking::with(['schedule.dosen'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $counselingRequests = CounselingRequest::with('dosen')
            ->where('mahasiswa_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.my-bookings', compact('bookings', 'counselingRequests'));
    }

    /**
     * Show form to request counseling.
     */
    public function createCounselingRequest()
    {
        $mahasiswa = Auth::user();
        $dosens = $mahasiswa->assignedDosens;

        if ($dosens->isEmpty()) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda belum memiliki dosen yang ditugaskan!');
        }

        return view('mahasiswa.request-counseling', compact('dosens'));
    }

    /**
     * Store counseling request.
     */
    public function storeCounselingRequest(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'message' => 'nullable|string|max:1000',
        ]);

        $mahasiswa = Auth::user();
        $dosenId = $request->dosen_id;
        $date = $request->date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // Check if dosen is assigned to mahasiswa
        $isAssigned = $mahasiswa->assignedDosens()->where('users.id', $dosenId)->exists();
        
        if (!$isAssigned) {
            return back()->withErrors(['error' => 'Dosen ini tidak ditugaskan kepada Anda!'])->withInput();
        }

        // Check for time conflicts with dosen's existing schedules
        // Two time ranges overlap if: start1 < end2 AND start2 < end1
        $scheduleConflict = Schedule::where('user_id', $dosenId)
            ->where('date', $date)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($scheduleConflict) {
            return back()->withErrors(['error' => 'Waktu yang Anda pilih bertabrakan dengan jadwal dosen yang sudah ada!'])->withInput();
        }

        // Check for time conflicts with other counseling requests
        $counselingConflict = CounselingRequest::where('dosen_id', $dosenId)
            ->where('date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($counselingConflict) {
            return back()->withErrors(['error' => 'Waktu yang Anda pilih bertabrakan dengan permintaan bimbingan lain yang sudah ada!'])->withInput();
        }

        // Upload file
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('counseling_requests', $fileName, 'public');
        }

        CounselingRequest::create([
            'mahasiswa_id' => Auth::id(),
            'dosen_id' => $dosenId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'file_path' => $filePath,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Permintaan bimbingan berhasil dikirim! Menunggu konfirmasi dosen.');
    }
}

