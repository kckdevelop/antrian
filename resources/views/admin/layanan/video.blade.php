@extends('layout.admin')
@section('title', 'Atur Video')
@section('content')

<div class="p-6 bg-gray-50 min-h-screen">
  <!-- Header -->
  <div class="bg-gradient-to-r from-red-600 to-pink-700 text-white rounded-xl shadow-lg p-6 mb-8">
    <h1 class="text-3xl font-bold flex items-center">
      <i class="fas fa-video mr-3"></i> Atur Video
    </h1>
    <p class="mt-2 text-red-100">Kelola video promosi, tutorial, atau layar utama.</p>
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

  <!-- Preview Video -->
  <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Video</h3>
    <div class="bg-gray-100 rounded-lg overflow-hidden">
      @if ($video->is_active && ($video->video_path || $video->embed_url))
        @if ($video->embed_url)
          <iframe 
            src="{{ $video->embed_url }}" 
            class="w-full aspect-video" 
            frameborder="0" 
            allowfullscreen>
          </iframe>
        @elseif ($video->video_path)
          <video controls class="w-full aspect-video" controlsList="nodownload">
            <source src="{{ asset('storage/' . $video->video_path) }}" type="video/{{ pathinfo($video->video_path, PATHINFO_EXTENSION) }}">
            Browser Anda tidak mendukung pemutar video.
          </video>
        @endif
        <p class="p-4 text-center text-gray-600">{{ $video->title }}</p>
      @else
        <div class="p-12 text-center text-gray-500">
          <i class="fas fa-video-slash text-6xl mb-3"></i>
          <p class="text-lg">Tidak ada video yang aktif</p>
        </div>
      @endif
    </div>
  </div>

  <!-- Form Pengaturan -->
  <div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Unggah atau Embed Video</h2>

    <form id="videoForm" action="{{ route('layanan.videoupdate') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-6">
        <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">Judul Video</label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $video->title) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500"
               placeholder="Contoh: Video Promosi Layanan">
      </div>

      <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-800 mb-2">Sumber Video</label>
        
        <!-- Upload File -->
        <div>
          <input type="file" name="video_file" accept="video/*" id="videoFile"
                 class="w-full border border-gray-300 rounded-xl px-3 py-2">
          <p class="text-xs text-gray-500 mt-1">Format: MP4, MOV, AVI (max 100MB)</p>
          @if ($video->video_path)
            <p class="text-xs text-green-600 mt-1">File saat ini: {{ basename($video->video_path) }}</p>
          @endif
        </div>

        <!-- Progress Bar -->
        <div id="progressContainer" class="hidden mt-4">
          <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Uploading...</span>
            <span id="progressPercent">0%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="progressBar" class="bg-red-600 h-2 rounded-full transition-all duration-200" style="width: 0%"></div>
          </div>
        </div>
      </div>

      <div class="flex items-center mb-6">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               class="h-5 w-5 text-red-600 rounded focus:ring-red-500"
               {{ $video->is_active ? 'checked' : '' }}>
        <label for="is_active" class="ml-2 text-gray-800 font-medium">Aktifkan Video</label>
      </div>

      <div class="flex justify-end">
        <button type="submit" id="submitBtn"
                class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-medium rounded-xl shadow transition transform hover:scale-105">
          <i class="fas fa-save mr-2"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('videoForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Cegah submit default

    const form = this;
    const fileInput = document.getElementById('videoFile');
    const submitBtn = document.getElementById('submitBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');

    if (!fileInput.files.length) {
        form.submit(); // Kalau tidak ada file, submit biasa
        return;
    }

    // Siapkan FormData
    const formData = new FormData(form);

    // Aktifkan progress bar
    progressContainer.classList.remove('hidden');
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

    // Kirim dengan AJAX
    const xhr = new XMLHttpRequest();

    // Update progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
            progressPercent.textContent = percent + '%';
        }
    });

    // Setelah selesai
    xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
            // Jika sukses, redirect atau tampilkan pesan
            window.location.reload(); // atau tampilkan toast
        } else {
            alert('Upload gagal. Coba lagi.');
            resetForm();
        }
    });

    xhr.addEventListener('error', function() {
        alert('Terjadi kesalahan saat upload.');
        resetForm();
    });

    xhr.open('POST', form.action);
    xhr.send(formData);
});

// Fungsi reset form setelah error
function resetForm() {
    const submitBtn = document.getElementById('submitBtn');
    const progressContainer = document.getElementById('progressContainer');
    
    submitBtn.disabled = false;
    submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
    progressContainer.classList.add('hidden');
}
</script>
@endsection