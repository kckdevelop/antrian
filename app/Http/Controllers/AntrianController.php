<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Panggilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\EscposImage;

class AntrianController extends Controller
{
    public function index()
    {
        // Ambil semua unit dan antrian yang belum dipanggil/hilang
        $units = Unit::withCount('panggilans')->get();


        return view('admin.antrian.index', compact('units'));
    }
    public function resetUnit($unitId)
    {
        $unit = Unit::findOrFail($unitId);

        // Hapus semua antrian di unit ini
        $deleted = Panggilan::where('unit_id', $unitId)->delete();

        return redirect()->back()->with('success', "Antrian di unit <strong>{$unit->unit}</strong> berhasil direset ({$deleted} data dihapus).");
    }
    public function tampilAmbilAntrian()
    {
        $units = Unit::where('status', 'aktif')->get(); // Hanya unit aktif
        return view('display.ambil-antrian', compact('units'));
    }

    public function ambil(Request $request)
    {
        $request->validate(['unit_id' => 'required|exists:units,id']);

        $unit = Unit::findOrFail($request->unit_id);

        // Ambil antrian terakhir hari ini untuk unit ini
        $last = Panggilan::where('unit_id', $unit->id)
            ->whereDate('created_at', today())
            ->latest()
            ->first();

        if ($last) {
            // Pisahkan nomor antrian: contoh "KES-001"
            $parts = explode('-', $last->nomor_antrian);
            $number = (int) $parts[1]; // Ambil bagian angka
            $nextNumber = str_pad($number + 1, 1, '0', STR_PAD_LEFT); // Tambah 1 dan format 2 digit
            $nomor = $unit->kode_unit . '-' . $nextNumber;
        } else {
            // Jika belum ada antrian hari ini
            $nomor = $unit->kode_unit . '-1';
        }

        // Simpan antrian baru
        $antrian = Panggilan::create([
            'nomor_antrian' => $nomor,
            'unit_id' => $unit->id,
            'status' => 'waiting',
            'dipanggil_at' => null,
        ]);



        return redirect()->route('antrian.selesai', $antrian->id);
    }
    public function selesai($id)
    {
        $antrian = Panggilan::with('unit')->findOrFail($id);

        // // Pengaturan printer
        // try {
        //     // 🔧 GANTI INI SESUAI KEBUTUHAN
        //     // Pilihan 1: USB Printer (Windows)
        //     $connector = new WindowsPrintConnector("THERMAL");

        //     // Pilihan 2: Jaringan (Network Printer)
        //     // $connector = new NetworkPrintConnector("192.168.1.100", 9100);

        //     // Pilihan 3: File (Testing)
        //     //$connector = new FilePrintConnector(storage_path('app/public/print-output.txt'));

        //     $printer = new Printer($connector);

        //     // Cetak logo (harus dalam format .png dan ukuran kecil agar muat)
        //     // Pastikan file: public/images/logo-smk.png
        //     // $logo = EscposImage::load(public_path('images/logo-smk.png'), false);
        //     // $printer->setJustification(Printer::JUSTIFY_CENTER);
        //     // $printer->bitImage($logo); // Cetak logo
        //     // $printer->feed(1);

        //     // Header
        //     $printer->setTextSize(2, 2);
        //     $printer->text("ANTRIAN\n");
        //     $printer->setTextSize(1, 1);
        //     $printer->text("SMK MUSABA\n");
        //     $printer->text("Jl. Parangtritis Km 12\n");
        //     $printer->text("----------------------------\n");

        //     // Nomor Antrian (ukuran besar)
        //     $printer->setTextSize(3, 3);
        //     $printer->text($antrian->nomor_antrian . "\n");

        //     // Detail antrian
        //     $printer->setTextSize(1, 1);
        //     $printer->text("Unit Layanan: " . $antrian->unit->unit . "\n");
        //     $printer->text("Waktu: " . $antrian->created_at->format('d-m-Y H:i') . "\n");
        //     $printer->text("----------------------------\n");

        //     // Pesan
        //     $printer->text("Silakan menunggu hingga\n");
        //     $printer->text("nomor Anda dipanggil.\n\n");

        //     // Ucapan terima kasih
        //     $printer->setEmphasis(true);
        //     $printer->text("TERIMA KASIH\n");
        //     $printer->setEmphasis(false);
        //     $printer->text("Aplikasi dibuat oleh:\n");
        //     $printer->text("Technopark PPLG Musaba\n");

        //     // Jarak akhir
        //     $printer->feed(3);
        //     $printer->cut(); // Potong kertas

        // } catch (\Exception $e) {
        //     // Tangani error (misal: printer tidak terhubung)
        //     Log::error("Gagal mencetak struk: " . $e->getMessage());
        // } finally {
        //     $printer->close();
        // }


        // Tampilkan halaman sukses
        return view('display.selesai', compact('antrian'));
    }
    public function cetak($id)
{
    $antrian = Panggilan::with('unit')->findOrFail($id);
    return view('display.print', compact('antrian'));
}
}
