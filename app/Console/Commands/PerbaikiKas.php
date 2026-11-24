<?php

namespace App\Console\Commands;

use App\Models\Kas;
use App\Models\LogAktivitas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PerbaikiKas extends Command
{
    protected $signature = 'kas:perbaiki';

    protected $description = 'Perbaiki data kas: koreksi entri pemakaian 757000 -> 10000, hapus kas jumlah <= 0, dan rekalkulasi saldo_akhir';

    public function handle()
    {
        DB::transaction(function () {
            // 1) Koreksi entri pemakaian SOLASI yang keliru (kas id 4478)
            $koreksi = Kas::where('id', 4478)
                ->where('tipe', 'keluar')
                ->where('kategori', 'pemakaian')
                ->where('jumlah', 757000)
                ->where('keterangan', 'like', '%solasi%')
                ->first();

            $koreksiCount = 0;
            if ($koreksi) {
                $koreksi->jumlah = 10000;
                $koreksi->save();
                $koreksiCount = 1;
                $this->info('Koreksi kas #4478: Rp 757.000 -> Rp 10.000');
            } else {
                $this->warn('Kas #4478 tidak ditemukan / tidak cocok dengan kriteria. Lewati koreksi.');
            }

            // 2) Hapus kas dengan jumlah <= 0
            $hapusCount = Kas::where('jumlah', '<=', 0)->count();
            if ($hapusCount > 0) {
                Kas::where('jumlah', '<=', 0)->delete();
                $this->info("Hapus {$hapusCount} entri kas dengan jumlah <= 0");
            } else {
                $this->info('Tidak ada entri kas dengan jumlah <= 0.');
            }

            // 3) Rekalkulasi saldo_akhir (urutan tanggal, id; baseline saldo baris pertama dipertahankan)
            $rows = Kas::orderBy('tanggal')->orderBy('id')->get();
            $rekalkulasiCount = 0;
            $running = 0;
            foreach ($rows as $i => $row) {
                if ($i === 0) {
                    $running = (float) $row->saldo_akhir;

                    continue;
                }
                $running += $row->tipe === 'masuk' ? (float) $row->jumlah : -(float) $row->jumlah;
                if ((float) $row->saldo_akhir !== $running) {
                    $row->saldo_akhir = $running;
                    $row->save();
                    $rekalkulasiCount++;
                }
            }

            $this->info("Rekalkulasi saldo: {$rekalkulasiCount} baris diperbaiki (dari total ".$rows->count().' baris)');

            LogAktivitas::catat(
                'update',
                'Kas',
                null,
                "Perbaikan data kas (artisan kas:perbaiki): koreksi {$koreksiCount}, hapus {$hapusCount}, rekalkulasi {$rekalkulasiCount} baris"
            );
        });

        $saldo = Kas::orderByDesc('id')->first()?->saldo_akhir;
        $this->info('Selesai. Saldo kas terakhir: Rp '.number_format($saldo, 0, ',', '.'));
    }
}
