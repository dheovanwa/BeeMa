<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Booking;
use App\Models\CounselingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Show the dosen dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $activeSchedules = Schedule::where('user_id', $user->id)
            ->where('status', 'open')
            ->count();
        
        $totalBookings = Booking::whereHas('schedule', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // Get pending counseling requests count
        $pendingCounselingRequests = CounselingRequest::where('dosen_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // Get all schedules (all statuses)
        $schedules = Schedule::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Get all counseling requests (all statuses)
        $counselingRequests = CounselingRequest::with('mahasiswa')
            ->where('dosen_id', $user->id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
        
        return view('dashboard.dosen', compact('activeSchedules', 'totalBookings', 'schedules', 'pendingCounselingRequests', 'counselingRequests'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        return view('dosen.schedules.create');
    }

    /**
     * Store a newly created schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1',
            'status' => 'required|in:open,closed',
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'quota' => $request->quota,
            'status' => $request->status,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        // Ensure the schedule belongs to the authenticated dosen
        if ($schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('dosen.schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified schedule.
     */
    public function update(Request $request, Schedule $schedule)
    {
        // Ensure the schedule belongs to the authenticated dosen
        if ($schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1',
            'status' => 'required|in:open,closed',
        ]);

        $schedule->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'quota' => $request->quota,
            'status' => $request->status,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule)
    {
        // Ensure the schedule belongs to the authenticated dosen
        if ($schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->delete();

        return redirect()->route('dosen.dashboard')->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Show incoming booking requests.
     */
    public function incomingRequests()
    {
        $user = Auth::user();
        
        $bookings = Booking::with(['mahasiswa', 'schedule'])
            ->whereHas('schedule', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dosen.incoming-requests', compact('bookings'));
    }

    /**
     * Update booking status (Approve/Reject).
     * PATCH /dosen/booking/{id}/status
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        // Ensure the booking's schedule belongs to the authenticated dosen
        if ($booking->schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        if ($request->status === 'approved') {
            // Check current approved bookings count
            $approvedCount = Booking::where('schedule_id', $booking->schedule_id)
                ->where('status', 'approved')
                ->count();

            $schedule = $booking->schedule;

            // Check if quota is already full
            if ($approvedCount >= $schedule->quota) {
                return back()->with('error', 'Kuota untuk jadwal ini sudah penuh!');
            }

            // Approve this booking
            $booking->update([
                'status' => 'approved',
                'rejection_reason' => null,
            ]);

            // If quota is now full, automatically reject all other pending bookings with quota reason
            if ($approvedCount + 1 >= $schedule->quota) {
                Booking::where('schedule_id', $booking->schedule_id)
                    ->where('status', 'pending')
                    ->where('id', '!=', $booking->id)
                    ->update([
                        'status' => 'rejected',
                        'rejection_reason' => 'Kuota untuk jadwal ini sudah penuh.',
                    ]);
                
                $message = 'Booking berhasil disetujui! Kuota penuh, booking lainnya otomatis ditolak.';
            } else {
                $message = 'Booking berhasil disetujui!';
            }
        } else {
            // Reject booking with reason
            $booking->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);
            $message = 'Booking berhasil ditolak!';
        }

        return back()->with('success', $message);
    }

    /**
     * Approve a booking request.
     */
    public function approveBooking(Booking $booking)
    {
        // Ensure the booking's schedule belongs to the authenticated dosen
        if ($booking->schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check current approved bookings count
        $approvedCount = Booking::where('schedule_id', $booking->schedule_id)
            ->where('status', 'approved')
            ->count();

        $schedule = $booking->schedule;

        // Check if quota is already full
        if ($approvedCount >= $schedule->quota) {
            return back()->with('error', 'Kuota untuk jadwal ini sudah penuh!');
        }

        // Approve this booking
        $booking->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // If quota is now full, automatically reject all other pending bookings
        if ($approvedCount + 1 >= $schedule->quota) {
            Booking::where('schedule_id', $booking->schedule_id)
                ->where('status', 'pending')
                ->where('id', '!=', $booking->id)
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Kuota untuk jadwal ini sudah penuh.',
                ]);
            
            return back()->with('success', 'Booking berhasil disetujui! Kuota penuh, booking lainnya otomatis ditolak.');
        }

        return back()->with('success', 'Booking berhasil disetujui!');
    }

    /**
     * Reject a booking request.
     */
    public function rejectBooking(Booking $booking)
    {
        // Ensure the booking's schedule belongs to the authenticated dosen
        if ($booking->schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->update(['status' => 'rejected']);

        return back()->with('success', 'Booking berhasil ditolak!');
    }

    /**
     * Show counseling requests for dosen.
     */
    public function counselingRequests()
    {
        $user = Auth::user();
        $requests = CounselingRequest::with('mahasiswa')
            ->where('dosen_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.counseling-requests', compact('requests'));
    }

    /**
     * Update counseling request status.
     */
    public function updateCounselingStatus(Request $request, CounselingRequest $counselingRequest)
    {
        // Ensure the request belongs to the authenticated dosen
        if ($counselingRequest->dosen_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        $counselingRequest->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        $message = $request->status === 'approved' 
            ? 'Permintaan bimbingan berhasil disetujui!' 
            : 'Permintaan bimbingan berhasil ditolak!';

        return back()->with('success', $message);
    }
}
