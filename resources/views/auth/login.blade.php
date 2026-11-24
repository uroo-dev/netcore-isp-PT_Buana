@extends('layouts.auth')
@section('title', 'Auth|Login')
@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-8">

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl mb-3">
                <i class='bx bx-lock text-2xl text-gray-600'></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mt-1">Silakan login ke akun Anda</p>
        </div>

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4" id="loginForm">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                <div class="relative">
                    <i class='bx bx-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                        placeholder="Masukkan username"
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-gray-100 focus:border-gray-500 outline-none transition">
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <i class='bx bx-lock-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full pl-11 pr-12 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-gray-100 focus:border-gray-500 outline-none transition">
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                        <i class='bx bx-hide text-lg' id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2.5 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                <span id="btnNormal" class="flex items-center gap-2">
                    <i class='bx bx-log-in text-lg'></i> Masuk
                </span>
                <span id="btnLoading" class="hidden items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </form>

    </div>
@endsection

@push('scripts')
<script>
    // Loading state saat form disubmit
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const normal = document.getElementById('btnNormal');
        const loading = document.getElementById('btnLoading');

        // Disable button
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');

        // Tampilkan loading
        normal.classList.add('hidden');
        loading.classList.remove('hidden');
        loading.classList.add('flex');
    });
</script>
@endpush
