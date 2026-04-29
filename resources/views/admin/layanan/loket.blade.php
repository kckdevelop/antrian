@extends('layout.admin')
@section('title', 'Manajemen Loket')
@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-700 text-white rounded-xl shadow-lg p-6 mb-8">
            <h1 class="text-3xl font-bold flex items-center">
                <i class="fas fa-desktop mr-3"></i> Manajemen Loket
            </h1>
            <p class="mt-2 text-green-100">Pilih unit untuk melihat dan mengelola loket.</p>
        </div>

        <!-- Pilih Unit -->
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <form method="GET" action="{{ route('layanan.loket') }}" class="space-y-4">
                <div>
                    <label for="unit_id" class="block text-sm font-semibold text-gray-800 mb-1">Pilih Unit Layanan</label>
                    <select name="unit_id" id="unit_id" onchange="this.form.submit()"
                        class="w-full md:w-1/2 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                        <option value="">-- Pilih unit --</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->unit }} ({{ $unit->kode_unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @if (request('unit_id'))
            @php
                $selectedUnit = $units->firstWhere('id', request('unit_id'));
                $loketUnit = $lokets->where('unit_id', request('unit_id'));
            @endphp

            <!-- Header Unit Terpilih -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">
                    Loket di Unit : <span class="text-green-700">{{ $selectedUnit?->unit }}</span>
                </h2>
                <button onclick="openModal()"
                    class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Loket
                </button>
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
            <!-- Daftar Loket (Card) -->
            @if ($loketUnit->isEmpty())
                <div class="text-center py-10 bg-white rounded-xl shadow">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                    <p class="text-lg text-gray-600">Belum ada loket untuk unit ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($loketUnit as $item)
        <div class="bg-white rounded-xl shadow p-5 border border-gray-100 transition hover:shadow-md flex flex-col">
            <div class="flex justify-between items-start mb-3">
                <span class="text-sm font-semibold text-green-700">{{ $item->nama_loket }}</span>
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $item->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($item->status) }}
                </span>
            </div>

            <!-- Tombol Edit & Hapus (dalam satu baris) -->
            <div class="flex space-x-2 mt-4">
                <button
                    onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama_loket) }}', '{{ $item->status }}')"
                    class="flex-1 text-sm py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition text-center shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </button>
                <button
                    onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama_loket) }}')"
                    class="flex-1 text-sm py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-center shadow-sm">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>
            </div>

            <!-- Tombol Buka Antrian (Full width, teks di tengah) -->
            <div class="mt-4">
                <a href="/{{ $item->id }}/panggilan"
                   class="block text-center text-sm py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-sm">
                    <i class="fas fa-door-open mr-1"></i> Buka Antrian
                </a>
            </div>
        </div>
    @endforeach
</div>
            @endif
        @else
            <!-- Pesan default saat belum pilih unit -->
            <div class="text-center py-16 bg-white rounded-xl shadow">
                <i class="fas fa-arrow-alt-circle-down text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">Silakan pilih unit layanan untuk melihat loket.</p>
            </div>
        @endif
    </div>

    <!-- Modal Tambah/Edit Loket -->
    <div id="addLoketModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h3 id="modalTitle" class="text-xl font-bold mb-4 text-gray-800">Tambah Loket Baru</h3>

            <form id="loketForm" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
                <input type="hidden" name="id" id="editId">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Loket</label>
                    <input type="text" name="nama_loket" id="nama_loket" required placeholder="Contoh : Loket 1"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition font-medium">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus (Lebih Menarik) -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
            <!-- Header Merah -->
            <div class="bg-red-600 text-white rounded-t-xl p-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                <h3 class="text-xl font-bold">Konfirmasi Hapus</h3>
            </div>

            <!-- Body -->
            <div class="p-6">
                <p class="text-gray-700">Anda yakin ingin menghapus loket berikut?</p>
                <p class="font-semibold text-lg mt-3 text-gray-900" id="deleteLoketName"></p>
                <p class="text-sm text-gray-500 mt-2">Data yang terhapus tidak dapat dikembalikan</p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end space-x-3 p-6 bg-gray-50 rounded-b-xl border-t">
                <button onclick="closeDeleteModal()"
                    class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition shadow-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal Tambah
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Loket Baru';
            document.getElementById('loketForm').action = "{{ route('layanan.loketcreate') }}";
            document.getElementById('loketForm').method = 'POST';
            document.getElementById('loketForm').querySelector('input[name="_method"]').value = 'POST';
            document.getElementById('editId').value = '';
            document.getElementById('nama_loket').value = '';
            document.getElementById('status').value = 'aktif';
            document.getElementById('addLoketModal').classList.remove('hidden');
        }

        // Modal Edit
        function openEditModal(id, nama, status) {
            document.getElementById('modalTitle').textContent = 'Edit Loket';
            document.getElementById('loketForm').action = "{{ url('admin/layanan/loketupdate') }}/" + id;
            document.getElementById('loketForm').method = 'POST';
            document.getElementById('loketForm').querySelector('input[name="_method"]').value = 'PUT';
            document.getElementById('editId').value = id;
            document.getElementById('nama_loket').value = nama;
            document.getElementById('status').value = status;
            document.getElementById('addLoketModal').classList.remove('hidden');
        }

        // Tutup Modal Tambah/Edit
        function closeModal() {
            document.getElementById('addLoketModal').classList.add('hidden');
        }

        // Buka Modal Hapus
        function openDeleteModal(id, nama) {
            document.getElementById('deleteLoketName').textContent = nama;
            document.getElementById('deleteForm').action = "{{ url('admin/layanan/loketdelete') }}/" + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Tutup Modal Hapus
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>

@endsection
