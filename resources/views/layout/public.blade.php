<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - Sistem Antrian</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome (untuk ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg border-b">
        <div class="container py-4 px-6">
            <div class="flex items-center justify-between">
                <!-- Logo & Judul -->
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-ticket-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Sistem Antrian</h1>
                        <p class="text-sm text-gray-600">Antrian Digital</p>
                    </div>
                </div>

                <!-- Waktu Realtime (Opsional) -->
                <div class="text-right text-sm">
                    <p id="tanggal" class="font-medium"></p>
                    <p id="jam" class="text-lg font-mono"></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 text-sm mt-12">
        &copy; {{ date('Y') }} Musaba-Technopark | Sistem Antrian Digital
    </footer>

    <!-- Jam Realtime Script -->
    <script>
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
    </script>

    @stack('scripts')
</body>
</html>