@extends('layouts.app')
@section('title', 'Perusahaan')
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

        <!-- Form Pengaturan Perusahaan -->
        <div
            class="bg-white dark:bg-dark-800 rounded-2xl shadow-md border border-gray-100 dark:border-dark-700 overflow-hidden transition-colors duration-200">
            <div class="p-6">
                <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2 mb-5">
                    <i class='bx bx-building text-gray-600 dark:text-blue-400 text-xl'></i>
                    Pengaturan Perusahaan
                </h3>

                <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5" id="formPerusahaan">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Perusahaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="text" name="nama" value="{{ $pengaturan?->nama ?? '' }}" required
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                    placeholder="PT Buana Data Media">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Email Perusahaan
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="email" name="email" value="{{ $pengaturan?->email ?? '' }}"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                    placeholder="info@buana.com">
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Telepon Perusahaan
                            </label>
                            <div class="relative">
                                <i
                                    class='bx bx-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                <input type="text" name="telepon" value="{{ $pengaturan?->telepon ?? '' }}"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors"
                                    placeholder="(021) 12345678">
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Alamat Perusahaan
                            </label>
                            <div class="relative">
                                <i class='bx bx-map absolute left-3 top-3 text-gray-400 dark:text-gray-500'></i>
                                <textarea name="alamat" rows="3"
                                    class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors resize-none"
                                    placeholder="Jl. Contoh No. 123, Kota, Provinsi">{{ $pengaturan?->alamat ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Logo Perusahaan -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Logo Perusahaan
                            </label>

                            <!-- Preview Logo Saat Ini -->
                            @if ($pengaturan?->logo)
                                <div class="mb-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl inline-block">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo Perusahaan"
                                            class="h-14 w-auto object-contain" id="currentLogo">
                                        <div class="text-sm">
                                            <p class="text-gray-600 dark:text-gray-400">Logo saat ini</p>
                                            <button type="button" onclick="removeLogo()"
                                                class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 mt-1">
                                                <i class='bx bx-trash'></i> Hapus Logo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload Logo Baru -->
                            <div class="relative">
                                <div id="logoPreview"
                                    class="hidden mb-3 p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl inline-block">
                                    <div class="flex items-center gap-3">
                                        <img id="logoPreviewImg" src="#" alt="Preview Logo"
                                            class="h-14 w-auto object-contain">
                                        <div class="text-sm">
                                            <p class="text-gray-600 dark:text-gray-400">Preview Logo Baru</p>
                                            <button type="button" onclick="clearLogoPreview()"
                                                class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 mt-1">
                                                <i class='bx bx-x'></i> Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <i
                                        class='bx bx-image absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500'></i>
                                    <input type="file" name="logo" id="logoInput"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-dark-700 rounded-xl text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none bg-white dark:bg-dark-800 text-gray-700 dark:text-gray-300 transition-colors cursor-pointer file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:text-sm file:border-0 file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-900/30 dark:file:text-blue-400">
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i>
                                Format: JPG, PNG. Maksimal 2MB. Logo akan muncul di invoice dan struk.
                            </p>
                        </div>
                    </div>

                    <!-- Informasi Preview di Invoice -->
                    <div class="bg-gray-50 dark:bg-gray-900/20 rounded-xl p-4 mt-2">
                        <div class="flex items-start gap-3">
                            <i class='bx bx-info-circle text-gray-600 dark:text-blue-400 text-xl mt-0.5'></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-blue-300">Informasi</p>
                                <p class="text-xs text-gray-700 dark:text-blue-400 mt-1">
                                    Logo dan informasi perusahaan akan muncul di halaman invoice, nota pembayaran, dan
                                    laporan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-dark-700">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class='bx bx-save text-lg'></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
    @push('scripts')
        <script>
            // Preview logo sebelum upload
            const logoInput = document.getElementById('logoInput');
            const logoPreview = document.getElementById('logoPreview');
            const logoPreviewImg = document.getElementById('logoPreviewImg');
            const currentLogo = document.getElementById('currentLogo');

            if (logoInput) {
                logoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validasi tipe file
                        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                title: 'Format Tidak Valid',
                                text: 'Hanya file JPG, JPEG, atau PNG yang diperbolehkan',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            this.value = '';
                            logoPreview.classList.add('hidden');
                            return;
                        }

                        // Validasi ukuran file (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                title: 'Ukuran Terlalu Besar',
                                text: 'Maksimal ukuran logo adalah 2MB',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            this.value = '';
                            logoPreview.classList.add('hidden');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            logoPreviewImg.src = e.target.result;
                            logoPreview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        logoPreview.classList.add('hidden');
                    }
                });
            }

            // Clear logo preview
            function clearLogoPreview() {
                logoInput.value = '';
                logoPreview.classList.add('hidden');
            }

            // Remove existing logo (mark for deletion)
            function removeLogo() {
                Swal.fire({
                    title: 'Hapus Logo?',
                    text: 'Logo perusahaan akan dihapus. Anda bisa upload logo baru nanti.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Add hidden input to mark logo for deletion
                        const form = document.getElementById('formPerusahaan');
                        const deleteInput = document.createElement('input');
                        deleteInput.type = 'hidden';
                        deleteInput.name = 'remove_logo';
                        deleteInput.value = '1';
                        form.appendChild(deleteInput);

                        // Hide current logo display
                        const logoContainer = document.querySelector('#currentLogo')?.closest('.p-3');
                        if (logoContainer) {
                            logoContainer.style.display = 'none';
                        }

                        Swal.fire('Terhapus!', 'Logo akan dihapus saat menyimpan.', 'success');
                    }
                });
            }
        </script>
    @endpush
