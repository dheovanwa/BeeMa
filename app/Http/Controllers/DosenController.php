<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Show the dosen dashboard.
     */
    public function dashboard()
    {
        return view('dashboard.dosen');
    }
}
