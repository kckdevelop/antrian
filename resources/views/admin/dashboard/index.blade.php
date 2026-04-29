@extends('layout.admin')
@section('title', 'Dashboard Admin')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen">

  <!-- Header -->
  <div class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white rounded-xl shadow-lg p-6 mb-8">
    <h1 class="text-3xl font-bold flex items-center">
      <i class="fas fa-tachometer-alt mr-3"></i> Dashboard Sistem Antrian
    </h1>
    <p class="mt-2 text-indigo-100">Ringkasan aktivitas dan status layanan.</p>
  </div>

  <!-- Stat Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Total Unit -->
    <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 border border-gray-100">
      <div class="p-3 bg-blue-100 rounded-full">
        <i class="fas fa-building text-blue-600 text-2xl"></i>
      </div>
      <div>
        <p class="text-sm font-semibold text-gray-600">Total Unit</p>
        <p class="text-3xl font-bold text-gray-900">{{ $totalUnit }}</p>
      </div>
    </div>

    <!-- Loket Aktif -->
    <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 border border-gray-100">
      <div class="p-3 bg-green-100 rounded-full">
        <i class="fas fa-desktop text-green-600 text-2xl"></i>
      </div>
      <div>
        <p class="text-sm font-semibold text-gray-600">Loket Aktif</p>
        <p class="text-3xl font-bold text-gray-900">{{ $totalLoketAktif }}</p>
      </div>
    </div>

    <!-- Total User -->
    <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 border border-gray-100">
      <div class="p-3 bg-purple-100 rounded-full">
        <i class="fas fa-users text-purple-600 text-2xl"></i>
      </div>
      <div>
        <p class="text-sm font-semibold text-gray-600">Total Pengguna</p>
        <p class="text-3xl font-bold text-gray-900">{{ $totalUser }}</p>
      </div>
    </div>
  </div>

  <<!-- Daftar Antrian Saat Ini per Unit -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gray-800 text-white px-6 py-4">
        <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-stream mr-2"></i> Antrian Saat Ini per Unit
        </h2>
        <p class="text-sm text-gray-300 mt-1">Nomor antrian yang sedang dipanggil di setiap unit</p>
    </div>

    <div class="divide-y divide-gray-200" id="antrian-container">
        @if($antrianPerUnit->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                <p class="text-lg">Belum ada antrian yang dipanggil.</p>
            </div>
        @else
            @foreach($antrianPerUnit as $unitId => $antrianList)
                @php
                    $item = $antrianList->first(); // Ambil antrian terbaru di unit ini
                @endphp
                <div class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-3 md:mb-0">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $item->unit->unit }}
                                <span class="inline-block ml-2 px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                    {{ $item->unit->kode_unit }}
                                </span>
                            </h3>
                            <p class="text-sm text-gray-600">
                                Dilayani di: <strong>{{ $item->loket?->nama_loket ?? 'Loket tidak diketahui' }}</strong>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-extrabold text-gray-900">{{ $item->nomor_antrian }}</p>
                            <p class="text-xs text-gray-500">
                                Dipanggil pada: {{ $item->dipanggil_at ? \Carbon\Carbon::parse($item->dipanggil_at)->format('H:i') : '–' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

  <!-- Tombol Refresh Manual -->
  <div class="mt-6 text-center">
    <button onclick="refreshDashboard()" class="inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow transition">
      <i class="fas fa-sync-alt mr-2"></i> Refresh
    </button>
  </div>
</div>

<script>
// Fungsi refresh data (manual)
function refreshDashboard() {
  // Ganti dengan AJAX jika ingin tanpa reload
  location.reload();
}

// Opsi: Auto-refresh setiap 30 detik
// setInterval(refreshDashboard, 30000);
</script>
@endsection