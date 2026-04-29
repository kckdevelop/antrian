<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\RunningText;
use App\Models\VideoSetting;

class LayananController extends Controller
{
    public function unit()
    {
        $units = Unit::paginate(10); // atau ->get()
        return view('admin.layanan.unit', compact('units')); // Blade view
    }
    public function loket()
    {
        $units = Unit::all();
        $lokets = Loket::with('unit')->get(); // Pastikan relasi unit ada

        return view('admin.layanan.loket', compact('units', 'lokets'));
    }
    public function unitcreate(Request $request)
    {
        // Validasi input
        $request->validate([
            'unit' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:10|unique:units,kode_unit',
        ], [
            'unit.required' => 'Nama unit wajib diisi.',
            'kode_unit.required' => 'Kode unit wajib diisi.',
            'kode_unit.unique' => 'Kode unit sudah digunakan.',
        ]);

        // Simpan ke database
        Unit::create([
            'unit' => $request->unit,
            'kode_unit' => $request->kode_unit,
        ]);

        // Redirect kembali dengan pesan sukses

        return redirect()->back()->with('success', 'Unit berhasil ditambahkan!');
    }
    public function unitupdate(Request $request, $id)
    {

        $request->validate([
            'unit' => 'required|string|max:255',
            'kode_unit' => 'required|string|max:10|unique:units,kode_unit,' . $id,
        ], [
            'unit.required' => 'Nama unit wajib diisi.',
            'kode_unit.required' => 'Kode unit wajib diisi.',
            'kode_unit.unique' => 'Kode unit sudah digunakan oleh unit lain.',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'unit' => $request->unit,
            'kode_unit' => $request->kode_unit,
        ]);

        return redirect()->back()->with('success', 'Unit berhasil diperbarui!');
    }

    public function unitdestroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'Unit berhasil dihapus!');
    }


    public function loketcreate(Request $request)
    {
        // Validasi input
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'nama_loket' => 'required|string|max:255',
            'status' => ['required', Rule::in(['aktif', 'non-aktif'])],
        ], [
            'unit_id.required' => 'Unit layanan wajib dipilih.',
            'unit_id.exists' => 'Unit yang dipilih tidak valid.',
            'nama_loket.required' => 'Nama loket wajib diisi.',
            'status.required' => 'Status loket wajib dipilih.',
            'status.in' => 'Status harus bernilai aktif atau non-aktif.',
        ]);

        // Simpan loket baru
        Loket::create([
            'unit_id' => $request->unit_id,
            'nama_loket' => $request->nama_loket,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Loket berhasil ditambahkan!');
    }

    public function loketupdate(Request $request, $id)
    {
        $loket = Loket::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama_loket' => 'required|string|max:255',
            'status' => ['required', Rule::in(['aktif', 'non-aktif'])],
        ], [
            'nama_loket.required' => 'Nama loket wajib diisi.',
            'status.required' => 'Status loket wajib dipilih.',
            'status.in' => 'Status harus bernilai aktif atau non-aktif.',
        ]);

        // Update data loket
        $loket->update([
            'nama_loket' => $request->nama_loket,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Loket berhasil diperbarui!');
    }

    public function loketdestroy($id)
    {
        $loket = Loket::findOrFail($id);

        // Opsional: Cek apakah loket masih digunakan (jika ada relasi)
        // Misalnya, cek antrian aktif
        // if ($loket->antrians()->where('status', '!=', 'selesai')->exists()) {
        //     return redirect()->back()->with('error', 'Tidak bisa menghapus loket yang masih memiliki antrian aktif.');
        // }

        $loket->delete();

        return redirect()->back()->with('success', 'Loket berhasil dihapus!');
    }

    //running text
    public function runningtext()
    {
        $runningText = RunningText::first(); // Karena cuma satu
        if (!$runningText) {
            $runningText = new RunningText(['text' => '', 'is_active' => false]);
        }
        return view('admin.layanan.runningtext', compact('runningText'));
    }

    public function runningtextupdate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'is_active' => 'boolean'
        ], [
            'text.required' => 'Isi running text wajib diisi.',
            'text.max' => 'Running text maksimal 500 karakter.'
        ]);

        $runningText = RunningText::first();
        if (!$runningText) {
            $runningText = RunningText::create([
                'text' => $request->text,
                'is_active' => $request->has('is_active')
            ]);
        } else {
            // Nonaktifkan semua dulu (jika ada banyak)
            RunningText::where('id', '!=', $runningText->id)->update(['is_active' => false]);

            $runningText->update([
                'text' => $request->text,
                'is_active' => $request->has('is_active')
            ]);
        }

        return redirect()->back()->with('success', 'Running text berhasil diperbarui!');
    }

    public function video()
    {
        $video = VideoSetting::first();
        if (!$video) {
            $video = new VideoSetting(['title' => '', 'is_active' => false]);
        }
        return view('admin.layanan.video', compact('video'));
    }

    public function videoupdate(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:122400', // max 100MB
            'embed_url' => 'nullable|url',
            'is_active' => 'boolean',
        ], [
            'video_file.max' => 'Video maksimal 100MB.',
            'video_file.mimes' => 'Format video harus mp4, mov, avi, atau wmv.',
        ]);

        $video = VideoSetting::first();
        $oldPath = $video?->video_path;

        // Handle upload file
        if ($request->hasFile('video_file')) {
            // Hapus file lama jika ada
            if ($oldPath && Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }

            $path = $request->file('video_file')->store('videos', 'public');
            // Simpan ke storage/app/public/videos
            $fileName = str_replace('public/', '', $path);
        } else {
            $fileName = $video?->video_path;
        }

        // Simpan atau update
        if (!$video) {
            VideoSetting::create([
                'title' => $request->title,
                'video_path' => $fileName,
                'embed_url' => $request->embed_url,
                'is_active' => $request->has('is_active'),
            ]);
        } else {
            // Nonaktifkan video lain jika ada banyak
            VideoSetting::where('id', '!=', $video->id)->update(['is_active' => false]);

            $video->update([
                'title' => $request->title,
                'video_path' => $fileName,
                'embed_url' => $request->embed_url,
                'is_active' => $request->has('is_active'),
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan video berhasil diperbarui!');
    }
}
