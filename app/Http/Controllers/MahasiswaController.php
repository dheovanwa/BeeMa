<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Show the mahasiswa dashboard.
     */
    public function dashboard()
    {
        return view('dashboard.mahasiswa');
    }
}
