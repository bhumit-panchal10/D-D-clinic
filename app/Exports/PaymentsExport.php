<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $fromDate, $toDate;
    protected $totalAmount = 0;

    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate ?? date('Y-m-01');
        $this->toDate = $toDate ?? date('Y-m-d');
    }

    public function collection()
    {
        $payments = Payment::with('patient')
            ->whereBetween('payment_date', [$this->fromDate, $this->toDate])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Calculate total amount
        $this->totalAmount = $payments->sum('amount');

        return $payments;
    }

    public function headings(): array
    {
        return ['Sr. No', 'Patient Name', 'Payment Date', 'Mode', 'Amount'];
    }

    public function map($payment): array
    {
        static $index = 1;
        return [
            $index++,
            optional($payment->patient)->name,
            date('d-m-Y', strtotime($payment->payment_date)),
            $payment->mode,
            number_format($payment->amount, 2)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $row = count($this->collection()) + 2; // Get the next row after the last record

                $event->sheet->setCellValue('D' . $row, 'Total Amount:'); 
                $event->sheet->setCellValue('E' . $row, number_format($this->totalAmount, 2));
            },
        ];
    }
}
