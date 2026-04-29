@extends('layout.public')
@section('title', 'Nomor Antrian')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="bg-white rounded-2xl shadow-xl p-10 text-center max-w-md w-full border border-gray-200">

        <!-- Ikon Tiket -->
        <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-ticket-alt text-4xl text-green-600"></i>
        </div>

        <!-- Nomor Antrian -->
        <h1 class="text-5xl font-extrabold text-gray-800 mb-4">{{ $antrian->nomor_antrian }}</h1>

        <!-- Detail -->
        <p class="text-gray-600 mb-2"><strong>Unit:</strong> {{ $antrian->unit->unit }}</p>
        <p class="text-gray-500 text-sm">Waktu: {{ $antrian->created_at->format('d-m-Y H:i') }}</p>

        <!-- Pesan -->
        <div class="mt-6 p-4 bg-blue-50 text-blue-800 rounded-lg text-sm">
            Silakan menunggu hingga nomor Anda dipanggil.
        </div>

        <!-- Tombol Cetak Ulang -->
        <div class="mt-6">
            <a href="{{ route('antrian.cetak', $antrian->id) }}"
               class="inline-block px-6 py-2 bg-gray-700 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition duration-200">
                <i class="fas fa-print mr-1"></i> Cetak Antrian
            </a>
        </div>

        <!-- Error Printer (Opsional) -->
        @if(session('print_error'))
            <div class="mt-4 p-3 bg-red-100 text-red-700 text-xs rounded-lg">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ session('print_error') }}
            </div>
        @endif
    </div>
</div>

<!-- Auto-redirect setelah 4 detik -->
{{-- <script>
    setTimeout(() => {
        window.location.href = "{{ route('antrian.tampil') }}";
    }, 4000);
</script> --}}
@endsection