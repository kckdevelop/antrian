@extends('layout.admin')
@section('title', 'Laporan Antrian')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Laporan & Riwayat Antrian</h1>

    <!-- Form Filter -->
    <div class="bg-white p-5 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                <input type="datetime-local" name="tanggal_awal"
                       value="{{ request('tanggal_awal', now()->startOfDay()->format('Y-m-d\TH:i')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="datetime-local" name="tanggal_akhir"
                       value="{{ request('tanggal_akhir', now()->endOfDay()->format('Y-m-d\TH:i')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Unit / Layanan</label>
                <select name="unit_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->unit }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- <div>
                <label class="block text-sm font-medium text-gray-700">Loket</label>
                <select name="loket_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Loket</option>
                    @foreach($loket as $l)
                        <option value="{{ $l->id }}" {{ request('loket_id') == $l->id ? 'selected' : '' }}>
                            {{ $l->nama_loket }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="dipanggil" {{ request('status') == 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div> --}}
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('laporan.export') }}&{{ http_build_query(request()->except('page')) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📥 Ekspor CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dipanggil Pada</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Input</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($log_panggilans as $item)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 text-lg font-bold text-red-600">{{ $item->nomor_antrian }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->unit?->unit ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->loket?->nama_loket ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($item->status == 'dipanggil') bg-yellow-100 text-yellow-800
                            @elseif($item->status == 'diproses') bg-blue-100 text-blue-800
                            @elseif($item->status == 'selesai') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                        {{ $item->dipanggil_at?->format('d-m-Y H:i') ?? '-' }}
                    </td>
                    
                    <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginasi -->
        <div class="p-4">
            {{ $log_panggilans->links() }}
        </div>
    </div>
</div>
@endsection