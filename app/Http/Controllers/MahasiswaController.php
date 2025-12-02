<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Booking;
use App\Models\User;
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
        $query = Schedule::with('dosen')
            ->where('status', 'open')
            ->where('date', '>=', now()->format('Y-m-d'));

        // Filter by dosen name
        if ($request->filled('dosen_name')) {
            $query->whereHas('dosen', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->dosen_name . '%');
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $schedules = $query->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('dashboard.mahasiswa', compact('schedules'));
    }

    /**
     * Show the booking form for a specific schedule.
     */
    public function showBookingForm(Schedule $schedule)
    {
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
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
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

        return view('mahasiswa.my-bookings', compact('bookings'));
    }
}

