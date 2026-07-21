<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $fromDate, $toDate, $page;
    protected $payments;

    protected $totalAmount = 0;
    protected $paidAmount = 0;
    protected $dueAmount = 0;

    public function __construct($fromDate, $toDate, $page = 1)
    {
        $this->fromDate = $fromDate ?? date('Y-m-01');
        $this->toDate   = $toDate ?? date('Y-m-d');
        $this->page     = $page;
    }

    public function collection()
    {
        // Same query as listing
        $this->payments = Payment::with([
            'patient',
            'notes.treatment_detail'
        ])
            ->whereBetween('payment_date', [$this->fromDate, $this->toDate])
            ->orderBy('payment_date', 'desc')
            ->paginate(config('app.per_page'), ['*'], 'page', $this->page);

        // Total Amount (same as listing)
        $this->totalAmount = $this->payments->getCollection()
            ->pluck('notes')
            ->flatten()
            ->sum('Net_amount');

        // Paid Amount (same as listing)
        $this->paidAmount = Payment::whereBetween(
            'payment_date',
            [$this->fromDate, $this->toDate]
        )->sum('amount');

        // Due Amount
        $this->dueAmount = $this->totalAmount - $this->paidAmount;

        return $this->payments->getCollection();
    }

    public function headings(): array
    {
        return [
            'Sr. No',
            'Case No',
            'Patient Name',
            'Treatment',
            'Payment Date',
            'Mode',
            'Amount',
        ];
    }

    public function map($payment): array
    {
        static $sr = 1;

        $latestNote = $payment->notes->sortByDesc('date')->first();

        return [
            $sr++,
            optional($payment->patient)->case_no,
            optional($payment->patient)->name,

            $payment->notes
                ->pluck('treatment_detail.treatment_name')
                ->filter()
                ->implode("\n"),

            $latestNote ? date('d-m-Y', strtotime($latestNote->date)) : '-',

            $payment->mode,

            number_format($payment->notes->sum('Net_amount'), 2),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $row = $this->payments->count() + 2;

                $event->sheet->setCellValue('F' . $row, 'Total Amount');
                $event->sheet->setCellValue('G' . $row, number_format($this->totalAmount, 2));

                $row++;

                $event->sheet->setCellValue('F' . $row, 'Paid Amount');
                $event->sheet->setCellValue('G' . $row, number_format($this->paidAmount, 2));

                $row++;

                $event->sheet->setCellValue('F' . $row, 'Due Amount');
                $event->sheet->setCellValue('G' . $row, number_format($this->dueAmount, 2));
            },
        ];
    }
}
