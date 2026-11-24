<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class NotifyDueInvoices extends Command
{
    protected $signature = 'invoice:notify-due';

    protected $description = 'Send WhatsApp notification for invoices approaching due date (H-7 and H-3)';

    public function handle()
    {
        $this->info('Checking for invoices approaching due date...');

        $today = now()->startOfDay();

        $invoices = Invoice::with(['pelanggan', 'user'])
            ->where('status', 'cicilan')
            ->whereNotNull('jatuh_tempo')
            ->get();

        $sent = 0;

        foreach ($invoices as $invoice) {
            $jatuhTempo = Carbon::parse($invoice->jatuh_tempo)->startOfDay();
            $daysUntil = $today->diffInDays($jatuhTempo, false);

            if ($daysUntil == 7 || $daysUntil == 3) {
                $user = $invoice->user;
                $pelanggan = $invoice->pelanggan;

                if ($user && $user->telepon) {
                    $message = $this->formatMessage($invoice, $daysUntil, $pelanggan);
                    $this->sendWhatsApp($user->telepon, $message);
                    $sent++;
                    $this->info('Notification sent for invoice '.$invoice->kode_invoice.' to '.$user->telepon);
                }
            }
        }

        $this->info('Total notifications sent: '.$sent);
    }

    private function formatMessage($invoice, $daysUntil, $pelanggan)
    {
        $tanggalJatuhTempo = Carbon::parse($invoice->jatuh_tempo)->format('d M Y');
        $sisa = number_format($invoice->sisa, 0, ',', '.');

        return "Pengingat Tagihan\n\n".
               'Invoice: '.$invoice->kode_invoice."\n".
               'Pelanggan: '.$pelanggan->nama."\n".
               'Jatuh Tempo: '.$tanggalJatuhTempo.' (H-'.$daysUntil.")\n".
               'Sisa Tagihan: Rp '.$sisa."\n\n".
               'Segera hubungi pelanggan untuk melakukan pembayaran.';
    }

    private function sendWhatsApp($phone, $message)
    {
        $apiKey = env('WA_API_KEY');
        $apiUrl = env('WA_API_URL');

        if (! $apiKey || ! $apiUrl) {
            $this->warn('WhatsApp API not configured. Message not sent.');

            return;
        }

        try {
            $response = Http::post($apiUrl, [
                'api_key' => $apiKey,
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $this->info('WhatsApp sent to '.$phone);
            } else {
                $this->error('Failed to send WhatsApp to '.$phone);
            }
        } catch (\Exception $e) {
            $this->error('Error sending WhatsApp: '.$e->getMessage());
        }
    }
}
