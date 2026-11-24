@extends('layouts.app')
@section('title', 'Profil')
@section('content')
    <div class="p-4 md:p-6 space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div>
            <h1 class="font-bold text-2xl text-gray-800 dark:text-white flex items-center gap-2">
                <i class='bx bx-cog text-gray-600 dark:text-blue-400 text-2xl'></i>
                Pengaturan
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pengaturan akun dan perusahaan</p>
        </div>

        <!-- Form Profil -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div class="p-6">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2 mb-5">
                    <i class='bx bx-user-circle text-gray-600 dark:text-blue-400 text-xl'></i>
                    Pengaturan Akun
                </h3>

                <form action="{{ route('pengaturan.profil.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-user-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="text" name="username" value="{{ $user->username }}" required
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Telepon
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="text" name="telepon" value="{{ $user->telepon ?? '' }}"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                    placeholder="Contoh: 08123456789">
                            </div>
                        </div>

                        <!-- Role (Readonly) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Role
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="text" value="{{ ucfirst($user->role) }}" disabled
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm bg-gray-100 dark:bg-dark-700/50 text-gray-600 dark:text-gray-400 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Password Baru -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Password Baru
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-lock-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                                    class="w-full pl-9 pr-10 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors">
                                <button type="button" onclick="togglePasswordVisibility(this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <i class='bx bx-hide text-lg'></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.
                            </p>
                        </div>
                    </div>

                    <!-- Informasi Tambahan (User Info) -->
                    <div class="bg-gray-50 dark:bg-dark-700/50 rounded-xl p-4 mt-2">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-sm">
                            <div class="flex items-center gap-2">
                                <i class='bx bx-calendar text-gray-400'></i>
                                <span class="text-gray-600 dark:text-gray-400">Bergabung sejak:</span>
                                <span
                                    class="font-medium text-gray-800 dark:text-white">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class='bx bx-time text-gray-400'></i>
                                <span class="text-gray-600 dark:text-gray-400">Terakhir update:</span>
                                <span
                                    class="font-medium text-gray-800 dark:text-white">{{ $user->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-dark-700">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class='bx bx-save text-lg'></i>
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
    @push('scripts')
        <script>
            // Toggle password visibility
            function togglePasswordVisibility(button) {
                const input = button.closest('.relative').querySelector('input');
                const icon = button.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            }

            // Konfirmasi sebelum submit jika ada perubahan password
            document.querySelector('form')?.addEventListener('submit', function(e) {
                const passwordInput = document.querySelector('input[name="password"]');
                if (passwordInput && passwordInput.value.length > 0 && passwordInput.value.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Password Terlalu Pendek',
                        text: 'Password minimal 6 karakter',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        </script>
    @endpush
