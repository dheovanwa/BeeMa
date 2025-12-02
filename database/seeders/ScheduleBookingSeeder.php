<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Dosen 2 (d@2)
        $dosen2 = User::where('email', 'd@2')->first();

        // Create a schedule for Dosen 2 with quota 1
        $schedule = Schedule::create([
            'user_id' => $dosen2->id,
            'date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'quota' => 1,
            'status' => 'open',
        ]);

        // Get some mahasiswa users
        $mahasiswa1 = User::where('email', 'm@1')->first();
        $mahasiswa2 = User::where('email', 'm@2')->first();
        $mahasiswa3 = User::where('email', 'm@3')->first();

        // Create bookings for this schedule
        Booking::create([
            'user_id' => $mahasiswa1->id,
            'schedule_id' => $schedule->id,
            'file_path' => null, // No file for demo
            'status' => 'pending',
            'message' => 'Saya ingin bimbingan untuk tugas akhir bab 1',
        ]);

        Booking::create([
            'user_id' => $mahasiswa2->id,
            'schedule_id' => $schedule->id,
            'file_path' => null, // No file for demo
            'status' => 'pending',
            'message' => 'Mohon bimbingan untuk revisi proposal',
        ]);

        Booking::create([
            'user_id' => $mahasiswa3->id,
            'schedule_id' => $schedule->id,
            'file_path' => null, // No file for demo
            'status' => 'pending',
            'message' => 'Perlu bimbingan untuk metodologi penelitian',
        ]);
    }
}
