<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\PenanggungJawabWifi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanWifiController extends Controller
{
    public function index(Request $request)
    {
        $penanggungJawab = PenanggungJawabWifi::orderBy('nama')->get();

        $bulan = $request->bulan ?? now()->format('Y-m');
        $tahun = substr($bulan, 0, 4);
        $bulanNum = substr($bulan, 5, 2);

        $tagihan = Invoice::with(['pelanggan', 'details'])
            ->where('sumber', 'wifi')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->orderBy('created_at', 'desc')
            ->paginate(25)->onEachSide(2);

        $preview = null;
        $filter = [];

        if ($request->has('tanggal') && $request->has('jatuh_tempo')) {
            $filter = [
                'kategori' => $request->kategori,
                'pj_id' => $request->pj_id,
                'tanggal' => $request->tanggal,
                'jatuh_tempo' => $request->jatuh_tempo,
            ];

            $query = Pelanggan::with('paket')->aktif();

            if ($request->kategori === 'pj' && $request->pj_id) {
                $query->where('penanggung_jawab_id', $request->pj_id);
            } elseif ($request->kategori && $request->kategori !== 'semua') {
                $query->where('tipe', $request->kategori);
            }

            $pelangganList = $query->get();
            $preview = [];
            $bulanIni = Carbon::parse($request->tanggal);
            $bulanLalu = $bulanIni->copy()->subMonth();

            // Batch load: ambil semua invoice yang sudah ada bulan ini untuk cegah duplikat
            $pelangganIds = $pelangganList->pluck('id')->toArray();
            $existingThisMonth = Invoice::where('sumber', 'wifi')
                ->whereIn('pelanggan_id', $pelangganIds)
                ->whereMonth('tanggal', $bulanIni->month)
                ->whereYear('tanggal', $bulanIni->year)
                ->pluck('pelanggan_id')
                ->toArray();

            // Batch load: ambil semua piutang bulan lalu dalam 2 query
            $piutangSummary = Invoice::whereIn('pelanggan_id', $pelangganIds)
                ->where('status', 'cicilan')
                ->where('sisa', '>', 0)
                ->whereMonth('tanggal', $bulanLalu->month)
                ->whereYear('tanggal', $bulanLalu->year)
                ->groupBy('pelanggan_id')
                ->selectRaw('pelanggan_id, SUM(sisa) as total_sisa')
                ->pluck('total_sisa', 'pelanggan_id');

            $piutangDetails = Invoice::whereIn('pelanggan_id', $pelangganIds)
                ->where('status', 'cicilan')
                ->where('sisa', '>', 0)
                ->whereMonth('tanggal', $bulanLalu->month)
                ->whereYear('tanggal', $bulanLalu->year)
                ->orderBy('id')
                ->get(['id', 'pelanggan_id', 'kode_invoice', 'tanggal', 'sisa'])
                ->groupBy('pelanggan_id');

            foreach ($pelangganList as $pelanggan) {
                if (! $pelanggan->paket) {
                    continue;
                }

                $alreadyExists = in_array($pelanggan->id, $existingThisMonth);

                $piutangBulanLalu = (int) ($piutangSummary[$pelanggan->id] ?? 0);

                $piutangDetail = [];
                if ($piutangBulanLalu > 0 && isset($piutangDetails[$pelanggan->id])) {
                    foreach ($piutangDetails[$pelanggan->id] as $inv) {
                        $piutangDetail[] = [
                            'kode' => $inv->kode_invoice,
                            'bulan' => Carbon::parse($inv->tanggal)->translatedFormat('F Y'),
                            'sisa' => $inv->sisa,
                        ];
                    }
                }

                $hargaPokok = $pelanggan->paket->harga;
                $hargaTotal = $hargaPokok + $piutangBulanLalu;

                if ($piutangBulanLalu > 0) {
                    $ket = 'Tagihan '.$bulanIni->translatedFormat('F Y').' - Paket '.$pelanggan->paket->nama
                        .' + Piutang '.$bulanLalu->translatedFormat('F Y').' Rp '.number_format($piutangBulanLalu, 0, ',', '.');
                } else {
                    $ket = 'Tagihan '.$bulanIni->translatedFormat('F Y').' - Paket '.$pelanggan->paket->nama;
                }

                $preview[] = [
                    'pelanggan_id' => $pelanggan->id,
                    'nama' => $pelanggan->nama,
                    'tipe' => $pelanggan->tipe,
                    'paket' => $pelanggan->paket->nama,
                    'harga_pokok' => $hargaPokok,
                    'harga' => $hargaTotal,
                    'piutang' => $piutangBulanLalu,
                    'piutang_detail' => $piutangDetail,
                    'keterangan' => $ket,
                    'already_exists' => $alreadyExists,
                ];
            }
        }

        return view('pages.tagihan-wifi.index', compact(
            'tagihan', 'penanggungJawab', 'preview', 'filter', 'bulan'
        ));
    }

    public function fetchData(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        $tahun = substr($bulan, 0, 4);
        $bulanNum = substr($bulan, 5, 2);

        $tagihan = Invoice::with(['pelanggan', 'details'])
            ->where('sumber', 'wifi')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->orderBy('created_at', 'desc')
            ->paginate(25)->onEachSide(2)->withQueryString();

        return response()->json([
            'rows' => view('pages.tagihan-wifi.partials.rows', compact('tagihan'))->render(),
            'pagination' => view('pages.tagihan-wifi.partials.pagination', compact('tagihan'))->render(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jatuh_tempo' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.pelanggan_id' => 'required|exists:pelanggan,id',
            'items.*.keterangan' => 'required|string',
            'items.*.harga' => 'required|string',
        ]);

        $tanggal = $request->tanggal;
        $jatuhTempo = $request->jatuh_tempo;
        $items = $request->items;

        $count = 0;
        $pelangganIds = array_column($items, 'pelanggan_id');
        $pelangganMap = Pelanggan::whereIn('id', $pelangganIds)->get()->keyBy('id');

        DB::beginTransaction();
        try {
            $tanggalNow = now()->format('Ymd');
            $lastKode = Invoice::whereDate('created_at', now()->toDateString())
                ->lockForUpdate()
                ->max('kode_invoice');
            $lastNum = $lastKode ? (int) substr($lastKode, -4) : 0;

            $now = now();
            $invoiceDetails = [];
            foreach ($items as $item) {
                $pelanggan = $pelangganMap->get($item['pelanggan_id']);
                if (! $pelanggan) {
                    continue;
                }

                $lastNum++;
                $no = str_pad($lastNum, 4, '0', STR_PAD_LEFT);
                $harga = (float) preg_replace('/[^0-9]/', '', $item['harga']);

                $invoice = Invoice::create([
                    'kode_invoice' => 'INV-'.$tanggalNow.'-'.$no,
                    'pelanggan_id' => $pelanggan->id,
                    'user_id' => auth()->id(),
                    'diskon' => 0,
                    'pajak' => 0,
                    'total' => $harga,
                    'dibayar' => 0,
                    'sisa' => $harga,
                    'status' => 'cicilan',
                    'metode_pembayaran' => null,
                    'tanggal' => $tanggal,
                    'jatuh_tempo' => $jatuhTempo,
                    'sumber' => 'wifi',
                ]);

                $invoiceDetails[] = [
                    'invoice_id' => $invoice->id,
                    'produk_id' => null,
                    'keterangan' => $item['keterangan'],
                    'qty' => 1,
                    'harga' => $harga,
                    'subtotal' => $harga,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $count++;
            }
            if (! empty($invoiceDetails)) {
                InvoiceDetail::insert($invoiceDetails);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $bulan = Carbon::parse($tanggal)->translatedFormat('F Y');
        LogAktivitas::catat('create', 'TagihanWifi', null, 'Membuat '.$count.' tagihan WiFi bulan '.$bulan);

        return redirect()->route('tagihan-wifi.index')->with('success', $count.' tagihan WiFi berhasil dibuat');
    }

    public function destroy($id)
    {
        $invoice = Invoice::where('sumber', 'wifi')->findOrFail($id);

        if ($invoice->status === 'lunas') {
            return redirect()->back()->with('error', 'Tagihan sudah lunas, tidak bisa dihapus');
        }

        LogAktivitas::catat('delete', 'TagihanWifi', $id, 'Menghapus tagihan WiFi: '.$invoice->kode_invoice);
        $invoice->delete();

        return redirect()->back()->with('success', 'Tagihan WiFi berhasil dihapus');
    }
}
