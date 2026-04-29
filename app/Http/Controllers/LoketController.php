<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loket;
use App\Models\Panggilan;
use App\Models\LogPanggilan;

class LoketController extends Controller
{
    /**
     * Tampilkan halaman pemanggilan loket.
     */
    public function panggilan($loketId)
    {
        $loket = Loket::with('unit')->findOrFail($loketId);

        // Antrian yang sedang dipanggil di loket ini
        $antrianSekarang = Panggilan::where('loket_id', $loketId)
            ->where('status', 'waiting')
            ->with('unit')
            ->first();

        // Antrian yang sedang diproses
        $antrianProses = Panggilan::where('loket_id', $loketId)
            ->where('status', 'called')
            ->with('unit')
            ->orderBy('dipanggil_at', 'desc')
            ->first();

        // Antrian dilewati (5 terakhir)
        $antrianDilewati = Panggilan::where('status', 'skipped')
            ->with('unit')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.loket.panggilan', compact(
            'loket',
            'antrianSekarang',
            'antrianProses',
            'antrianDilewati'
        ));
    }

    /**
     * Panggil antrian berikutnya (hanya dari unit yang sama)
     */
    public function panggil($loketId)
    {
        $loket = Loket::with('unit')->findOrFail($loketId);

        // Ambil antrian menunggu dari unit yang SAMA
        $antrian = Panggilan::where('unit_id', $loket->unit_id)
            ->where('status', 'waiting')
            ->orderBy('created_at', 'asc') // ambil yang paling awal
            ->first();

        if ($antrian) {
            $antrian->update([
                'loket_id'     => $loketId,
                'status'       => 'waiting',
                'dipanggil_at' => now(),
            ]);

            return redirect()->back()->with('success', "Antrian <strong>{$antrian->nomor_antrian}</strong> telah dipanggil.");
        }

        return redirect()->back()->with('info', 'Tidak ada antrian menunggu untuk unit ini.');
    }

    /**
     * Ulangi panggilan (yang sedang dipanggil)
     */
    public function ulang($loketId, Request $request)
    {
        $request->validate(['nomor_antrian' => 'required|string']);

        $antrian = Panggilan::where('nomor_antrian', $request->nomor_antrian)
            ->where('loket_id', $loketId)
            ->where('status', 'called')
            ->first();

        if ($antrian) {
            // Kembalikan ke menunggu
            $antrian->update([
                'status'       => 'waiting',
                'loket_id'     => null,
                'dipanggil_at' => null,
            ]);

            // Panggil ulang (otomatis ambil yang sama)
            return $this->panggil($loketId);
        }

        return redirect()->back()->with('warning', 'Antrian tidak ditemukan.');
    }

    /**
     * Proses antrian (simpan ke log dan ubah status)
     */
     // ✅ Proses: pindahkan ke log dan ubah status
    public function proses($loketId, Request $request)
    {
        $request->validate(['nomor_antrian' => 'required|string']);

        $antrian = Panggilan::where('nomor_antrian', $request->nomor_antrian)
            ->where('loket_id', $loketId)
            ->where('status', 'called')
            ->first();

        if ($antrian) {
            // Simpan ke log
            LogPanggilan::create([
                'nomor_antrian' => $antrian->nomor_antrian,
                'unit_id'       => $antrian->unit_id,
                'status'      => 'done',
                'loket_id'      => $antrian->loket_id,
                'dipanggil_at'  => $antrian->dipanggil_at,
                'diproses_at'   => now(),
            ]);

            // Update status
            $antrian->update([
                'status' => 'called'
            ]);

            return redirect()->back()->with('info', "Antrian sedang diproses.");
        }

        return redirect()->back()->with('warning', 'Tidak ada antrian yang sedang dipanggil.');
    }

    // ⏭️ Lewati: ubah status jadi dilewati
    public function lewati(Request $request, $loketId)
    {
        $request->validate(['nomor_antrian' => 'required|string']);

        $antrian = Panggilan::where('nomor_antrian', $request->nomor_antrian)
            ->where('loket_id', $loketId)
            ->where('status', 'called')
            ->first();
            

        if ($antrian) {
            $antrian->update([
                'status' => 'skipped',
                'loket_id' => null,
                'dipanggil_at' => null,

            ]);

            return redirect()->back()->with('info', "Antrian <strong>{$antrian->nomor_antrian}</strong> dilewati.");
        }

        return redirect()->back()->with('warning', 'Tidak ada antrian untuk dilewati.');
    }

    // 🔁 Panggil Ulang dari Daftar Dilewati
    public function panggilUlang(Request $request, $loketId)
{
    // Validasi input
    $request->validate([
        'id' => 'required|string|max:255',
    ], [
        'id.required' => 'ID wajib diisi.',
    ]);

    // Cari loket
    $loket = Loket::with('unit')->find($loketId);
    if (!$loket) {
        return redirect()->back()->with('error', 'Loket tidak ditemukan.');
    }

    // Cari antrian yang dilewati
    $antrian = Panggilan::where('id', $request->id)
        ->where('status', 'skipped') // Pastikan statusnya 'dilewati'
        ->first();

    if ($antrian) {
        // Pastikan unit antrian sesuai dengan unit loket
        if ($antrian->unit_id !== $loket->unit_id) {
            return redirect()->back()->with('error', 'Antrian ini bukan dari unit ini.');
        }

        // Kembalikan ke status "dipanggil"
        $antrian->update([
            'status'       => 'waiting',
            'dipanggil_at' => null,
        ]);

        // Panggil ulang → langsung panggil antrian ini
        return $this->panggil($loketId); // Asumsi panggil() ambil antrian menunggu
    } else {
        return redirect()->back()->with('info', 'Panggil ulang gagal: Antrian tidak ditemukan atau belum dilewati.');
    }
}
}