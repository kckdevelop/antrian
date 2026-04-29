<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Loket;
use App\Models\User;
use App\Models\Panggilan;

class DashboardController extends Controller
{
    public function index()
    {

    $totalUnit = Unit::count();
    $totalLoketAktif = Loket::where('status', 'aktif')->count();
    $totalUser = User::count();

    // Ambil antrian dengan status 'dipanggil', urutkan terbaru dulu
    $panggilanAktif = Panggilan::with(['unit', 'loket'])
        ->where('status', 'called')
        ->orderBy('dipanggil_at', 'desc')
        ->get();

    // Kelompokkan per unit_id
    $antrianPerUnit = $panggilanAktif->groupBy('unit_id');

    return view('admin.dashboard.index', compact(
        'totalUnit',
        'totalLoketAktif',
        'totalUser',
        'antrianPerUnit'
    ));
    }
}