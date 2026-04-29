@php
    // Array daftar warna background (Tailwind)
    $bgColors = [
        'bg-red-100 border-red-500',
        'bg-green-100 border-green-500',
        'bg-blue-100 border-blue-500',
        'bg-yellow-100 border-yellow-500',
        'bg-purple-100 border-purple-500',
        'bg-pink-100 border-pink-500',
        'bg-teal-100 border-teal-500',
        'bg-indigo-100 border-indigo-500',
    ];
@endphp

@if($antrianPerUnit && $antrianPerUnit->isNotEmpty())
    @foreach($antrianPerUnit as $unitId => $antrianList)
        @php 
            $item = $antrianList->first(); 
            $randomColor = $bgColors[array_rand($bgColors)];
        @endphp
        <div class="rounded-lg shadow p-3 hover:shadow-md transition animate-fade {{ $randomColor }}">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-bold text-gray-900">LAYANAN : {{ $item->unit->unit }}</p>
                    <p class="text-sm text-gray-600">Kode : {{ $item->unit->kode_unit }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-extrabold text-gray-900">{{ $item->nomor_antrian }}</p>
                    <p class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($item->dipanggil_at)->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="text-center text-gray-500 py-4 text-sm">
        <i class="fas fa-inbox mr-1"></i> Belum ada antrian aktif
    </div>
@endif
