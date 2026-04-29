<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Aplikasi Antrian - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .marquee {
            white-space: nowrap;
            display: inline-block;
            animation: marquee 20s linear infinite;
        }

        .antrian-box {
            transition: all 0.3s ease;
        }

        .antrian-box:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        /* Tambahkan efek transisi untuk tombol dropdown */
        .dropdown-button {
            @apply transition-colors duration-200;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex flex-col">

    <!-- Header -->
    <header class="bg-blue-600 text-white flex justify-between items-center px-6 py-3 text-xl font-semibold">
        <div class="flex items-center">
            <i class="fas fa-users mr-3"></i>
            <span>ADMIN PANEL SISTEM ANTRIAN</span>
        </div>
        <!-- Jam + Tanggal -->
        <div id="time" class="text-lg bg-blue-700 px-4 py-1 rounded-lg"></div>
    </header>

    <!-- Main Content -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-4 h-full flex flex-col">
            <!-- Judul dan Profil -->
            <div>
                <div class="mb-8 p-4 bg-gray-700 rounded-lg flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('name')?? 'User') }}&background=111827&color=fff&rounded=true&size=64"
                        alt="Foto Profil" class="w-12 h-12 rounded-full border-2 border-gray-600 object-cover">
                    <div class="text-sm">
                        <div class="font-semibold">{{ session('name') ?? 'Guest' }}</div>
                        <div class="text-xs text-gray-300">{{session('role') ?? 'Petugas' }}</div>
                        <div class="text-xs text-gray-400">
                            Terakhir login: <br>{{ session('last_login') ?? now()->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="flex-1 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard.index') }}"
                    class="block p-3 rounded-lg flex items-center transition duration-200
           {{ request()->routeIs('dashboard.index') ? 'bg-blue-600 font-semibold' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                </a>

                <!-- Manajemen Layanan Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown('layananDropdown')"
                        class="dropdown-button block w-full text-left p-3 rounded-lg hover:bg-gray-700 flex items-center">
                        <i class="fas fa-cogs mr-3"></i> Manajemen Layanan
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    <div id="layananDropdown" class="dropdown-menu left-0 w-full bg-gray-800 rounded-lg hidden pl-2">
                        <a href="{{ route('layanan.unit') }}"
                            class="block p-2 hover:bg-gray-700 rounded
                   {{ request()->routeIs('layanan.unit') ? 'bg-blue-600 font-semibold' : '' }}">
                            Atur Unit
                        </a>
                        <a href="{{ route('layanan.loket') }}"
                            class="block p-2 hover:bg-gray-700 rounded
                   {{ request()->routeIs('layanan.loket') ? 'bg-blue-600 font-semibold' : '' }}">
                            Atur Loket
                        </a>
                        <a href="{{ route('layanan.runningtext') }}"
                            class="block p-2 hover:bg-gray-700
                   {{ request()->routeIs('layanan.runningtext') ? 'bg-blue-600 font-semibold' : '' }}">
                            Atur Running Text
                        </a>
                        <a href="{{ route('layanan.video') }}"
                            class="block p-2 hover:bg-gray-700
                   {{ request()->routeIs('layanan.video') ? 'bg-blue-600 font-semibold' : '' }}">
                            Atur Video
                        </a>
                    </div>
                </div>

                <!-- Manajemen Antrian -->
                <a href="{{ route('antrian.index') }}"
                    class="block p-3 rounded-lg flex items-center transition duration-200
           {{ request()->routeIs('antrian.index') ? 'bg-blue-600 font-semibold' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-chart-bar mr-3"></i> Manajemen Antrian
                </a>

                <!-- Manajemen Users -->
                <a href="{{ route('user.index') }}"
                    class="block p-3 rounded-lg flex items-center transition duration-200
   {{ request()->routeIs('user.*') ? 'bg-blue-600 font-semibold' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-user-cog mr-3"></i> Manajemen Users
                </a>

                <!-- Display Antrian -->
                <a href="{{ route('display.index') }}" target="_blank"
                    class="block p-3 rounded-lg flex items-center transition duration-200
           {{ request()->routeIs('display.index') ? 'bg-blue-600 font-semibold' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-tv mr-3"></i> Display Antrian
                </a>

                <!-- Ambil Antrian (Untuk Pengunjung) -->
                <a href="{{ route('antrian.tampil') }}" target="_blank"
                    class="block p-3 rounded-lg flex items-center transition duration-200
           {{ request()->routeIs('antrian.tampil') ? 'bg-blue-600 font-semibold' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-ticket-alt mr-3"></i> Ambil Antrian
                </a>

                <!-- Laporan dan Riwayat -->
                <a href="{{ route('laporan.index') }}"
                    class="block p-3 rounded-lg flex items-center transition duration-200
           {{ request()->routeIs('laporan.*') ? 'bg-blue-600 font-semibold' : 'hover:bg-gray-700' }}">
                    <i class="fas fa-file-alt mr-3"></i> Laporan dan Riwayat
                </a>
            </nav>

            <!-- Logout -->
            <div class="mt-6">
                <a href="#" onclick="event.preventDefault(); openLogoutModal();"
                    class="w-full block bg-red-600 text-left p-3 text-sm text-gray-300 hover:bg-red-900 rounded-lg transition">
                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            @yield('content')
        </main>
        <!-- Modal Konfirmasi Logout -->
        <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96 max-w-md mx-4">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-red-100 rounded-full">
                        <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-800">Konfirmasi Logout</h3>
                </div>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem? Anda harus login kembali untuk
                    mengakses dashboard.</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeLogoutModal()"
                        class="px-4 py-2 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-md transition">Batal</button>
                    <button onclick="confirmLogout()"
                        class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition">Ya,
                        Logout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Running Text Footer -->
    <footer class="text-center bg-blue-600 text-white py-2 overflow-hidden border-t border-blue-500">
        <div class="px-6 text-sm">
            SISTEM ANTRIAN TERPADU - Musaba-Technopark
        </div>
    </footer>

    <script>
        // Update waktu real-time (jam + tanggal)
        function updateTime() {
            const timeEl = document.getElementById('time');
            const now = new Date();
            const options = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const formatted = now.toLocaleDateString('id-ID', options);
            timeEl.textContent = formatted;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Toggle dropdown dengan warna aktif
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const button = dropdown.previousElementSibling;

            // Toggle tampilan dropdown
            dropdown.classList.toggle('hidden');

            // Tambahkan/hapus warna aktif pada tombol
            if (!dropdown.classList.contains('hidden')) {
                button.classList.add('bg-blue-800');
            } else {
                button.classList.remove('bg-blue-800');
            }
        }

        // Tutup dropdown saat klik di luar
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-button') &&
                !event.target.closest('.dropdown-button')) {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(dropdown => {
                    const button = dropdown.previousElementSibling;
                    dropdown.classList.add('hidden');
                    button.classList.remove('bg-blue-800');
                });
            }
        }

        // Buka modal logout
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Cegah scroll saat modal terbuka
        }

        // Tutup modal logout
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Konfirmasi logout
        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLogoutModal();
            }
        });
    </script>
</body>

</html>
