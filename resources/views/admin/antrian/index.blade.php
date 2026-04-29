@extends('layout.admin')
@section('title', 'Reset Antrian Per Unit')
@section('content')

    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-pink-700 text-white rounded-xl shadow-lg p-6 mb-8">
            <h1 class="text-3xl font-bold flex items-center">
                <i class="fas fa-ban mr-3"></i> Reset Antrian Per Unit
            </h1>
            <p class="mt-2 text-red-100">Kosongkan semua antrian untuk unit tertentu.</p>
        </div>

        <!-- Tampilkan Pesan Sukses -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Berhasil:</strong> {!! session('success') !!}
                </div>
            </div>
        @endif

        <!-- Daftar Unit dengan Tombol Reset -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Unit Layanan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Kode Unit</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Jumlah Antrian
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Tbody: Tampilkan jumlah antrian -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($units as $unit)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $unit->unit }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                        {{ $unit->kode_unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                                        {{ $unit->panggilans_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button"
                                        onclick="openResetModal({{ $unit->id }}, '{{ addslashes($unit->unit) }}', '{{ $unit->kode_unit }}')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition shadow-sm hover:shadow">
                                        <i class="fas fa-ban mr-2"></i> Reset Antrian
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-building text-5xl mb-3 text-gray-300"></i>
                                    <p class="text-lg">Belum ada unit layanan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Reset -->
    <div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0"
            id="modalContent">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi Reset</h3>
                </div>
                <button type="button" onclick="closeResetModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Isi -->
            <div class="text-center py-4">
                <i class="fas fa-ban text-6xl text-red-500 mb-4"></i>
                <p class="text-lg font-medium text-gray-800">
                    Yakin ingin mereset semua antrian di unit:
                </p>
                <p class="text-2xl font-bold text-red-600 mt-2" id="modal-unit-name"></p>
                <p class="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <!-- Form Reset -->
            <form id="resetForm" method="POST">
                @csrf
                @method('POST')
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeResetModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl shadow transition transform hover:scale-105">
                        Reset Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openResetModal(unitId, unitName, kodeUnit) {
            const modal = document.getElementById('resetModal');
            const content = document.getElementById('modalContent');

            // Isi data modal
            document.getElementById('modal-unit-name').textContent = `${kodeUnit} - ${unitName}`;
            document.getElementById('resetForm').action = `/admin/antrian/${unitId}`;

            // Tampilkan modal
            modal.classList.remove('hidden');
            void content.offsetWidth;
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }

        function closeResetModal() {
            const modal = document.getElementById('resetModal');
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Tutup modal jika klik di luar
        document.getElementById('resetModal')?.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeResetModal();
        });
    </script>
    <style>
        #modalContent {
            transition: all 0.2s ease-out;
        }
    </style>
@endsection
