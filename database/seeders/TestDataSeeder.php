<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\PaketWifi;
use App\Models\Pelanggan;
use App\Models\PenanggungJawabWifi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $paketIds = PaketWifi::pluck('id')->toArray();
        $pjIds = PenanggungJawabWifi::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        if (empty($paketIds) || empty($pjIds) || empty($userIds)) {
            $this->command->error('Jalankan DatabaseSeeder dulu untuk data referensi (paket, PJ, user)!');

            return;
        }

        $hargaPaket = PaketWifi::pluck('harga', 'id')->toArray();

        $namaDepan = ['Budi', 'Siti', 'Ahmad', 'Dewi', 'Rudi', 'Mega', 'Agus', 'Rina', 'Dodi', 'Eka',
            'Fajar', 'Gita', 'Hendra', 'Indah', 'Joko', 'Karin', 'Lutfi', 'Mira', 'Novi', 'Oscar',
            'Putri', 'Qori', 'Rizky', 'Sari', 'Tono', 'Udin', 'Vina', 'Wawan', 'Yuda', 'Zara',
            'Adi', 'Bella', 'Cici', 'Dani', 'Edo', 'Fitri', 'Gunawan', 'Hana', 'Irwan', 'Juli',
            'Kiki', 'Lina', 'Maman', 'Nina', 'Oman', 'Pia', 'Rama', 'Santi', 'Tari'];

        $tipePool = ['individu', 'individu', 'individu', 'instansi', 'mitra'];

        $this->command->info('Membuat 1000 pelanggan...');

        $pelangganData = [];
        $total = 1000;

        for ($i = 0; $i < $total; $i++) {
            $nama = $namaDepan[array_rand($namaDepan)].' '.fake()->lastName();
            $tipe = $tipePool[array_rand($tipePool)];
            $status = rand(1, 10) > 2 ? 'on' : 'off';
            $paketId = $paketIds[array_rand($paketIds)];

            $pelangganData[] = [
                'nama' => $nama,
                'telepon' => '08'.fake()->numerify('##########'),
                'alamat' => fake()->address(),
                'tipe' => $tipe,
                'paket_id' => $paketId,
                'penanggung_jawab_id' => $pjIds[array_rand($pjIds)],
                'status' => $status,
                'tanggal_off' => $status === 'off' ? now()->subDays(rand(1, 90))->format('Y-m-d') : null,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        }

        foreach (array_chunk($pelangganData, 100) as $chunk) {
            DB::table('pelanggan')->insert($chunk);
        }

        $this->command->info('Membuat invoice untuk 1000 pelanggan...');

        $semuaPelanggan = Pelanggan::select('id', 'paket_id')->get()->keyBy('id');
        $invoiceData = [];
        $kodeCounter = [];
        $baris = 0;

        foreach ($semuaPelanggan as $pelangganId => $pel) {
            $harga = $hargaPaket[$pel->paket_id] ?? 150000;
            $jumlahInvoice = rand(1, 3);
            $bulanMulai = now()->subMonths($jumlahInvoice);

            for ($j = 0; $j < $jumlahInvoice; $j++) {
                $tanggal = $bulanMulai->copy()->addMonths($j)->format('Y-m-d');
                $tglKey = str_replace('-', '', $tanggal);

                if (! isset($kodeCounter[$tglKey])) {
                    $maxKode = Invoice::where('kode_invoice', 'like', 'INV-'.$tglKey.'-%')
                        ->max('kode_invoice');
                    $kodeCounter[$tglKey] = $maxKode ? (int) substr($maxKode, -4) : 0;
                }
                $kodeCounter[$tglKey]++;
                $kodeInvoice = 'INV-'.$tglKey.'-'.str_pad($kodeCounter[$tglKey], 4, '0', STR_PAD_LEFT);

                $totalHarga = $harga;
                $isLunas = rand(1, 10) > 4;

                if ($isLunas) {
                    $dibayar = $totalHarga;
                    $sisa = 0;
                    $statusInv = 'lunas';
                } else {
                    $dibayar = (int) ($totalHarga * rand(0, 70) / 100);
                    $sisa = $totalHarga - $dibayar;
                    $statusInv = 'cicilan';
                }

                $invoiceData[] = [
                    'kode_invoice' => $kodeInvoice,
                    'pelanggan_id' => $pelangganId,
                    'user_id' => $userIds[array_rand($userIds)],
                    'diskon' => 0,
                    'pajak' => 0,
                    'total' => $totalHarga,
                    'dibayar' => $dibayar,
                    'sisa' => $sisa,
                    'status' => $statusInv,
                    'sumber' => 'wifi',
                    'metode_pembayaran' => rand(0, 1) ? 'cash' : 'bank',
                    'tanggal' => $tanggal,
                    'jatuh_tempo' => $bulanMulai->copy()->addMonths($j + 1)->format('Y-m-d'),
                    'created_at' => $tanggal.' 10:00:00',
                    'updated_at' => $tanggal.' 10:00:00',
                ];
            }

            $baris++;
            if ($baris % 100 === 0) {
                $this->command->info('  Progress invoice: '.$baris.'/'.$total.' pelanggan...');
            }
        }

        foreach (array_chunk($invoiceData, 200) as $chunk) {
            DB::table('invoice')->insert($chunk);
        }

        $this->command->info('Membuat invoice detail...');

        DB::table('invoice')->orderBy('id')->chunk(200, function ($invoices) {
            $detailData = [];
            foreach ($invoices as $inv) {
                $harga = (int) $inv->total;
                $detailData[] = [
                    'invoice_id' => $inv->id,
                    'produk_id' => null,
                    'keterangan' => 'Paket WiFi - Rp '.number_format($harga, 0, ',', '.'),
                    'qty' => 1,
                    'harga' => $harga,
                    'subtotal' => $harga,
                    'created_at' => $inv->created_at,
                    'updated_at' => $inv->updated_at,
                ];
            }
            DB::table('invoice_detail')->insert($detailData);
        });

        $this->command->info('Selesai! '.$total.' pelanggan dengan invoice berhasil dibuat.');
    }
}
