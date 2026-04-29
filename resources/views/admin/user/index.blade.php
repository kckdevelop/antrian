@extends('layout.admin')
@section('title', 'Manajemen User')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl shadow-lg p-6 mb-8">
        <h1 class="text-3xl font-bold flex items-center">
            <i class="fas fa-users mr-3"></i> Manajemen User
        </h1>
        <p class="mt-2 text-blue-100">Kelola pengguna sistem (admin atau petugas).</p>
    </div>


    <!-- Header Aksi -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            Daftar User
           
        </h2>
        <button onclick="openModal()" 
                class="mt-4 md:mt-0 inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition shadow">
            <i class="fas fa-plus-circle mr-2"></i> Tambah User
        </button>
    </div>

    <!-- Pesan Sukses -->
    @if (session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm animate-fade">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Berhasil:</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Daftar User (Tabel) -->
    @if ($users->isEmpty())
        <div class="text-center py-16 bg-white rounded-xl shadow">
            <i class="fas fa-user-slash text-5xl text-gray-300 mb-3"></i>
            <p class="text-lg text-gray-600">Belum ada user yang terdaftar.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-6">Nama</th>
                        <th class="py-3 px-6">Email</th>
                        <th class="py-3 px-6">Role</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-semibold">{{ $user->name }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ $user->email }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : 
                                       ($user->role == 'petugas' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center space-x-2">
                                <button 
                                    onclick="openEditModal(
                                        {{ $user->id }}, 
                                        '{{ addslashes($user->name) }}', 
                                        '{{ addslashes($user->email) }}', 
                                        '{{ $user->role }}'
                                    )"
                                    class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button 
                                    onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal Tambah/Edit User -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h3 id="modalTitle" class="text-xl font-bold mb-4 text-gray-800">Tambah User Baru</h3>

        <form id="userForm" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="editId">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" required placeholder="John Doe"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required placeholder="email@domain.com"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" 
                       placeholder="Masukkan password baru"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition font-medium">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl">
        <div class="bg-red-600 text-white rounded-t-xl p-4 flex items-center">
            <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
            <h3 class="text-xl font-bold">Hapus User?</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700">Yakin ingin menghapus user berikut? Data tidak dapat dikembalikan.</p>
            <p class="font-semibold text-lg mt-3 text-gray-900" id="deleteUserName"></p>
        </div>
        <div class="flex justify-end space-x-3 p-6 bg-gray-50 rounded-b-xl border-t">
            <button onclick="closeDeleteModal()"
                    class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
                Batal
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Buka modal tambah
    function openModal() {
        document.getElementById('modalTitle').textContent = 'Tambah User Baru';
        document.getElementById('userForm').action = "{{ route('user.store') }}";
        document.querySelector('#userForm [name="_method"]').value = 'POST';
        document.getElementById('editId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
        document.getElementById('role').value = 'user';
        document.getElementById('addUserModal').classList.remove('hidden');
    }

    // Buka modal edit
    function openEditModal(id, name, email, role) {
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('userForm').action = "{{ url('admin/user') }}/" + id;
        document.querySelector('#userForm [name="_method"]').value = 'PUT';
        document.getElementById('editId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
        document.getElementById('role').value = role;
        document.getElementById('addUserModal').classList.remove('hidden');
    }

    // Tutup modal tambah/edit
    function closeModal() {
        document.getElementById('addUserModal').classList.add('hidden');
    }

    // Buka modal hapus
    function openDeleteModal(id, name) {
        document.getElementById('deleteUserName').textContent = name;
        document.getElementById('deleteForm').action = "{{ url('admin/user') }}/" + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Tutup modal hapus
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>

@endsection