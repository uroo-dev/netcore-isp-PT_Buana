<?php

namespace App\Exports;

use App\Models\PenanggungJawabWifi;
use App\Models\Pengaturan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TagihanExport implements FromView, WithEvents
{
    use RegistersEventListeners;

    public function __construct(
        protected Collection $invoices,
        protected int $totalKeseluruhan,
        protected ?string $tanggalMulai = null,
        protected ?string $tanggalAkhir = null,
        protected string $status = 'semua',
        protected string $tipe = 'semua',
        protected ?string $penanggungJawab = null,
    ) {
        if ($this->penanggungJawab) {
            $pj = PenanggungJawabWifi::find($this->penanggungJawab);
            $this->penanggungJawab = $pj?->nama;
        }
    }

    public function view(): View
    {
        $pengaturan = Pengaturan::first();

        return view('pages.laporan.nota_excel', [
            'invoices' => $this->invoices,
            'totalKeseluruhan' => $this->totalKeseluruhan,
            'tanggalMulai' => $this->tanggalMulai,
            'tanggalAkhir' => $this->tanggalAkhir,
            'status' => $this->status,
            'tipe' => $this->tipe,
            'pengaturan' => $pengaturan,
            'penanggungJawab' => $this->penanggungJawab,
        ]);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();
        $pengaturan = Pengaturan::first();

        if ($pengaturan && $pengaturan->logo) {
            $logoPath = storage_path('app/public/'.$pengaturan->logo);
            if (file_exists($logoPath)) {
                $drawing = new Drawing;
                $drawing->setName('Logo');
                $drawing->setDescription('Company Logo');
                $drawing->setPath($logoPath);
                $drawing->setHeight(25);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);
            }
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(20);
    }
}
