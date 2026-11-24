<?php

namespace App\Console\Commands;

use App\Models\Kas;
use App\Models\User;
use Illuminate\Console\Command;

class SeedKasTestData extends Command
{
    protected $signature = 'kas:seed-test {--count=1000} {--date=}';

    protected $description = 'Generate data uji kas (laporan keuangan) untuk mengetes filter & pagination';

    public function handle()
    {
        $count = (int) $this->option('count');
        $date = $this->option('date') ?: now()->toDateString();

        $masuk = [
            ['kategori' => 'pembayaran', 'keterangan' => 'Pembayaran invoice INV-2026{no}'],
            ['kategori' => 'pembayaran', 'keterangan' => 'Pembayaran tagihan WiFi bulanan pelanggan'],
            ['kategori' => 'pemasukan', 'keterangan' => 'Pemasukan lain: {ket}'],
            ['kategori' => 'manual', 'keterangan' => 'Setoran kas dari penjualan tunai'],
        ];
        $keluar = [
            ['kategori' => 'operasional', 'keterangan' => 'Biaya operasional: {ket}'],
            ['kategori' => 'restok', 'keterangan' => 'Pembelian stok produk: {ket}'],
            ['kategori' => 'pemakaian', 'keterangan' => 'Pemakaian stok cabang: {ket}'],
            ['kategori' => 'manual', 'keterangan' => 'Pengeluaran manual: {ket}'],
        ];
        $keteranganPool = ['ATK kantor', 'listrik', 'internet', 'gaji karyawan', 'transport', 'pemeliharaan jaringan', 'operasional harian', 'peralatan', 'makan minum', 'promosi'];

        $last = Kas::orderByDesc('id')->first();
        $saldo = $last ? (float) $last->saldo_akhir : 0;

        $userIds = User::pluck('id')->all();
        $now = now();

        $jumlahMasuk = [150000, 250000, 300000, 500000, 750000, 1000000];
        $jumlahKeluar = [50000, 75000, 100000, 150000, 250000, 500000];

        $inserts = [];
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 1; $i <= $count; $i++) {
            $tipe = rand(1, 100) <= 65 ? 'masuk' : 'keluar';
            $template = $tipe === 'masuk' ? $masuk[array_rand($masuk)] : $keluar[array_rand($keluar)];
            $ket = $keteranganPool[array_rand($keteranganPool)];
            $keterangan = str_replace(
                ['{ket}', '{no}'],
                [$ket, str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT)],
                $template['keterangan']
            );

            $jumlah = $tipe === 'masuk'
                ? $jumlahMasuk[array_rand($jumlahMasuk)]
                : $jumlahKeluar[array_rand($jumlahKeluar)];

            $saldo = $tipe === 'masuk' ? $saldo + $jumlah : $saldo - $jumlah;

            $inserts[] = [
                'tanggal' => $date,
                'tipe' => $tipe,
                'kategori' => $template['kategori'],
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
                'saldo_akhir' => $saldo,
                'user_id' => ! empty($userIds) ? $userIds[array_rand($userIds)] : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($inserts) >= 500) {
                Kas::insert($inserts);
                $inserts = [];
            }
            $bar->advance();
        }

        if (! empty($inserts)) {
            Kas::insert($inserts);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Selesai: {$count} data kas untuk tanggal {$date} (saldo akhir: Rp ".number_format($saldo, 0, ',', '.').')');
    }
}
