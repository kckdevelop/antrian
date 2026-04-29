<?php

namespace App\Http\Controllers;
use App\Models\VideoSetting;
use App\Models\RunningText;
use App\Models\Panggilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DisplayController extends Controller
{
    public function index()
    {
        $video = VideoSetting::where('is_active', true)->first();
$runningText = RunningText::where('is_active', true)->first();

$antrianPerUnit = Panggilan::with(['unit'])
    ->where('status', 'called')
    ->orderBy('dipanggil_at', 'desc')
    ->get()
    ->groupBy('unit_id');

return view('display.index', compact('video', 'runningText', 'antrianPerUnit'));
        
    }

    public function list()
    {
        $antrianPerUnit = Panggilan::with(['unit', 'loket'])
            ->where('status', 'called')
            ->orderBy('dipanggil_at', 'desc')
            ->get()
            ->groupBy('unit_id');

        // Kembalikan partial view (rekomendasi)
        return view('admin.partials.antrian-listdisplay', compact('antrianPerUnit'))->render();
    }
}
