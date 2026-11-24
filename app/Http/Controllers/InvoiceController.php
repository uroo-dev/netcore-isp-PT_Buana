<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Kas;
use App\Models\LogAktivitas;
use App\Models\Pembayaran;
use App\Models\PenanggungJawabWifi;
use App\Models\Pengaturan;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.invoice.index', $this->buildData($request, false));
    }

    public function fetchData(Request $request)
    {
        if (! $request->expectsJson()) {
            return redirect()->route('invoice.index');
        }

        $data = $this->buildData($request, false);

        return response()->json([
            'rows' => view('pages.invoice.partials.rows', $data)->render(),
            'pagination' => view('pages.invoice.partials.pagination', $data)->render(),
            'total' => $data['invoices']->total(),
        ]);
    }

    public function kasbon(Request $request)
    {
        return view('pages.invoice.kasbon', $this->buildData($request, true));
    }

    public function fetchKasbon(Request $request)
    {
        if (! $request->expectsJson()) {
            return redirect()->route('kasbon.index');
        }

        $data = $this->buildData($request, true);

        return response()->json([
            'rows' => view('pages.invoice.partials.rows', $data)->render(),
            'pagination' => view('pages.invoice.partials.pagination', $data)->render(),
            'total' => $data['invoices']->total(),
        ]);
    }

    private function buildData(Request $request, $kasbon = false)
    {
        $query = Invoice::with(['pelanggan', 'user'])->latest();

        if ($kasbon) {
            $query->where('status', 'cicilan');
        }

        if (! $kasbon) {
            $tanggalMulai = $request->tanggal_mulai;
            $tanggalAkhir = $request->tanggal_akhir;

            if (! $tanggalMulai && ! $tanggalAkhir) {
                $tanggalMulai = now()->startOfMonth()->format('Y-m-d');
                $tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
            }

            if ($tanggalMulai && $tanggalAkhir) {
                $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
            } elseif ($tanggalMulai) {
                $query->where('tanggal', '>=', $tanggalMulai);
            } elseif ($tanggalAkhir) {
                $query->where('tanggal', '<=', $tanggalAkhir);
            }
        } else {
            $tanggalMulai = $request->tanggal_mulai;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->tipe_pelanggan) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('tipe', $request->tipe_pelanggan);
            });
        }

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_invoice', 'like', '%'.$request->q.'%')
                    ->orWhereHas('pelanggan', function ($q) use ($request) {
                        $q->where('nama', 'like', '%'.$request->q.'%');
                    });
            });
        }

        if ($request->penanggung_jawab) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('penanggung_jawab_id', $request->penanggung_jawab);
            });
        }

        $invoices = $query->paginate(25)->onEachSide(2)->withQueryString();
        $penanggungJawabList = PenanggungJawabWifi::orderBy('nama')->get();

        return compact('invoices', 'penanggungJawabList', 'tanggalMulai', 'tanggalAkhir');
    }

    public function create()
    {
        $produk = Produk::where('stok', '>', 0)->orderBy('nama')->get();

        return view('pages.invoice.create', compact('produk'));
    }

    public function getPiutangPelanggan($pelangganId)
    {
        $totalPiutang = Invoice::where('pelanggan_id', $pelangganId)
            ->whereIn('status', ['cicilan'])
            ->sum('sisa');

        return response()->json([
            'total_piutang' => $totalPiutang,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'diskon' => 'nullable|string',
            'pajak' => 'nullable|string',
            'tanggal' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal',
            'metode_pembayaran' => 'nullable|in:cash,bank',
            'dibayar_langsung' => 'nullable|string',
            'exclude_piutang' => 'nullable|in:0,1',
            'items' => 'required|array|min:1',
            'items.*.tipe' => 'required|in:produk,lainnya',
            'items.*.produk_id' => 'nullable|integer',
            'items.*.keterangan' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|string',
        ]);

        $parseIdr = function ($val) {
            return (float) preg_replace('/[^0-9]/', '', $val);
        };

        DB::beginTransaction();

        try {
            $totalItem = 0;

            $produkIds = array_filter(array_column($validated['items'], 'produk_id'));
            $produkMap = ! empty($produkIds) ? Produk::whereIn('id', $produkIds)->get()->keyBy('id') : collect();

            $produkIdsInItems = [];
            foreach ($validated['items'] as &$item) {
                $item['harga'] = $parseIdr($item['harga']);

                if ($item['tipe'] === 'produk' && ! empty($item['produk_id'])) {
                    $produk = $produkMap->get($item['produk_id']);
                    if ($produk && $item['qty'] > $produk->stok) {
                        throw new \Exception("Stok {$produk->nama} tidak mencukupi. Stok tersedia: {$produk->stok}");
                    }
                    if (in_array($item['produk_id'], $produkIdsInItems)) {
                        throw new \Exception("Produk {$produk->nama} tidak boleh ditambahkan lebih dari sekali. Silakan gunakan qty untuk jumlah lebih dari 1.");
                    }
                    $produkIdsInItems[] = $item['produk_id'];
                }

                $subtotal = $item['qty'] * $item['harga'];
                $totalItem += $subtotal;
            }
            unset($item);

            $diskon = $parseIdr($validated['diskon'] ?? 0);
            $pajak = $parseIdr($validated['pajak'] ?? 0);

            $total = $totalItem - $diskon + $pajak;

            $excludePiutang = ($validated['exclude_piutang'] ?? '0') === '1';
            $piutangSebelumnya = 0;

            if (! $excludePiutang) {
                $piutangSebelumnya = Invoice::where('pelanggan_id', $validated['pelanggan_id'])
                    ->whereIn('status', ['cicilan'])
                    ->sum('sisa');
            }

            $grandTotal = $total + $piutangSebelumnya;

            $dibayarLangsung = $parseIdr($validated['dibayar_langsung'] ?? 0);
            $sisa = $grandTotal - $dibayarLangsung;

            $status = $sisa <= 0 ? 'lunas' : 'cicilan';

            $invoice = Invoice::create([
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => auth()->id(),
                'diskon' => $diskon,
                'pajak' => $pajak,
                'total' => $grandTotal,
                'dibayar' => $dibayarLangsung,
                'sisa' => max(0, $sisa),
                'status' => $status,
                'metode_pembayaran' => $validated['metode_pembayaran'] ?? null,
                'tanggal' => $validated['tanggal'],
                'jatuh_tempo' => $validated['jatuh_tempo'],
                'sumber' => 'manual',
            ]);

            $now = now();
            $invoiceDetails = [];
            foreach ($validated['items'] as $item) {
                $invoiceDetails[] = [
                    'invoice_id' => $invoice->id,
                    'produk_id' => $item['tipe'] === 'produk' ? $item['produk_id'] : null,
                    'keterangan' => $item['tipe'] === 'lainnya' ? ($item['keterangan'] ?? 'Lainnya') : null,
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($item['tipe'] === 'produk' && ! empty($item['produk_id'])) {
                    Produk::where('id', $item['produk_id'])->decrement('stok', $item['qty']);
                }
            }
            if (! empty($invoiceDetails)) {
                InvoiceDetail::insert($invoiceDetails);
            }

            $kasTerakhir = Kas::orderBy('id', 'desc')->first();
            $saldoBerjalan = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

            if (! $excludePiutang && $piutangSebelumnya > 0) {
                Invoice::where('pelanggan_id', $validated['pelanggan_id'])
                    ->where('status', 'cicilan')
                    ->oldest('jatuh_tempo')
                    ->each(function ($inv) use (&$dibayarLangsung, &$saldoBerjalan) {
                        if ($dibayarLangsung <= 0) {
                            return;
                        }

                        $bayarUntukInvoice = min($inv->sisa, $dibayarLangsung);

                        $inv->sisa -= $bayarUntukInvoice;
                        $inv->dibayar += $bayarUntukInvoice;
                        $inv->status = $inv->sisa <= 0 ? 'lunas' : 'cicilan';
                        $inv->save();

                        $dibayarLangsung -= $bayarUntukInvoice;

                        if ($inv->status === 'lunas') {
                            Pembayaran::create([
                                'invoice_id' => $inv->id,
                                'user_id' => auth()->id(),
                                'tanggal' => now(),
                                'nominal' => $bayarUntukInvoice,
                                'metode' => $inv->metode_pembayaran ?? 'cash',
                                'keterangan' => 'Pembayaran otomatis dari invoice baru',
                            ]);

                            $saldoBerjalan += $bayarUntukInvoice;
                            Kas::create([
                                'tanggal' => now(),
                                'tipe' => 'masuk',
                                'kategori' => 'pembayaran',
                                'keterangan' => 'Pembayaran invoice '.$inv->kode_invoice.' (auto)',
                                'jumlah' => $bayarUntukInvoice,
                                'saldo_akhir' => $saldoBerjalan,
                                'user_id' => auth()->id(),
                            ]);
                        }
                    });
            }

            if ($dibayarLangsung > 0) {

                Pembayaran::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => auth()->id(),
                    'tanggal' => now(),
                    'nominal' => $dibayarLangsung,
                    'metode' => $validated['metode_pembayaran'] ?? 'cash',
                    'keterangan' => 'Pembayaran langsung saat buat tagihan',
                ]);

                $saldoBerjalan += $dibayarLangsung;

                Kas::create([
                    'tanggal' => now(),
                    'tipe' => 'masuk',
                    'kategori' => 'pembayaran',
                    'keterangan' => 'Pembayaran invoice '.$invoice->kode_invoice,
                    'jumlah' => $dibayarLangsung,
                    'saldo_akhir' => $saldoBerjalan,
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            $namaPelanggan = $pelanggan->nama ?? '#'.$invoice->pelanggan_id;
            LogAktivitas::catat('create', 'Invoice', $invoice->id, 'Membuat invoice: '.$invoice->kode_invoice.' untuk '.$namaPelanggan);

            return redirect()->route('invoice.show', $invoice->id)
                ->with('success', 'Invoice berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat invoice: '.$e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $invoice = Invoice::with(['pelanggan', 'user', 'details.produk', 'pembayaran.user'])->findOrFail($id);

        return view('pages.invoice.show', compact('invoice'));
    }

    public function bayar($id)
    {
        $invoice = Invoice::with(['pelanggan', 'details.produk'])->findOrFail($id);

        if ($invoice->status === 'lunas') {
            return redirect()->route('invoice.show', $id)
                ->with('info', 'Invoice ini sudah lunas.');
        }

        return view('pages.invoice.bayar', compact('invoice'));
    }

    public function cetakPdf($id)
    {
        $invoice = Invoice::with(['pelanggan', 'details.produk'])->findOrFail($id);
        $pengaturan = Pengaturan::first();

        $tunggakan = Invoice::where('pelanggan_id', $invoice->pelanggan_id)
            ->where('id', '<', $invoice->id)
            ->where('status', 'cicilan')
            ->get();
        $pdf = Pdf::loadView('pages.invoice.pdf', compact('invoice', 'pengaturan', 'tunggakan'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Invoice-'.$invoice->kode_invoice.'.pdf');
    }

    public function prosesBayar(Request $request, $id)
    {
        $invoice = Invoice::with('pelanggan')->findOrFail($id);

        $validated = $request->validate([
            'nominal' => 'required|string',
            'metode' => 'required|in:cash,bank',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $nominal = (float) preg_replace('/[^0-9]/', '', $validated['nominal']);

        $totalSisaPiutang = Invoice::where('pelanggan_id', $invoice->pelanggan_id)
            ->where('id', '<', $invoice->id)
            ->where('status', 'cicilan')
            ->where('sisa', '>', 0)
            ->sum('sisa');

        $totalSisaSemua = $totalSisaPiutang + $invoice->sisa;

        if ($nominal <= 0 || $nominal > $totalSisaSemua) {
            return redirect()->back()->with('error', 'Nominal tidak valid. Maksimal Rp '.number_format($totalSisaSemua, 0, ',', '.'));
        }

        DB::beginTransaction();

        try {
            $sisaNominal = $nominal;

            $piutangInvoices = Invoice::where('pelanggan_id', $invoice->pelanggan_id)
                ->where('id', '<', $invoice->id)
                ->where('status', 'cicilan')
                ->where('sisa', '>', 0)
                ->oldest('jatuh_tempo')
                ->get();

            $kasTerakhir = Kas::orderBy('id', 'desc')->first();
            $saldoBerjalan = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

            foreach ($piutangInvoices as $piutang) {
                if ($sisaNominal <= 0) {
                    break;
                }

                $bayar = min($piutang->sisa, $sisaNominal);
                $piutang->sisa -= $bayar;
                $piutang->dibayar += $bayar;
                $piutang->status = $piutang->sisa <= 0 ? 'lunas' : 'cicilan';
                $piutang->save();
                $sisaNominal -= $bayar;

                Pembayaran::create([
                    'invoice_id' => $piutang->id,
                    'user_id' => auth()->id(),
                    'tanggal' => now(),
                    'nominal' => $bayar,
                    'metode' => $validated['metode'],
                    'keterangan' => 'Pembayaran piutang (dari pembayaran invoice '.$invoice->kode_invoice.')',
                ]);

                $saldoBerjalan += $bayar;
                Kas::create([
                    'tanggal' => now(),
                    'tipe' => 'masuk',
                    'kategori' => 'pembayaran',
                    'keterangan' => 'Pembayaran piutang '.$piutang->kode_invoice.' (dari '.$invoice->kode_invoice.')',
                    'jumlah' => $bayar,
                    'saldo_akhir' => $saldoBerjalan,
                    'user_id' => auth()->id(),
                ]);
            }

            if ($sisaNominal > 0) {
                Pembayaran::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => auth()->id(),
                    'tanggal' => now(),
                    'nominal' => $sisaNominal,
                    'metode' => $validated['metode'],
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);

                $invoice->dibayar += $sisaNominal;
                $invoice->sisa -= $sisaNominal;
                $invoice->metode_pembayaran = $validated['metode'];

                if ($invoice->sisa <= 0) {
                    $invoice->sisa = 0;
                    $invoice->status = 'lunas';
                } else {
                    $invoice->status = 'cicilan';
                }

                $invoice->save();

                $saldoBerjalan += $sisaNominal;
                Kas::create([
                    'tanggal' => now(),
                    'tipe' => 'masuk',
                    'kategori' => 'pembayaran',
                    'keterangan' => 'Pembayaran invoice '.$invoice->kode_invoice,
                    'jumlah' => $sisaNominal,
                    'saldo_akhir' => $saldoBerjalan,
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            LogAktivitas::catat('create', 'Pembayaran', $invoice->id, 'Pembayaran invoice '.$invoice->kode_invoice.': Rp '.number_format($nominal, 0, ',', '.'));

            $pesan = 'Pembayaran berhasil!';
            if ($totalSisaPiutang > 0) {
                $pesan .= ' Piutang lama terbayar Rp '.number_format(min($nominal, $totalSisaPiutang), 0, ',', '.').'.';
            }
            $pesan .= $invoice->status === 'lunas'
                ? ' Invoice '.$invoice->kode_invoice.' sudah LUNAS.'
                : ' Sisa invoice '.$invoice->kode_invoice.': Rp '.number_format($invoice->sisa, 0, ',', '.');

            return redirect()->route('invoice.show', $invoice->id)->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses pembayaran: '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $invoice = Invoice::with('details')->findOrFail($id);

        if ($invoice->status === 'lunas') {
            return back()->with('error', 'Invoice yang sudah lunas tidak bisa dihapus.');
        }

        DB::beginTransaction();

        try {
            foreach ($invoice->details as $detail) {
                if ($detail->produk_id) {
                    Produk::where('id', $detail->produk_id)->increment('stok', $detail->qty);
                }
            }

            $invoice->details()->delete();
            $invoice->pembayaran()->delete();
            $invoice->delete();

            DB::commit();

            LogAktivitas::catat('delete', 'Invoice', $id, 'Menghapus invoice: '.$invoice->kode_invoice);

            return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus invoice: '.$e->getMessage());
        }
    }
}
