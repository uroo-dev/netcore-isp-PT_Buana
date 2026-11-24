<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['pelanggan', 'pembayaran.user'])
            ->whereHas('pembayaran')
            ->latest();

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereHas('pembayaran', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
            });
        }

        if ($request->no_nota) {
            $query->where('kode_invoice', 'like', '%'.$request->no_nota.'%');
        }

        if ($request->nama_pelanggan) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->nama_pelanggan.'%');
            });
        }

        $invoices = $query->paginate(25)->onEachSide(2);

        return view('pages.pembayaran.index', compact('invoices'));
    }

    public function detail($invoiceId)
    {
        $invoice = Invoice::with(['pelanggan', 'pembayaran.user'])->findOrFail($invoiceId);

        return response()->json([
            'invoice' => $invoice,
            'pembayaran' => $invoice->pembayaran->map(function ($p) {
                return [
                    'id' => $p->id,
                    'tanggal' => $p->tanggal->format('d M Y'),
                    'nominal' => $p->nominal,
                    'metode' => $p->metode,
                    'keterangan' => $p->keterangan,
                    'admin' => $p->user->nama_lengkap,
                ];
            }),
        ]);
    }
}
