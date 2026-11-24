<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-dark-900 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-8xl font-bold text-red-500 mb-4">403</div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Akses Ditolak</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <div class="flex justify-center gap-3">
            <button onclick="history.back()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                <i class='bx bx-arrow-back text-lg'></i>
                Kembali
            </button>
            <a href="{{ route('dashboard.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-700 rounded-xl text-sm font-medium transition-all duration-200">
                <i class='bx bx-home text-lg'></i>
                Dashboard
            </a>
        </div>
    </div>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</body>
</html>