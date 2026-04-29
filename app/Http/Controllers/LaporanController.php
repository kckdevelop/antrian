<?php

namespace App\Http\Controllers;

use App\Models\LogPanggilan;
use App\Models\Unit;
use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan dengan filter
     */
    public function index(Request $request)
    {
        $query = LogPanggilan::with(['unit', 'loket']);

        // Filter Tanggal
        $tanggal_awal = $request->input('tanggal_awal', now()->startOfDay());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfDay());

        $query->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);

        // Filter Unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter Loket
        if ($request->filled('loket_id')) {
            $query->where('loket_id', $request->loket_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $log_panggilans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Data untuk filter
        $units = Unit::all();
        $loket = Loket::all();

        return view('admin.laporan.index', compact(
            'log_panggilans',
            'units',
            'loket',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }

    /**
     * Ekspor data ke Excel (opsional – bisa dikembangkan)
     */
    public function export(Request $request)
    {
        // Ambil data seperti di index()
        $query = LogPanggilan::with(['unit', 'loket']);

        $tanggal_awal = $request->input('tanggal_awal', now()->startOfDay());
        $tanggal_akhir = $request->input('tanggal_akhir', now()->endOfDay());

        $query->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('loket_id')) {
            $query->where('loket_id', $request->loket_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        // Untuk demo, kita kembalikan sebagai CSV sederhana
        $filename = "laporan-antrian-" . now()->format('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['Nomor Antrian', 'Unit', 'Loket', 'Status', 'Dipanggil Pada', 'Waktu Input'];

        $callback = function() use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $item) {
                fputcsv($file, [
                    $item->nomor_antrian,
                    $item->unit?->unit ?? '-',
                    $item->loket?->nama ?? '-',
                    ucfirst($item->status),
                    $item->dipanggil_at?->format('d-m-Y H:i') ?? '-',
                    $item->created_at->format('d-m-Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}