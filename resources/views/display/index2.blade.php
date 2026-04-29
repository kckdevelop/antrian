<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Antrian - SMK MUSABA</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Segoe UI', sans-serif;
        }

        @keyframes zoomIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-zoom {
            animation: zoomIn 0.6s ease-out;
        }

        /* Running Text */
        .marquee-container {
            background: linear-gradient(90deg, #1e3a8a, #1e40af);
            color: white;
            white-space: nowrap;
            overflow: hidden;
            padding: 10px 0;
            box-shadow: inset 0 -2px 5px rgba(0,0,0,0.2);
        }
        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 25s linear infinite;
            font-size: 1.1rem;
            font-weight: 600;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Animasi teks masuk */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white p-4 shadow-lg">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-desktop text-3xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">SISTEM ANTRIAN</h1>
                    <p class="text-sm opacity-90">SMK MUSABA</p>
                </div>
            </div>
            <div class="text-right">
                <p id="tanggal" class="font-medium"></p>
                <p id="jam" class="text-xl font-mono animate-pulse"></p>
            </div>
        </div>
    </header>

    <!-- Running Text -->
    <div class="marquee-container">
        <div class="marquee">
            <span>
                @if($runningText && $runningText->is_active)
                    {{ $runningText->text }}
                @else
                    Selamat datang di SMK MUSABA | Pelayanan Cepat & Profesional | Hormati petugas, antre dengan tertib
                @endif
            </span>
        </div>
    </div>

    <!-- Konten Utama -->
    <main class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-4 p-4">

        <!-- Video Profil -->
        <div class="bg-black rounded-xl overflow-hidden flex items-center justify-center">
            @if ($video && $video->is_active)
                @if ($video->embed_url)
                    <iframe 
                        src="{{ $video->embed_url }}" 
                        class="w-full aspect-video rounded-lg" 
                        frameborder="0" 
                        allowfullscreen>
                    </iframe>
                @elseif ($video->video_path)
                    <video 
                        class="w-full aspect-video" 
                        controlsList="nodownload" 
                        autoplay muted loop>
                        <source src="{{ asset('storage/' . $video->video_path) }}" 
                                type="video/{{ pathinfo($video->video_path, PATHINFO_EXTENSION) }}">
                        Browser Anda tidak mendukung pemutar video.
                    </video>
                @endif
            @else
                <div class="text-center text-gray-400 py-16">
                    <i class="fas fa-video-slash text-5xl mb-3 opacity-50"></i>
                    <p class="text-lg">Video Tidak Tersedia</p>
                </div>
            @endif
        </div>

        <!-- Panggilan Terbaru (Utama) -->
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
               class="text-6xl font-extrabold my-4 bg-white bg-opacity-10 px-6 py-3 rounded-lg min-h-20 flex items-center justify-center w-52">
                -
            </p>

            <!-- Loket -->
            <h3 id="loket" class="text-lg font-medium">
                <span class="bg-white text-fuchsia-700 px-4 py-1 rounded-full font-bold text-sm">LOKET</span>
            </h3>
        </div>

        <!-- Daftar Antrian Saat Ini per Unit -->
        <div class="space-y-3 overflow-y-auto max-h-[calc(100vh-280px)] pr-2">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2">Antrian Saat Ini</h2>
            <div id="antrian-list">
                @if($antrianPerUnit && $antrianPerUnit->isNotEmpty())
                    @foreach($antrianPerUnit as $unitId => $antrianList)
                        @php $item = $antrianList->first(); @endphp
                        <div class="bg-white rounded-lg shadow p-3 border-l-4 border-blue-500 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item->unit->unit }}</p>
                                    <p class="text-sm text-gray-600">{{ $item->unit->kode_unit }}</p>
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
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-2 text-xs">
        &copy; {{ date('Y') }} SMK MUSABA | Powered by Musaba-Technopark
    </footer>

    <!-- Audio Bell -->
    <audio id="bell" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>

    <script>
        let soundEnabled = false;
        let lastId = null;

        // Aktifkan suara
        document.getElementById("enableSound")?.addEventListener("click", function() {
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

                    // Update UI
                    document.getElementById("nomorantrian").innerText = nomorAntrian;
                    document.getElementById("unit").innerText = data.unit?.unit || "Unit Tidak Dikenal";
                    document.getElementById("loket").innerText = data.loket?.nama_loket || "Loket Tidak Dikenal";

                    // Animasi
                    const box = document.getElementById("antrian-box");
                    box.classList.remove("animate-zoom");
                    void box.offsetWidth;
                    box.classList.add("animate-zoom");

                    // Mainkan suara
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

        // Auto-refresh daftar antrian
        async function refreshAntrianList() {
            try {
                const res = await fetch("{{ route('antrian.list') }}");
                const html = await res.text();
                document.getElementById("antrian-list").innerHTML = html;
            } catch (err) {
                console.error("Gagal refresh daftar antrian:", err);
            }
        }

        setInterval(cekAntrian, 12000);
        setInterval(refreshAntrianList, 15000);

        // Load pertama kali
        window.addEventListener("load", () => {
            cekAntrian();
            refreshAntrianList();
        });
    </script>
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
</body>
</html>