<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Notes;

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

    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate ?? date('Y-m-01');
        $this->toDate   = $toDate ?? date('Y-m-d');
    }

    public function collection()
    {
        $this->payments = Notes::with([
            'patient',
            'treatment_detail',
            'patient.payments' => function ($q) {
                $q->whereBetween('payment_date', [$this->fromDate, $this->toDate]);
            }
        ])
            ->whereBetween('date', [$this->fromDate, $this->toDate])
            ->orderBy('date', 'desc')
            ->get();

        foreach ($this->payments as $note) {
            $paid = $note->patient->payments->sum('amount');

            $note->paid_amount = $paid;
            $note->due_amount = $note->Net_amount - $paid;
        }

        // Footer Totals
        $this->totalAmount = $this->payments->sum('Net_amount');

        $this->paidAmount = Payment::whereBetween(
            'payment_date',
            [$this->fromDate, $this->toDate]
        )->sum('amount');

        $this->dueAmount = $this->totalAmount - $this->paidAmount;

        return $this->payments;
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

    public function map($note): array
    {
        static $sr = 1;

        return [
            $sr++,
            optional($note->patient)->case_no,
            optional($note->patient)->name,
            optional($note->treatment_detail)->treatment_name,
            date('d-m-Y', strtotime($note->date)),
            number_format($note->Net_amount, 2),
            number_format($note->paid_amount, 2),
            number_format($note->due_amount, 2),
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
