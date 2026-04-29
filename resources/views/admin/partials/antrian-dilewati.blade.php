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
                <form action="{{ route('loket.panggil-ulang') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <input type="hidden" name="loket_id" value="{{ $item->loket_id }}">
                    <button type="submit"
                            class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                            onclick="return confirm('Yakin ingin panggil ulang antrian ini?')">
                        <i class="fas fa-redo mr-1"></i> Panggil Ulang
                    </button>
                </form>
            </div>
        </div>
    @endforeach
@endforeach
@endif