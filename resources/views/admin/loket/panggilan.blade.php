@extends('layout.admin')
@section('title', "Panggilan - {$loket->nama_loket}")
@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-700 text-white rounded-xl shadow-lg p-6 mb-8">
            <h1 class="text-3xl font-bold flex items-center">
                <i class="fas fa-bullhorn mr-3"></i> Pemanggilan Loket
            </h1>
            <p class="mt-2 text-green-100">
                Loket: <strong>{{ $loket->nama_loket }}</strong> | Unit: {{ $loket->unit->unit }}
            </p>
        </div>

        <!-- Tampilan Utama: Antrian Saat Ini -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

            <!-- Card: Antrian Dipanggil -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Antrian Dipanggil</h2>
                @if ($antrianSekarang)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200 rounded-xl p-6">
                        <p class="text-5xl font-extrabold text-gray-900">{{ $antrianSekarang->nomor_antrian }}</p>
                        <p class="text-lg text-gray-700 mt-2">Unit: {{ $antrianSekarang->unit->unit }}</p>
                        <p class="text-sm text-gray-500 mt-1">Dipanggil:
                            {{ $antrianSekarang->dipanggil_at?->format('H:i') }}</p>
                    </div>
                @else
                    <p class="text-gray-500 py-8">Belum ada antrian yang dipanggil</p>
                @endif
            </div>

            <!-- Card: Antrian Diproses -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Sedang Diproses</h2>
                @if ($antrianProses)
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-100 border border-yellow-200 rounded-xl p-6">
                        <p class="text-5xl font-extrabold text-gray-900">{{ $antrianProses->nomor_antrian }}</p>
                        <p class="text-lg text-gray-700 mt-2">Unit: {{ $antrianProses->unit->unit }}</p>
                    </div>
                @else
                    <p class="text-gray-500 py-8">Tidak ada antrian dalam proses</p>
                @endif
            </div>
        </div>
        <!-- Tampilkan Pesan -->
        @if (session('success'))
            <div id="alert-success"
                class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm transition-opacity duration-300">
                <i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}
            </div>

            <script>
                // Hapus pesan setelah 5 detik
                setTimeout(() => {
                    const alert = document.getElementById('alert-success');
                    if (alert) {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s ease-out';
                        setTimeout(() => alert.remove(), 500); // Hapus dari DOM setelah fade
                    }
                }, 5000);
            </script>
        @endif

        @if (session('info'))
            <div id="alert-info"
                class="mb-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-sm transition-opacity duration-300">
                <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
            </div>

            <script>
                // Hapus pesan setelah 5 detik
                setTimeout(() => {
                    const alert = document.getElementById('alert-info');
                    if (alert) {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s ease-out';
                        setTimeout(() => alert.remove(), 500); // Hapus dari DOM setelah fade
                    }
                }, 5000);
            </script>
        @endif
        <!-- Tombol Aksi -->
        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <!-- Panggil -->
            <form action="{{ route('loket.panggil', $loket->id) }}" method="POST">
                @csrf
                @if ($antrianSekarang)
                    <button type="button"
                        class="flex items-center px-8 py-3 bg-gray-400 text-white font-bold rounded-xl cursor-not-allowed"
                        disabled>
                        <i class="fas fa-step-forward mr-3"></i> Tidak Ada
                    </button>
                @else
                    <button type="submit"
                        class="flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow transition transform hover:scale-105">
                        <i class="fas fa-step-forward mr-3"></i> Panggil
                    </button>
                @endif


            </form>

            <!-- Tombol Ulang Panggilan -->
            <form action="{{ route('loket.ulang', $loket->id) }}" method="POST">
                @csrf
                @if ($antrianSekarang)
                    <input type="hidden" name="nomor_antrian" value="{{ $antrianSekarang->nomor_antrian }}">
                    <input type="hidden" name="loketId" value="{{ $loket->id }}">
                    <button type="submit"
                        class="flex items-center px-8 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-xl shadow transition transform hover:scale-105">
                        <i class="fas fa-redo mr-3"></i> Ulang
                    </button>
                @else
                    <button type="button"
                        class="flex items-center px-8 py-3 bg-gray-400 text-white font-bold rounded-xl cursor-not-allowed"
                        disabled>
                        <i class="fas fa-redo mr-3"></i> Tidak Ada
                    </button>
                @endif
            </form>
            <!-- Proses -->
            <form action="{{ route('loket.proses', $loket->id) }}" method="POST">
                @csrf
                @if ($antrianSekarang)
                    <input type="hidden" name="nomor_antrian" value="{{ $antrianSekarang->nomor_antrian }}">
                    <input type="hidden" name="loketId" value="{{ $loket->id }}">
                    <button type="submit"
                        class="flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow transition transform hover:scale-105">
                        <i class="fas fa-check-circle mr-3"></i> Proses
                    </button>
                @else
                    <button type="button"
                        class="flex items-center px-8 py-3 bg-gray-400 text-white font-bold rounded-xl cursor-not-allowed"
                        disabled>
                        <i class="fas fa-check-circle mr-3"></i> Proses
                    </button>
                @endif
            </form>

            <!-- Lewati -->
            <form action="{{ route('loket.lewati', $loket->id) }}" method="POST">
                @csrf
                
                @if ($antrianSekarang)
                 <input type="hidden" name="nomor_antrian" value="{{ $antrianSekarang->nomor_antrian }}">
                    <input type="hidden" name="loketId" value="{{ $loket->id }}">
                    
                    <button type="submit"
                        class="flex items-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow transition transform hover:scale-105">
                        <i class="fas fa-ban mr-3"></i> Lewati
                    </button>
                @else
                    <button type="button"
                        class="flex items-center px-8 py-3 bg-gray-400 text-white font-bold rounded-xl cursor-not-allowed"
                        disabled>
                        <i class="fas fa-ban mr-3"></i> Lewati
                    </button>
                @endif
            </form>
        </div>

        <!-- Daftar Antrian Dilewati -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4">
                <h3 class="text-lg font-bold">Antrian Dilewati</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @if ($antrianDilewati->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-list-ol text-5xl mb-3 text-gray-300"></i>
                        <p>Belum ada antrian yang dilewati</p>
                    </div>
                @else
                    @foreach ($antrianDilewati as $item)
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50">
                            <div>
                                <p class="font-bold text-gray-900">{{ $item->nomor_antrian }}</p>
                                <p class="text-sm text-gray-600">Unit: {{ $item->unit->unit }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-red-600 font-semibold bg-red-100 px-3 py-1 rounded-full">
                                    Dilewati
                                </span>
                                <form action="{{ route('loket.panggil-ulang',$loket->id)}}" method="POST" class="inline">
                                  @csrf
                                  <input type="hidden" name="id" value="{{$item->id}}">
                                  
                                    <button type="submit"
                                        class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                        onclick="return confirm('Yakin ingin panggil ulang antrian ini?')">
                                        <i class="fas fa-redo mr-1"></i> Panggil Ulang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
   
@endsection
