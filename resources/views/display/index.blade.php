<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Antrian - SMK MUSABA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes zoomIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-zoom {
            animation: zoomIn 0.8s ease-out;
        }

        /* Running Text */
        .running-text {
            background: linear-gradient(90deg, #1e3a8a, #1e40af);
            color: white;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            padding: 12px 0;
            box-shadow: inset 0 -2px 5px rgba(0,0,0,0.2);
        }
        .running-text span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 100s linear infinite;
            font-size: 1.2rem;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Tombol Aktifkan Suara -->
    <div class="fixed top-4 left-4 z-50">
        <button id="enableSound"
            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-4 py-2 rounded-lg shadow-lg transition">
            🔊 AKTIFKAN SUARA
        </button>
    </div>

    <!-- Header -->
    <header class="bg-blue-600 text-white flex justify-between items-center px-6 py-4 shadow-lg">
        <div>
            <h1 class="font-bold text-lg">SISTEM ANTRIAN</h1>
            
        </div>
        <div class="text-right">
            <span id="tanggal" class="font-medium"></span> | 
            <span id="jam" class="text-xl font-mono animate-pulse"></span>
        </div>
    </header>

   

    <!-- Content -->
    <main class="grid grid-cols-3 gap-4 p-6 h-[calc(100vh-200px)]">
    <!-- Kolom Kiri: Video Profil (2 baris) -->
    <div class="col-span-2 row-span-2 flex justify-center items-center bg-black rounded-2xl overflow-hidden">
        @if ($video->is_active && ($video->video_path || $video->embed_url))
            @if ($video->embed_url)
                <iframe 
                    src="{{ $video->embed_url }}" 
                    class="w-full aspect-video rounded-lg" 
                    frameborder="0" 
                    allowfullscreen>
                </iframe>
            @elseif ($video->video_path)
                <video controls class="w-full aspect-video" controlsList="nodownload" autoplay muted loop>
                    <source src="{{ asset('storage/' . $video->video_path) }}" 
                            type="video/{{ pathinfo($video->video_path, PATHINFO_EXTENSION) }}">
                    Browser Anda tidak mendukung pemutar video.
                </video>
            @endif
        @else
            <div class="text-center text-gray-400 p-8">
                <i class="fas fa-video-slash text-6xl mb-3 opacity-50"></i>
                <p class="text-lg">Tidak ada video aktif</p>
            </div>
        @endif
    </div>

    <!-- Kolom Kanan Atas: Card Panggilan Baru -->
    {{-- <div class="bg-gradient-to-br from-fuchsia-600 to-purple-700 text-white rounded-2xl shadow-2xl p-6 flex flex-col items-center text-center relative overflow-hidden">
        <!-- Efek Glow --> --}}
        <div id="antrian-box"
             class="col-span-1 bg-gradient-to-br from-fuchsia-600 to-purple-700 text-white rounded-xl shadow-xl flex flex-col items-center justify-center p-6 text-center relative overflow-hidden">
            
            <!-- Efek Glow -->
            <div class="absolute w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -top-10 -right-10"></div>

            <!-- Badge -->
            <div class="bg-white bg-opacity-20 backdrop-blur-sm px-5 py-2 rounded-full text-xs font-bold mb-3 animate-pulse flex items-center gap-1">
                <i class="fas fa-bullhorn"></i> PANGGILAN TERBARU
            </div>

            <!-- Unit -->
            <h3 id="unit" class="text-lg font-semibold mb-2 opacity-90">
                UNIT PELAYANAN
            </h3>

            <!-- Nomor Antrian -->
            <p id="nomorantrian"
               class="text-5xl font-extrabold my-4 bg-white bg-opacity-10 px-6 py-3 rounded-lg min-h-20 flex items-center justify-center w-52">
                -
            </p>

            <!-- Loket -->
            <h3 id="loket" class="text-lg font-medium">
                <span class="bg-white text-fuchsia-700 px-4 py-1 rounded-full font-bold text-sm">LOKET</span>
            </h3>
        </div>
    {{-- </div> --}}
    <!-- Kolom Kanan Bawah: Daftar Antrian Per Unit -->
    <div class="bg-white rounded-xl shadow-lg p-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Antrian Saat Ini</h3>
        <div id="antrian-list" class="space-y-3 max-h-72 overflow-y-auto px-2">
            @if(isset($antrianPerUnit) && $antrianPerUnit->isNotEmpty())
                @foreach($antrianPerUnit as $unitId => $antrianList)
                    @php $item = $antrianList->first(); @endphp
                    <div class="bg-gray-50 rounded-lg p-3 border-l-4 border-indigo-500 hover:bg-gray-100 transition transform hover:scale-[1.01] duration-150 ease-in-out">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-900">{{ $item->unit->unit }}</p>
                                <p class="text-gray-600 text-sm">{{ $item->unit->kode_unit }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-extrabold text-gray-900">{{ $item->nomor_antrian }}</p>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ \Carbon\Carbon::parse($item->dipanggil_at)->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-500 py-4 text-sm">
                    <i class="fas fa-inbox mr-1"></i> Belum ada antrian aktif
                </p>
            @endif
        </div>
    </div>
</main>

    {{-- <!-- Antrian Loket (opsional) -->
    <section class="grid grid-cols-4 gap-4 px-6 pb-6">
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white rounded-2xl flex flex-col items-center justify-center p-8 shadow-xl transform transition duration-500 hover:scale-110 hover:shadow-2xl relative overflow-hidden">
            <div class="absolute w-40 h-40 bg-white opacity-20 rounded-full blur-3xl -top-10 -right-10"></div>
            <div class="absolute w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -bottom-6 -left-6"></div>
            <h4 class="mb-2 text-sm tracking-widest font-semibold">PENDAFTARAN</h4>
            <p class="text-6xl font-extrabold drop-shadow-lg">P3</p>
            <p class="mt-2 text-2xl font-bold tracking-wide">Loket 1</p>
        </div>
        <div class="bg-green-500 text-white rounded-xl flex flex-col items-center justify-center p-6 shadow-lg transform transition duration-500 hover:scale-105">
            <h4 class="mb-2 text-sm">PEMBAYARAN</h4>
            <p class="text-5xl font-bold">B5</p>
        </div>
        <div class="bg-red-500 text-white rounded-xl flex flex-col items-center justify-center p-6 shadow-lg transform transition duration-500 hover:scale-105">
            <h4 class="mb-2 text-sm">PEMBAYARAN 3</h4>
            <p class="text-5xl font-bold">C21</p>
        </div>
        <div class="bg-blue-400 text-white rounded-xl flex flex-col items-center justify-center p-6 shadow-lg transform transition duration-500 hover:scale-105">
            <h4 class="mb-2 text-sm">PEMBAYARAN 4</h4>
            <p class="text-5xl font-bold">D10</p>
        </div>
    </section> --}}
 <!-- 🔴 Running Text (Baru Ditambahkan) -->
    <div class="running-text">
        <span>
            @if($runningText && $runningText->is_active)
                {{ $runningText->text }}
            @else
                Belum Ada Text Untuk Ditampilkan
            @endif
        </span>
    </div>
    <!-- Audio Bell -->
    <audio id="bell" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center py-3 text-sm shadow-inner">
        Created by <span class="font-semibold">Musaba-Technopark 2025</span>
    </footer>
<script>
// Refresh daftar antrian setiap 15 detik
async function refreshAntrianList() {
    try {
        const res = await fetch("{{ route('antrian.list') }}");
        const html = await res.text();
        document.getElementById("antrian-list").innerHTML = html;
    } catch (err) {
        console.error("Gagal refresh daftar antrian:", err);
    }
}

// Jalankan setiap 15 detik
setInterval(refreshAntrianList, 15000);

// Load pertama kali saat halaman dibuka
window.addEventListener("load", refreshAntrianList);
</script>
    <script>
        let soundEnabled = false;
        let lastId = null;

        // Aktifkan suara
        document.getElementById("enableSound").addEventListener("click", function() {
            const dummy = new Audio();
            dummy.src = "{{ asset('sounds/bell.mp3') }}";
            dummy.volume = 0.001;

            dummy.play().then(() => {
                soundEnabled = true;
                this.style.display = "none";
                console.log("🔊 Suara diaktifkan!");
            }).catch(err => {
                alert("Gagal mengaktifkan suara. Klik lagi.");
                console.error(err);
            });
        });

        // Jam Realtime
        function updateClock() {
            const now = new Date();
            const hari = now.toLocaleDateString("id-ID", { weekday: 'long' });
            const tanggal = now.toLocaleDateString("id-ID", { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById("tanggal").innerText = `${hari}, ${tanggal}`;

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            document.getElementById("jam").innerText = `${jam}:${menit}:${detik}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Cek antrian baru
        async function cekAntrian() {
            if (!soundEnabled) return;

            try {
                const res = await fetch("{{ route('panggilan.next') }}");
                const data = await res.json();

                if (data && data.id !== lastId) {
                    lastId = data.id;
                    const nomorAntrian = data.nomor_antrian || "-";

                    document.getElementById("nomorantrian").innerText = nomorAntrian;
                    document.getElementById("unit").innerText = data.unit?.unit || "UNIT";
                    document.getElementById("loket").innerText = data.loket?.nama_loket || "LOKET";

                    const box = document.getElementById("antrian-box");
                    box.classList.remove("animate-zoom");
                    void box.offsetWidth;
                    box.classList.add("animate-zoom");

                    if (nomorAntrian !== "-") {
                        const bell = document.getElementById("bell");
                        bell.currentTime = 0;
                        bell.play().catch(e => console.warn("Gagal mainkan bel:", e));

                        setTimeout(() => {
                            const text = `Nomor antrian ${data.nomor_antrian}, menuju ${data.unit?.unit || 'tidak diketahui'}, di ${data.loket?.nama_loket || 'tidak diketahui'}`;
                            const utter = new SpeechSynthesisUtterance(text);
                            utter.lang = "id-ID";
                            utter.rate = 0.9;
                            utter.pitch = 1;
                            utter.volume = 1;
                            speechSynthesis.speak(utter);
                        }, 2000);
                    }
                }
            } catch (err) {
                console.error("Error fetching antrian:", err);
            }
        }

        setInterval(cekAntrian, 8000);
        window.addEventListener("load", cekAntrian);
    </script>
</body>
</html>