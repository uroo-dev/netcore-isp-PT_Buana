<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BiayaOperasionalController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CabangDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RepeaterController;
use App\Http\Controllers\SlipGajiController;
use App\Http\Controllers\TagihanWifiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process')->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:owner,pegawai')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::get('/pelanggan/search/json', [PelangganController::class, 'search'])->name('pelanggan.search');
        Route::get('/pelanggan/data/{tipe}', [PelangganController::class, 'fetchTab'])->name('pelanggan.fetch-tab');
        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/{id}', [PelangganController::class, 'show'])->name('pelanggan.show');
        Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
        Route::get('/pelanggan/{id}/tagihan', [PelangganController::class, 'tagihan'])->name('pelanggan.tagihan');
        Route::put('/pelanggan/{id}/toggle-status', [PelangganController::class, 'toggleStatus'])->name('pelanggan.toggle-status');

        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/data', [ProdukController::class, 'fetchData'])->name('produk.data');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
        Route::put('/produk/{id}/restok', [ProdukController::class, 'restok'])->name('produk.restok');
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('/invoice/data', [InvoiceController::class, 'fetchData'])->name('invoice.data');
        Route::get('/kasbon', [InvoiceController::class, 'kasbon'])->name('kasbon.index');
        Route::get('/kasbon/data', [InvoiceController::class, 'fetchKasbon'])->name('kasbon.data');
        Route::get('/invoice/buat', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('/invoice', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/invoice/{id}/cetak', [InvoiceController::class, 'cetakPdf'])->name('invoice.cetak');
        Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::get('/invoice/{id}/bayar', [InvoiceController::class, 'bayar'])->name('invoice.bayar');
        Route::post('/invoice/{id}/bayar', [InvoiceController::class, 'prosesBayar'])->name('invoice.prosesBayar');
        Route::delete('/invoice/{id}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
        Route::get('/invoice/piutang/{pelangganId}', [InvoiceController::class, 'getPiutangPelanggan'])->name('invoice.piutang');
        Route::get('/produk/search', [ProdukController::class, 'search'])->name('produk.search');

        Route::get('/tagihan-wifi', [TagihanWifiController::class, 'index'])->name('tagihan-wifi.index');
        Route::get('/tagihan-wifi/data', [TagihanWifiController::class, 'fetchData'])->name('tagihan-wifi.data');
        Route::post('/tagihan-wifi', [TagihanWifiController::class, 'store'])->name('tagihan-wifi.store');
        Route::delete('/tagihan-wifi/{id}', [TagihanWifiController::class, 'destroy'])->name('tagihan-wifi.destroy');

        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/{invoiceId}/detail', [PembayaranController::class, 'detail'])->name('pembayaran.detail');

        Route::get('/kas', [KasController::class, 'index'])->name('kas.index');
        Route::get('/kas/data', [KasController::class, 'fetchData'])->name('kas.data');
        Route::post('/kas', [KasController::class, 'store'])->name('kas.store');
        Route::post('/kas/rekalkulasi', [KasController::class, 'recalculateSaldo'])->name('kas.rekalkulasi');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data', [LaporanController::class, 'fetchData'])->name('laporan.data');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/nota-bulanan', [LaporanController::class, 'notaBulanan'])->name('laporan.nota-bulanan');
        Route::get('/laporan/cetak-tagihan', [LaporanController::class, 'cetakTagihan'])->name('laporan.cetak-tagihan');
        Route::get('/laporan/cetak-produk', [LaporanController::class, 'cetakProduk'])->name('laporan.cetak-produk');
        Route::get('/laporan/cetak-kas-perusahaan', [LaporanController::class, 'cetakKasPerusahaan'])->name('laporan.cetak-kas-perusahaan');

        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/form', [AbsensiController::class, 'form'])->name('absensi.form');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/riwayat', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
        Route::get('/absensi/cetak', [AbsensiController::class, 'cetak'])->name('absensi.cetak');
        Route::get('/absensi/rekap-pdf', [AbsensiController::class, 'rekapPdf'])->name('absensi.rekap-pdf');

        Route::get('/slip-gaji', [SlipGajiController::class, 'index'])->name('slip-gaji.index');
        Route::get('/slip-gaji/create', [SlipGajiController::class, 'create'])->name('slip-gaji.create');
        Route::post('/slip-gaji', [SlipGajiController::class, 'store'])->name('slip-gaji.store');
        Route::get('/slip-gaji/{id}', [SlipGajiController::class, 'show'])->name('slip-gaji.show');
        Route::put('/slip-gaji/detail/{id}', [SlipGajiController::class, 'updateDetail'])->name('slip-gaji.update-detail');
        Route::delete('/slip-gaji/detail/{id}', [SlipGajiController::class, 'destroyDetail'])->name('slip-gaji.destroy-detail');
        Route::delete('/slip-gaji/{id}', [SlipGajiController::class, 'destroy'])->name('slip-gaji.destroy');
        Route::get('/slip-gaji/{id}/cetak-pdf', [SlipGajiController::class, 'cetakPdf'])->name('slip-gaji.cetak-pdf');

        Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
        Route::post('/cabang', [CabangController::class, 'store'])->name('cabang.store');
        Route::put('/cabang/{id}', [CabangController::class, 'update'])->name('cabang.update');
        Route::delete('/cabang/{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');
        Route::get('/cabang/{id}/users', [CabangController::class, 'users'])->name('cabang.users');
        Route::get('/cabang/{id}/produk', [CabangController::class, 'produk'])->name('cabang.produk');
        Route::get('/cabang/{id}/cetak-laporan', [CabangController::class, 'cetakLaporan'])->name('cabang.cetak-laporan');

        Route::post('/produk/pindah-cabang', [ProdukController::class, 'pindahKeCabang'])->name('produk.pindah-cabang');

        Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
        Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
        Route::put('/akun/{id}', [AkunController::class, 'update'])->name('akun.update');
        Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');

        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::get('/pengaturan/profil', [PengaturanController::class, 'profil'])->name('pengaturan.profil');
        Route::put('/pengaturan/profil', [PengaturanController::class, 'updateProfil'])->name('pengaturan.profil.update');

        Route::post('/pengaturan/paket', [PengaturanController::class, 'storePaket'])->name('pengaturan.paket.store');
        Route::put('/pengaturan/paket/{id}', [PengaturanController::class, 'updatePaket'])->name('pengaturan.paket.update');
        Route::delete('/pengaturan/paket/{id}', [PengaturanController::class, 'destroyPaket'])->name('pengaturan.paket.destroy');

        Route::post('/pengaturan/penanggung-jawab', [PengaturanController::class, 'storePenanggungJawab'])->name('pengaturan.penanggung-jawab.store');
        Route::put('/pengaturan/penanggung-jawab/{id}', [PengaturanController::class, 'updatePenanggungJawab'])->name('pengaturan.penanggung-jawab.update');
        Route::delete('/pengaturan/penanggung-jawab/{id}', [PengaturanController::class, 'destroyPenanggungJawab'])->name('pengaturan.penanggung-jawab.destroy');
    });

    Route::middleware('role:owner')->group(function () {
        Route::get('/dashboard/pajak', [DashboardController::class, 'pajak'])->name('dashboard.pajak');
        Route::get('/dashboard/pajak/data', [DashboardController::class, 'fetchDataPajak'])->name('dashboard.pajak.data');
        Route::get('/pengaturan/perusahaan', [PengaturanController::class, 'perusahaan'])->name('pengaturan.perusahaan');
        Route::get('/pengaturan/paket', [PengaturanController::class, 'paket'])->name('pengaturan.paket');
        Route::get('/pengaturan/penanggung-jawab', [PengaturanController::class, 'penanggungJawab'])->name('pengaturan.penanggung-jawab');
        Route::get('/pengaturan/log-aktivitas', [PengaturanController::class, 'logAktivitas'])->name('pengaturan.log');
        Route::get('/pengaturan/log-aktivitas/data', [PengaturanController::class, 'fetchLogData'])->name('pengaturan.log.data');
        Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
        Route::get('/biaya-operasional', [BiayaOperasionalController::class, 'index'])->name('biaya-operasional.index');
        Route::get('/biaya-operasional/data', [BiayaOperasionalController::class, 'fetchData'])->name('biaya-operasional.data');
        Route::post('/biaya-operasional', [BiayaOperasionalController::class, 'store'])->name('biaya-operasional.store');
        Route::put('/biaya-operasional/{id}', [BiayaOperasionalController::class, 'update'])->name('biaya-operasional.update');
        Route::delete('/biaya-operasional/{id}', [BiayaOperasionalController::class, 'destroy'])->name('biaya-operasional.destroy');

        Route::get('/repeater', [RepeaterController::class, 'index'])->name('repeater.index');
        Route::post('/repeater', [RepeaterController::class, 'store'])->name('repeater.store');
        Route::get('/repeater/{id}', [RepeaterController::class, 'show'])->name('repeater.show');
        Route::delete('/repeater/{id}', [RepeaterController::class, 'destroy'])->name('repeater.destroy');
        Route::get('/repeater/{id}/bulan/create', [RepeaterController::class, 'createBulan'])->name('repeater.create-bulan');
        Route::post('/repeater/{id}/bulan', [RepeaterController::class, 'storeBulan'])->name('repeater.store-bulan');
        Route::get('/repeater/{id}/bulan/{bulan}/{tahun}', [RepeaterController::class, 'showBulan'])->name('repeater.show-bulan');
        Route::put('/repeater/pelanggan/{pelangganId}', [RepeaterController::class, 'updatePelanggan'])->name('repeater.update-pelanggan');
        Route::delete('/repeater/pelanggan/{pelangganId}', [RepeaterController::class, 'destroyPelanggan'])->name('repeater.destroy-pelanggan');
        Route::delete('/repeater/{id}/bulan/{bulan}/{tahun}', [RepeaterController::class, 'destroyBulan'])->name('repeater.destroy-bulan');

        Route::get('/pengaturan/backup', [BackupController::class, 'index'])->name('pengaturan.backup');
        Route::post('/pengaturan/backup', [BackupController::class, 'create'])->name('pengaturan.backup.create');
        Route::put('/pengaturan/backup', [BackupController::class, 'updateSettings'])->name('pengaturan.backup.update');
        Route::get('/pengaturan/backup/{backup}/download', [BackupController::class, 'download'])->name('pengaturan.backup.download');
        Route::delete('/pengaturan/backup/{backup}', [BackupController::class, 'destroy'])->name('pengaturan.backup.destroy');
    });

    Route::middleware('role:cabang')->group(function () {
        Route::get('/cabang/dashboard', [CabangDashboardController::class, 'index'])->name('cabang.dashboard');
        Route::get('/cabang/dashboard/data', [CabangDashboardController::class, 'fetchData'])->name('cabang.dashboard.data');
        Route::post('/cabang/produk/{id}/pakai', [CabangDashboardController::class, 'pakai'])->name('cabang.produk.pakai');
    });
});
