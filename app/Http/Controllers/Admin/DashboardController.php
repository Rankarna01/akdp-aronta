<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Nanti di sini kita akan panggil Model (Armada::count(), Tiket::sum('total'), dll)
        // Untuk sekarang kita siapkan UI/UX-nya terlebih dahulu.
        
        return view('admin.dashboard.index');
    }
}