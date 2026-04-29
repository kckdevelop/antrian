@extends('layout.public') {{-- Gunakan layout publik, tanpa sidebar --}}
@section('title', 'Ambil Antrian - SMK MUSABA')
@section('content')

<div class="bg-gradient-to-br from-slate-50 to-slate-100 py-4 px-4" style="min-height: 600px">
  <!-- Header -->
  <div class="text-center mb-10">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Ambil Antrian</h1>
    <p class="text-lg text-gray-600">Pilih unit layanan untuk mengambil nomor antrian Anda.</p>
  </div>

  <!-- Daftar Unit dalam Card -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
    @foreach ($units as $unit)
      @php
        // Warna background card (konsisten berdasarkan ID unit)
        $colors = [
          'bg-gradient-to-br from-blue-500 to-blue-700',
          'bg-gradient-to-br from-green-500 to-green-700',
          'bg-gradient-to-br from-purple-500 to-purple-700',
          'bg-gradient-to-br from-orange-500 to-orange-700',
          'bg-gradient-to-br from-red-500 to-red-700',
          'bg-gradient-to-br from-teal-500 to-teal-700',
          'bg-gradient-to-br from-indigo-500 to-indigo-700',
          'bg-gradient-to-br from-pink-500 to-pink-700',
        ];
        $colorClass = $colors[$unit->id % count($colors)];
      @endphp

      <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
        <!-- Background Warna Unit -->
        <div class="{{ $colorClass }} py-5 px-6 text-white text-center">
          <h3 class="text-2xl font-bold">{{ $unit->unit }}</h3>
          <p class="text-blue-100 text-sm mt-1">Kode: <strong>{{ $unit->kode_unit }}</strong></p>
        </div>

        <!-- Konten Card -->
        <div class="p-6 text-center">
          <p class="text-gray-600 mb-6 text-sm leading-relaxed">
            Layanan: {{ $unit->unit }}<br>
            Loket akan memanggil Anda saat giliran tiba.
          </p>

          <!-- Tombol Ambil Antrian -->
          <form action="{{ route('antrian.ambil') }}" method="POST">
            @csrf
            <input type="hidden" name="unit_id" value="{{ $unit->id }}">
            <button type="submit"
                    class="w-full py-3 bg-white text-gray-800 font-bold rounded-xl shadow-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:scale-105">
              <i class="fas fa-ticket-alt mr-2"></i> Ambil Antrian
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Jika tidak ada unit -->
  @if ($units->isEmpty())
    <div class="text-center py-16">
      <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
      <p class="text-xl text-gray-500">Belum ada unit layanan yang tersedia.</p>
    </div>
  @endif
</div>

@endsection