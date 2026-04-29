@extends('layout.admin')
@section('title', 'Layanan Unit')
@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header dengan Gradien -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold flex items-center">
                        <i class="fas fa-building mr-3"></i> Manajemen Unit
                    </h1>
                    <p class="mt-2 text-blue-100">Kelola unit pelayanan secara efisien</p>
                </div>
                <button type="button" onclick="openModal()"
                    class="mt-4 md:mt-0 inline-flex items-center px-5 py-3 bg-white text-blue-700 hover:bg-gray-100 font-semibold rounded-xl shadow-md transition transform hover:scale-105 duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Unit
                </button>
            </div>
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
        <!-- Tabel Utama -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-gray-700 uppercase tracking-wide">
                            <th class="px-8 py-4">#</th>
                            <th class="px-8 py-4">Nama Unit</th>
                            <th class="px-8 py-4">Kode Unit</th>
                            <th class="px-8 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($units as $unit)
                            <tr class="hover:bg-blue-50 transition duration-200 ease-in-out transform hover:translate-x-0">
                                <td class="px-8 py-4 text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-8 py-4">
                                    <span class="font-medium text-gray-900">{{ $unit->unit }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span
                                        class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                        {{ $unit->kode_unit }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 space-x-2">
                                    <!-- Edit Button -->
                                    <button type="button"
                                        onclick="openEditModal(
                                          {{ $unit->id }},
                                          '{{ addslashes($unit->unit) }}',
                                          '{{ addslashes($unit->kode_unit) }}'
                                        )"
                                        class="inline-flex items-center px-3.5 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg text-sm font-medium transition duration-150 shadow-sm hover:shadow">
                                        <i class="fas fa-edit mr-1.5"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button"
                                        onclick="openDeleteModal({{ $unit->id }}, '{{ addslashes($unit->unit) }}')"
                                        class="inline-flex items-center px-3.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-medium transition duration-150 shadow-sm hover:shadow">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                    <p class="text-lg font-medium">Belum ada data unit</p>
                                    <p class="text-sm">Silakan tambahkan unit pelayanan pertama Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginasi -->
        <div class="mt-6 flex justify-center">
            {{ $units->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- Modal Tambah Unit -->
    <div id="addUnitModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 ease-out scale-95 opacity-0"
            id="modalContent">

            <!-- Header Modal -->
            <div class="flex items-center bg-blue justify-between border-b border-gray-200 pb-4 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-building text-white-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white-900">Tambah Unit Baru</h3>
                </div>
                <button type="button" onclick="closeModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('layanan.unitcreate') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <!-- Nama Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-semibold text-gray-800 mb-1">Nama Unit</label>
                        <input type="text" name="unit" id="unit" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm placeholder-gray-400"
                            placeholder="Contoh: Kesehatan">
                    </div>

                    <!-- Kode Unit -->
                    <div>
                        <label for="kode_unit" class="block text-sm font-semibold text-gray-800 mb-1">Kode Unit</label>
                        <input type="text" name="kode_unit" id="kode_unit" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm placeholder-gray-400"
                            placeholder="Contoh: K">
                    </div>
                </div>

                <!-- Footer Tombol -->
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md transition duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-1.5"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Unit -->
    <div id="editUnitModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 ease-out scale-95 opacity-0"
            id="editModalContent">

            <!-- Header Modal -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-edit text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Edit Unit</h3>
                </div>
                <button type="button" onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Form Edit -->
            <form id="editUnitForm" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <!-- Nama Unit -->
                    <div>
                        <label for="edit_unit" class="block text-sm font-semibold text-gray-800 mb-1">Nama Unit</label>
                        <input type="hidden" name="id_unit" id="id_unit" required>
                        <input type="text" name="unit" id="edit_unit" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm placeholder-gray-400"
                            placeholder="Contoh: Kesehatan">
                    </div>

                    <!-- Kode Unit -->
                    <div>
                        <label for="edit_kode_unit" class="block text-sm font-semibold text-gray-800 mb-1">Kode
                            Unit</label>
                        <input type="text" name="kode_unit" id="edit_kode_unit" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm placeholder-gray-400"
                            placeholder="Contoh: K">
                    </div>
                </div>

                <!-- Footer Tombol -->
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 rounded-xl shadow-md transition duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-save mr-1.5"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
  <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 ease-out scale-95 opacity-0" id="deleteModalContent">
    
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-200">
      <div class="flex items-center space-x-3">
        <div class="p-2 bg-red-100 rounded-lg">
          <i class="fas fa-trash-alt text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Konfirmasi Hapus</h3>
      </div>
      <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-times text-lg"></i>
      </button>
    </div>

    <!-- Isi -->
    <div class="text-center py-4">
      <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
      <p class="text-lg text-gray-800 font-medium">Apakah Anda yakin ingin menghapus unit ini?</p>
      <p class="text-sm text-gray-500 mt-2">
        <strong id="delete-unit-name"></strong><br>
        Data yang dihapus tidak dapat dikembalikan.
      </p>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex justify-end space-x-3 mt-6">
      <button type="button" onclick="closeDeleteModal()"
              class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-xl transition">
        Batal
      </button>
      <form id="deleteForm" action="#" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl shadow transition transform hover:scale-105">
          Hapus
        </button>
      </form>
    </div>
  </div>
</div>

    <script>
        // Fungsi untuk membuka modal tambah
        function openModal() {
            const modal = document.getElementById('addUnitModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            void content.offsetWidth;
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }

        // Fungsi untuk menutup modal tambah
        function closeModal() {
            const modal = document.getElementById('addUnitModal');
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Fungsi untuk membuka modal edit
        function openEditModal(id, unitName, kodeUnit) {
            const modal = document.getElementById('editUnitModal');
            const content = document.getElementById('editModalContent');

            // Isi form dengan data
            document.getElementById('edit_unit').value = unitName;
            document.getElementById('edit_kode_unit').value = kodeUnit;
            document.getElementById('editUnitForm').action = `/admin/layanan/unitupdate/${id}`; // Sesuaikan route

            // Tampilkan modal
            modal.classList.remove('hidden');
            void content.offsetWidth;
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            const modal = document.getElementById('editUnitModal');
            const content = document.getElementById('editModalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Tutup modal jika klik di luar konten
        document.getElementById('addUnitModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.getElementById('editUnitModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // Animasi fade-in header
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector('.bg-gradient-to-r')?.classList.add('animate-fade-in-down');
        });

        // Fungsi buka modal hapus
function openDeleteModal(id, unitName) {
  const modal = document.getElementById('deleteModal');
  const content = document.getElementById('deleteModalContent');
  
  // Isi nama unit
  document.getElementById('delete-unit-name').textContent = unitName;
  
  // Atur action form hapus
  const form = document.getElementById('deleteForm');
  form.action = `/admin/layanan/unitdelete/${id}`; // Sesuaikan dengan route delete

  // Tampilkan modal
  modal.classList.remove('hidden');
  void content.offsetWidth;
  content.classList.remove('scale-95', 'opacity-0');
  content.classList.add('scale-100', 'opacity-100');
}

// Fungsi tutup modal hapus
function closeDeleteModal() {
  const modal = document.getElementById('deleteModal');
  const content = document.getElementById('deleteModalContent');
  content.classList.add('scale-95', 'opacity-0');
  setTimeout(() => {
    modal.classList.add('hidden');
  }, 200);
}

// Tutup modal jika klik di luar
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeDeleteModal();
});
    </script>
    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.6s ease-out;
        }

        /* Transisi halus untuk modal */
        #modalContent {
            transition: all 0.2s ease-out;
        }
    </style>
@endsection
