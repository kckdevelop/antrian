<?php

namespace App\Http\Controllers;

use App\Models\Panggilan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PanggilanController extends Controller
{
    public function tampilLayar()
    {
        return view('layar-antrian'); // Blade view
    }

    public function getNextWaiting()
    {
        $panggilan = Panggilan::with(['unit', 'loket'])
            ->where('status', 'waiting')
            ->whereNotNull('loket_id') // Tambahkan ini
            ->orderBy('created_at', 'asc') // Ambil yang paling lama menunggu
            ->first();

        if ($panggilan) {
            $panggilan->update(['status' => 'called', 'dipanggil_at' => Carbon::now()]);
            return response()->json([
                'id' => $panggilan->id,
                'nomor_antrian' => $panggilan->nomor_antrian,
                'status' => $panggilan->status,
                'unit' => [
                    'unit' => $panggilan->unit->unit ?? 'Unit Tidak Dikenal'
                ],
                'loket' => [
                    'nama_loket' => $panggilan->loket->nama_loket ?? 'Loket Tidak Dikenal'
                ]
            ]);


            // update jadi 'called'


        }

        // Tidak ada antrian
        return response()->json(null);
    }
}
