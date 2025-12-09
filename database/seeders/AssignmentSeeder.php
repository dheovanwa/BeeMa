<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users by email
        $m1 = User::where('email', 'm@1')->first();
        $m2 = User::where('email', 'm@2')->first();
        $m3 = User::where('email', 'm@3')->first();
        $m4 = User::where('email', 'm@4')->first();
        
        $d2 = User::where('email', 'd@2')->first();
        $d3 = User::where('email', 'd@3')->first();
        $d4 = User::where('email', 'd@4')->first();

        // Create assignments
        Assignment::create([
            'mahasiswa_id' => $m1->id,
            'dosen_id' => $d2->id,
        ]);

        Assignment::create([
            'mahasiswa_id' => $m2->id,
            'dosen_id' => $d2->id,
        ]);

        Assignment::create([
            'mahasiswa_id' => $m3->id,
            'dosen_id' => $d3->id,
        ]);

        Assignment::create([
            'mahasiswa_id' => $m4->id,
            'dosen_id' => $d4->id,
        ]);
    }
}
