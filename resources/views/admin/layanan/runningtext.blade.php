@extends('layout.admin')
@section('title', 'Atur Running Text')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen">
  <!-- Header -->
  <div class="bg-gradient-to-r from-purple-600 to-pink-700 text-white rounded-xl shadow-lg p-6 mb-8">
    <h1 class="text-3xl font-bold flex items-center">
      <i class="fas fa-scroll mr-3"></i> Atur Running Text
    </h1>
    <p class="mt-2 text-purple-100">Kelola teks berjalan untuk tampilan layar antrian atau dashboard.</p>
  </div>

  <!-- Tampilkan Pesan Sukses -->
  @if (session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
      <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Berhasil:</strong> {{ session('success') }}
      </div>
    </div>
  @endif

  <!-- Preview Running Text -->
  <div class="bg-white rounded-xl shadow-lg p-4 mb-8 overflow-hidden">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Preview Running Text</h3>
    <div class="bg-gray-100 rounded-lg p-3">
      <div class="whitespace-nowrap overflow-hidden">
        <div class="inline-block animate-scroll text-gray-800 font-medium text-lg">
          {{ $runningText->text ?: 'Tidak ada teks yang ditampilkan...' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Form Pengaturan -->
  <div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Pengaturan Running Text</h2>

    <form action="{{ route('layanan.runningtextupdate') }}" method="POST">
      @csrf

      <div class="mb-6">
        <label for="text" class="block text-sm font-semibold text-gray-800 mb-2">Isi Text</label>
        <textarea name="text" id="text" rows="3" maxlength="500"
                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  placeholder="Masukkan teks yang akan berjalan...">{{ old('text', $runningText->text) }}</textarea>
        <p class="text-sm text-gray-500 mt-1">Maksimal 500 karakter</p>
      </div>

      <div class="flex items-center mb-6">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               class="h-5 w-5 text-purple-600 rounded focus:ring-purple-500"
               {{ $runningText->is_active ? 'checked' : '' }}>
        <label for="is_active" class="ml-2 text-gray-800 font-medium">Aktifkan Running Text</label>
      </div>

      <div class="flex justify-end">
        <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-xl shadow transition transform hover:scale-105">
          <i class="fas fa-save mr-2"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Animasi Scroll -->
<style>
  @keyframes scroll {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
  }
  .animate-scroll {
    animation: scroll 20s linear infinite;
    white-space: nowrap;
  }
</style>

@endsection