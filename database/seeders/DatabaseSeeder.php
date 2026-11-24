<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\BiayaOperasional;
use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\Kas;
use App\Models\LogAktivitas;
use App\Models\LogStok;
use App\Models\PaketWifi;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\PenanggungJawabWifi;
use App\Models\Pengaturan;
use App\Models\Produk;
use App\Models\ProdukCabang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. CABANG
        // ==========================================
        $cabangPusat = Cabang::create([
            'nama' => 'Pusat',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta Pusat',
            'telepon' => '021-12345678',
        ]);
        $cabangBandung = Cabang::create([
            'nama' => 'Cabang Bandung',
            'alamat' => 'Jl. Braga No. 10, Bandung',
            'telepon' => '022-87654321',
        ]);

        // ==========================================
        // 2. USERS
        // ==========================================
        $owner = User::create([
            'nama_lengkap' => 'Admin Utama',
            'username' => 'owner',
            'telepon' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'cabang_id' => $cabangPusat->id,
        ]);
        $pegawai = User::create([
            'nama_lengkap' => 'Pegawai Pusat',
            'username' => 'pegawai',
            'telepon' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'cabang_id' => $cabangPusat->id,
        ]);
        $userCabang = User::create([
            'nama_lengkap' => 'Pegawai Cabang',
            'username' => 'cabang',
            'telepon' => '081234567892',
            'password' => Hash::make('password'),
            'role' => 'cabang',
            'cabang_id' => $cabangBandung->id,
        ]);

        // ==========================================
        // 3. KARYAWAN
        // ==========================================
        $karyawan1 = Karyawan::create([
            'nama_lengkap' => 'Dodi Firmansyah',
            'telepon' => '081298765432',
            'alamat' => 'Jakarta',
        ]);
        $karyawan2 = Karyawan::create([
            'nama_lengkap' => 'Eka Putri',
            'telepon' => '081298765433',
            'alamat' => 'Jakarta',
        ]);
        $karyawan3 = Karyawan::create([
            'nama_lengkap' => 'Fajar Nugraha',
            'telepon' => '081298765434',
            'alamat' => 'Bandung',
        ]);

        // ==========================================
        // 4. PAKET WIFI
        // ==========================================
        $paket10 = PaketWifi::create([
            'nama' => 'Paket 10 Mbps',
            'harga' => 150000,
            'kecepatan' => 10,
            'deskripsi' => 'Internet 10 Mbps untuk rumahan',
        ]);
        $paket20 = PaketWifi::create([
            'nama' => 'Paket 20 Mbps',
            'harga' => 250000,
            'kecepatan' => 20,
            'deskripsi' => 'Internet 20 Mbps untuk rumahan & UKM',
        ]);
        $paket50 = PaketWifi::create([
            'nama' => 'Paket 50 Mbps',
            'harga' => 500000,
            'kecepatan' => 50,
            'deskripsi' => 'Internet 50 Mbps untuk bisnis',
        ]);

        // ==========================================
        // 5. PENANGGUNG JAWAB WIFI
        // ==========================================
        $pjPusat = PenanggungJawabWifi::create([
            'nama' => 'Andi Wijaya',
            'telepon' => '087811223344',
            'wilayah' => 'Jakarta Pusat',
        ]);
        $pjSelatan = PenanggungJawabWifi::create([
            'nama' => 'Budi Santoso',
            'telepon' => '087811223355',
            'wilayah' => 'Jakarta Selatan',
        ]);
        $pjBandung = PenanggungJawabWifi::create([
            'nama' => 'Citra Dewi',
            'telepon' => '087811223366',
            'wilayah' => 'Bandung',
        ]);

        // ==========================================
        // 6. PELANGGAN
        // ==========================================
        $pel1 = Pelanggan::create([
            'nama' => 'Rina Kusuma',
            'telepon' => '081511111111',
            'alamat' => 'Jl. Sudirman No. 10, Jakarta Pusat',
            'koordinat' => '-6.2146,106.8451',
            'ip_address' => '192.168.1.10',
            'tipe' => 'individu',
            'paket_id' => $paket10->id,
            'penanggung_jawab_id' => $pjPusat->id,
            'status' => 'on',
        ]);
        $pel2 = Pelanggan::create([
            'nama' => 'Agus Prasetyo',
            'telepon' => '081522222222',
            'alamat' => 'Jl. Gatot Subroto No. 20, Jakarta Selatan',
            'koordinat' => '-6.2246,106.8351',
            'ip_address' => '192.168.1.11',
            'tipe' => 'individu',
            'paket_id' => $paket20->id,
            'penanggung_jawab_id' => $pjSelatan->id,
            'status' => 'on',
        ]);
        $pel3 = Pelanggan::create([
            'nama' => 'PT. Maju Jaya',
            'telepon' => '021-5551111',
            'alamat' => 'Jl. Jendral Gatot Subroto Kav. 5, Jakarta',
            'tipe' => 'instansi',
            'paket_id' => $paket50->id,
            'penanggung_jawab_id' => $pjSelatan->id,
            'status' => 'on',
        ]);
        $pel4 = Pelanggan::create([
            'nama' => 'Siti Nurhaliza',
            'telepon' => '081533333333',
            'alamat' => 'Jl. Thamrin No. 30, Jakarta Pusat',
            'tipe' => 'individu',
            'paket_id' => $paket10->id,
            'penanggung_jawab_id' => $pjPusat->id,
            'status' => 'off',
            'tanggal_off' => '2026-05-01',
        ]);
        $pel5 = Pelanggan::create([
            'nama' => 'Warung Kopi Senja',
            'telepon' => '081544444444',
            'alamat' => 'Jl. Diponegoro No. 5, Bandung',
            'tipe' => 'mitra',
            'paket_id' => $paket20->id,
            'penanggung_jawab_id' => $pjBandung->id,
            'status' => 'on',
        ]);
        $pel6 = Pelanggan::create([
            'nama' => 'Bambang Wirawan',
            'telepon' => '081555555555',

            'alamat' => 'Jl. Asia Afrika No. 15, Bandung',
            'tipe' => 'individu',
            'paket_id' => $paket10->id,
            'penanggung_jawab_id' => $pjBandung->id,
            'status' => 'on',
        ]);
        $pel7 = Pelanggan::create([
            'nama' => 'Uroooooo',
            'telepon' => '081566666666',
            'alamat' => 'Jl. Contoh No. 1, Jakarta',
            'tipe' => 'individu',
            'paket_id' => $paket10->id,
            'penanggung_jawab_id' => $pjPusat->id,
            'status' => 'on',
        ]);

        // ==========================================
        // 7. PENGATURAN
        // ==========================================
        Pengaturan::create([
            'nama' => 'PT. BuanaNet Indonesia',
            'email' => 'info@buananet.id',
            'telepon' => '021-12345678',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta Pusat',
        ]);

        // ==========================================
        // 8. PRODUK
        // ==========================================
        $prodModem = Produk::create([
            'nama' => 'Modem ONT Fiber',
            'kategori' => 'Perangkat',
            'stok' => 20,
            'harga' => 350000,
            'harga_beli' => 200000,
        ]);
        $prodKabel = Produk::create([
            'nama' => 'Kabel UTP 10 meter',
            'kategori' => 'Aksesoris',
            'stok' => 50,
            'harga' => 50000,
            'harga_beli' => 30000,
        ]);
        $prodRouter = Produk::create([
            'nama' => 'Router WiFi AX1800',
            'kategori' => 'Perangkat',
            'stok' => 15,
            'harga' => 450000,
            'harga_beli' => 300000,
        ]);
        $prodSwitch = Produk::create([
            'nama' => 'Switch 8 Port Gigabit',
            'kategori' => 'Perangkat',
            'stok' => 8,
            'harga' => 250000,
            'harga_beli' => 175000,
        ]);
        $prodAdaptor = Produk::create([
            'nama' => 'Adaptor 12V 1A',
            'kategori' => 'Aksesoris',
            'stok' => 0,
            'harga' => 35000,
            'harga_beli' => 20000,
        ]);

        // ==========================================
        // 9. PRODUK CABANG
        // ==========================================
        ProdukCabang::create(['produk_id' => $prodModem->id, 'cabang_id' => $cabangPusat->id, 'stok' => 12]);
        ProdukCabang::create(['produk_id' => $prodModem->id, 'cabang_id' => $cabangBandung->id, 'stok' => 8]);
        ProdukCabang::create(['produk_id' => $prodKabel->id, 'cabang_id' => $cabangPusat->id, 'stok' => 30]);
        ProdukCabang::create(['produk_id' => $prodKabel->id, 'cabang_id' => $cabangBandung->id, 'stok' => 20]);
        ProdukCabang::create(['produk_id' => $prodRouter->id, 'cabang_id' => $cabangPusat->id, 'stok' => 10]);
        ProdukCabang::create(['produk_id' => $prodRouter->id, 'cabang_id' => $cabangBandung->id, 'stok' => 5]);
        ProdukCabang::create(['produk_id' => $prodSwitch->id, 'cabang_id' => $cabangPusat->id, 'stok' => 5]);
        ProdukCabang::create(['produk_id' => $prodSwitch->id, 'cabang_id' => $cabangBandung->id, 'stok' => 3]);

        // ==========================================
        // 10. KAS
        // ==========================================
        $saldo = 0;
        $saldo += 10000000;
        Kas::create(['tanggal' => '2026-05-01', 'tipe' => 'masuk', 'kategori' => 'Modal Awal', 'keterangan' => 'Setoran modal awal usaha', 'jumlah' => 10000000, 'saldo_akhir' => $saldo, 'user_id' => $owner->id]);
        $saldo -= 2000000;
        Kas::create(['tanggal' => '2026-05-03', 'tipe' => 'keluar', 'kategori' => 'Operasional', 'keterangan' => 'Pembelian perangkat ONT 10 pcs', 'jumlah' => 2000000, 'saldo_akhir' => $saldo, 'user_id' => $owner->id]);
        $saldo += 150000;
        Kas::create(['tanggal' => '2026-05-06', 'tipe' => 'masuk', 'kategori' => 'Pembayaran', 'keterangan' => 'Pembayaran invoice Rina Kusuma', 'jumlah' => 150000, 'saldo_akhir' => $saldo, 'user_id' => $pegawai->id]);
        $saldo += 250000;
        Kas::create(['tanggal' => '2026-05-15', 'tipe' => 'masuk', 'kategori' => 'Pembayaran', 'keterangan' => 'Pembayaran invoice Agus Prasetyo', 'jumlah' => 250000, 'saldo_akhir' => $saldo, 'user_id' => $pegawai->id]);
        $saldo -= 500000;
        Kas::create(['tanggal' => '2026-05-20', 'tipe' => 'keluar', 'kategori' => 'Operasional', 'keterangan' => 'Biaya listrik bulan Mei', 'jumlah' => 500000, 'saldo_akhir' => $saldo, 'user_id' => $pegawai->id]);

        // ==========================================
        // 11. INVOICES (via DB table agar kode_invoice bisa diatur manual)
        // ==========================================
        $inv1Id = DB::table('invoice')->insertGetId([
            'kode_invoice' => 'INV-20260504-0001',
            'pelanggan_id' => $pel1->id,
            'user_id' => $pegawai->id,
            'diskon' => 0,
            'pajak' => 0,
            'total' => 150000,
            'dibayar' => 150000,
            'sisa' => 0,
            'status' => 'lunas',
            'sumber' => 'wifi',
            'metode_pembayaran' => 'cash',
            'tanggal' => '2026-05-04',
            'jatuh_tempo' => '2026-05-04',
            'created_at' => '2026-05-04 10:00:00',
            'updated_at' => '2026-05-04 10:00:00',
        ]);
        $inv2Id = DB::table('invoice')->insertGetId([
            'kode_invoice' => 'INV-20260511-0001',
            'pelanggan_id' => $pel2->id,
            'user_id' => $pegawai->id,
            'diskon' => 0,
            'pajak' => 0,
            'total' => 250000,
            'dibayar' => 250000,
            'sisa' => 0,
            'status' => 'lunas',
            'sumber' => 'wifi',
            'metode_pembayaran' => 'cash',
            'tanggal' => '2026-05-11',
            'jatuh_tempo' => '2026-05-11',
            'created_at' => '2026-05-11 10:00:00',
            'updated_at' => '2026-05-11 10:00:00',
        ]);
        $inv3Id = DB::table('invoice')->insertGetId([
            'kode_invoice' => 'INV-20260518-0001',
            'pelanggan_id' => $pel3->id,
            'user_id' => $pegawai->id,
            'diskon' => 0,
            'pajak' => 50000,
            'total' => 550000,
            'dibayar' => 300000,
            'sisa' => 250000,
            'status' => 'cicilan',
            'sumber' => 'wifi',
            'metode_pembayaran' => 'bank',
            'tanggal' => '2026-05-18',
            'jatuh_tempo' => '2026-06-18',
            'created_at' => '2026-05-18 10:00:00',
            'updated_at' => '2026-05-18 10:00:00',
        ]);
        $inv4Id = DB::table('invoice')->insertGetId([
            'kode_invoice' => 'INV-20260605-0001',
            'pelanggan_id' => $pel5->id,
            'user_id' => $userCabang->id,
            'diskon' => 0,
            'pajak' => 0,
            'total' => 250000,
            'dibayar' => 0,
            'sisa' => 250000,
            'status' => 'cicilan',
            'sumber' => 'wifi',
            'metode_pembayaran' => 'cash',
            'tanggal' => '2026-06-05',
            'jatuh_tempo' => '2026-07-05',
            'created_at' => '2026-06-05 10:00:00',
            'updated_at' => '2026-06-05 10:00:00',
        ]);
        // Invoice untuk uroooooo - piutang bulan lalu sisa 10.000
        $inv5Id = DB::table('invoice')->insertGetId([
            'kode_invoice' => 'INV-20260525-0001',
            'pelanggan_id' => $pel7->id,
            'user_id' => $pegawai->id,
            'diskon' => 0,
            'pajak' => 0,
            'total' => 150000,
            'dibayar' => 140000,
            'sisa' => 10000,
            'status' => 'cicilan',
            'sumber' => 'wifi',
            'metode_pembayaran' => 'cash',
            'tanggal' => '2026-05-25',
            'jatuh_tempo' => '2026-06-25',
            'created_at' => '2026-05-25 10:00:00',
            'updated_at' => '2026-05-25 10:00:00',
        ]);

        // ==========================================
        // 12. INVOICE DETAILS
        // ==========================================
        DB::table('invoice_detail')->insert([
            [
                'invoice_id' => $inv1Id,
                'produk_id' => null,
                'keterangan' => 'Paket 10 Mbps - Mei 2026',
                'qty' => 1,
                'harga' => 150000,
                'subtotal' => 150000,
                'created_at' => '2026-05-04 10:00:00',
                'updated_at' => '2026-05-04 10:00:00',
            ],
            [
                'invoice_id' => $inv2Id,
                'produk_id' => null,
                'keterangan' => 'Paket 20 Mbps - Mei 2026',
                'qty' => 1,
                'harga' => 250000,
                'subtotal' => 250000,
                'created_at' => '2026-05-11 10:00:00',
                'updated_at' => '2026-05-11 10:00:00',
            ],
            [
                'invoice_id' => $inv3Id,
                'produk_id' => null,
                'keterangan' => 'Paket 50 Mbps - Mei 2026',
                'qty' => 1,
                'harga' => 500000,
                'subtotal' => 500000,
                'created_at' => '2026-05-18 10:00:00',
                'updated_at' => '2026-05-18 10:00:00',
            ],
            [
                'invoice_id' => $inv4Id,
                'produk_id' => null,
                'keterangan' => 'Paket 20 Mbps - Juni 2026',
                'qty' => 1,
                'harga' => 250000,
                'subtotal' => 250000,
                'created_at' => '2026-06-05 10:00:00',
                'updated_at' => '2026-06-05 10:00:00',
            ],
            [
                'invoice_id' => $inv5Id,
                'produk_id' => null,
                'keterangan' => 'Paket 10 Mbps - Mei 2026',
                'qty' => 1,
                'harga' => 150000,
                'subtotal' => 150000,
                'created_at' => '2026-05-25 10:00:00',
                'updated_at' => '2026-05-25 10:00:00',
            ],
        ]);

        // ==========================================
        // 13. PEMBAYARAN
        // ==========================================
        Pembayaran::create([
            'invoice_id' => $inv1Id,
            'user_id' => $pegawai->id,
            'tanggal' => '2026-05-04',
            'nominal' => 150000,
            'metode' => 'cash',
            'keterangan' => 'Pembayaran lunas',
        ]);
        Pembayaran::create([
            'invoice_id' => $inv2Id,
            'user_id' => $pegawai->id,
            'tanggal' => '2026-05-11',
            'nominal' => 250000,
            'metode' => 'cash',
            'keterangan' => 'Pembayaran lunas',
        ]);
        Pembayaran::create([
            'invoice_id' => $inv3Id,
            'user_id' => $pegawai->id,
            'tanggal' => '2026-05-18',
            'nominal' => 300000,
            'metode' => 'bank',
            'keterangan' => 'Pembayaran DP 300rb',
        ]);

        // ==========================================
        // 14. BIAYA OPERASIONAL
        // ==========================================
        BiayaOperasional::create([
            'tanggal' => '2026-05-10',
            'kategori' => 'Listrik',
            'keterangan' => 'Tagihan listrik kantor bulan Mei',
            'jumlah' => 500000,
            'user_id' => $pegawai->id,
        ]);
        BiayaOperasional::create([
            'tanggal' => '2026-05-15',
            'kategori' => 'Internet',
            'keterangan' => 'Biaya langganan internet upstream',
            'jumlah' => 1200000,
            'user_id' => $pegawai->id,
        ]);
        BiayaOperasional::create([
            'tanggal' => '2026-05-25',
            'kategori' => 'Gaji',
            'keterangan' => 'Gaji karyawan bulan Mei',
            'jumlah' => 5000000,
            'user_id' => $owner->id,
        ]);

        // ==========================================
        // 15. ABSENSI (Mei 2026)
        // ==========================================
        $startDate = Carbon::parse('2026-05-01');
        for ($i = 0; $i < 31; $i++) {
            $tgl = $startDate->copy()->addDays($i);
            if ($tgl->isWeekday()) {
                Absensi::create([
                    'user_id' => $pegawai->id,
                    'karyawan_id' => $karyawan1->id,
                    'tanggal' => $tgl->format('Y-m-d'),
                    'status' => 'datang',
                    'jam_masuk' => '08:00:00',
                    'jam_pulang' => '17:00:00',
                    'created_by' => $owner->id,
                ]);
                Absensi::create([
                    'user_id' => $pegawai->id,
                    'karyawan_id' => $karyawan2->id,
                    'tanggal' => $tgl->format('Y-m-d'),
                    'status' => 'datang',
                    'jam_masuk' => '08:15:00',
                    'jam_pulang' => '17:00:00',
                    'created_by' => $owner->id,
                ]);
                $status3 = ($i % 5 === 0) ? 'terlambat' : 'datang';
                $jam3 = ($i % 5 === 0) ? '08:45:00' : '08:00:00';
                Absensi::create([
                    'user_id' => $userCabang->id,
                    'karyawan_id' => $karyawan3->id,
                    'tanggal' => $tgl->format('Y-m-d'),
                    'status' => $status3,
                    'jam_masuk' => $jam3,
                    'jam_pulang' => '17:00:00',
                    'keterangan' => ($i % 5 === 0) ? 'Macet' : null,
                    'created_by' => $owner->id,
                ]);
            }
        }

        // ==========================================
        // 16. LOG STOK
        // ==========================================
        LogStok::create([
            'produk_id' => $prodModem->id,
            'aksi' => 'masuk',
            'jumlah' => 20,
            'cabang_id' => $cabangPusat->id,
            'user_id' => $owner->id,
            'keterangan' => 'Pembelian awal stok modem ONT',
            'tanggal' => '2026-05-01',
        ]);
        LogStok::create([
            'produk_id' => $prodKabel->id,
            'aksi' => 'masuk',
            'jumlah' => 50,
            'cabang_id' => $cabangPusat->id,
            'user_id' => $owner->id,
            'keterangan' => 'Pembelian awal stok kabel UTP',
            'tanggal' => '2026-05-01',
        ]);
        LogStok::create([
            'produk_id' => $prodModem->id,
            'aksi' => 'pindah',
            'jumlah' => 8,
            'cabang_id' => $cabangBandung->id,
            'user_id' => $owner->id,
            'keterangan' => 'Transfer stok ke cabang Bandung',
            'tanggal' => '2026-05-03',
        ]);
        LogStok::create([
            'produk_id' => $prodRouter->id,
            'aksi' => 'masuk',
            'jumlah' => 15,
            'cabang_id' => $cabangPusat->id,
            'user_id' => $owner->id,
            'keterangan' => 'Pembelian awal stok router',
            'tanggal' => '2026-05-01',
        ]);

        // ==========================================
        // 17. LOG AKTIVITAS
        // ==========================================
        LogAktivitas::create([
            'user_id' => $owner->id,
            'aksi' => 'create',
            'model' => 'cabang',
            'model_id' => (string) $cabangPusat->id,
            'deskripsi' => 'Membuat cabang baru: '.$cabangPusat->nama,
            'data_baru' => $cabangPusat->toArray(),
        ]);
        LogAktivitas::create([
            'user_id' => $owner->id,
            'aksi' => 'create',
            'model' => 'pelanggan',
            'model_id' => (string) $pel1->id,
            'deskripsi' => 'Mendaftarkan pelanggan baru: '.$pel1->nama,
            'data_baru' => $pel1->toArray(),
        ]);
        LogAktivitas::create([
            'user_id' => $pegawai->id,
            'aksi' => 'create',
            'model' => 'invoice',
            'model_id' => (string) $inv1Id,
            'deskripsi' => 'Membuat invoice untuk Rina Kusuma Rp150.000',
            'data_baru' => ['total' => 150000],
        ]);

        $this->call(TestDataSeeder::class);
    }
}
