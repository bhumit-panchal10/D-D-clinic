<?php

namespace App\Exports;

use App\Models\Notes;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PaymentsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithColumnFormatting
{
    protected $fromDate;
    protected $toDate;
    protected $payments;

    protected $totalAmount = 0;
    protected $paidAmount = 0;
    protected $dueAmount = 0;

    protected $serialNumber = 1;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate ?: date('Y-m-01');
        $this->toDate = $toDate ?: date('Y-m-d');
    }

    public function collection()
    {
        $this->payments = Notes::with([
            'patient',
            'treatment_detail',
            'patient.payments' => function ($query) {
                $query->whereBetween(
                    'payment_date',
                    [$this->fromDate, $this->toDate]
                );
            }
        ])
            ->whereBetween('date', [$this->fromDate, $this->toDate])
            ->orderByDesc('date')
            ->get();

        $patientIds = $this->payments
            ->pluck('patient_id')
            ->filter()
            ->unique()
            ->values();

        $patientNoteTotals = Notes::whereIn('patient_id', $patientIds)
            ->whereBetween('date', [$this->fromDate, $this->toDate])
            ->selectRaw(
                'patient_id, COALESCE(SUM(Net_amount), 0) as total_amount'
            )
            ->groupBy('patient_id')
            ->pluck('total_amount', 'patient_id');

        $patientPaymentTotals = Payment::whereIn('patient_id', $patientIds)
            ->whereBetween(
                'payment_date',
                [$this->fromDate, $this->toDate]
            )
            ->selectRaw(
                'patient_id, COALESCE(SUM(amount), 0) as paid_amount'
            )
            ->groupBy('patient_id')
            ->pluck('paid_amount', 'patient_id');

        foreach ($this->payments as $note) {
            $totalAmount = (float) (
                $patientNoteTotals[$note->patient_id] ?? 0
            );

            $paidAmount = (float) (
                $patientPaymentTotals[$note->patient_id] ?? 0
            );

            /*
             * These dynamic values must be used inside map().
             */
            $note->total_amount = $totalAmount;
            $note->paid_amount = $paidAmount;
            $note->due_amount = max(
                0,
                $totalAmount - $paidAmount
            );
        }

        /*
         * Use grouped totals to prevent duplicate totals
         * when one patient has multiple notes.
         */
        $this->totalAmount = (float) $patientNoteTotals->sum();
        $this->paidAmount = (float) $patientPaymentTotals->sum();
        $this->dueAmount = max(
            0,
            $this->totalAmount - $this->paidAmount
        );

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
            'Payment Received',
            'Balance',
        ];
    }

    public function map($note): array
    {
        $payment = optional($note->patient)
            ->payments
            ?->first();

        $patientName = collect([
            optional($note->patient)->name,
            optional($note->patient)->middle_name,
            optional($note->patient)->last_name,
        ])->filter()->implode(' ');

        return [
            $this->serialNumber++,

            optional($note->patient)->case_no ?? '-',

            $patientName,

            optional($note->treatment_detail)->treatment_name ?? '-',

            $payment && $payment->payment_date
                ? date('d-m-Y', strtotime($payment->payment_date))
                : '',

            optional($payment)->mode ?? '-',

            /*
             * Important:
             * Use total_amount, not Net_amount.
             */
            (float) ($note->total_amount ?? 0),

            (float) ($note->paid_amount ?? 0),

            (float) ($note->due_amount ?? 0),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => '#,##0.00',
            'H' => '#,##0.00',
            'I' => '#,##0.00',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getStyle('A1:I1')
                    ->getFont()
                    ->setBold(true);

                $totalRow = $this->payments->count() + 2;

                $sheet->setCellValue(
                    "F{$totalRow}",
                    'Total Amount'
                );

                $sheet->setCellValue(
                    "G{$totalRow}",
                    $this->totalAmount
                );

                $paidRow = $totalRow + 1;

                $sheet->setCellValue(
                    "F{$paidRow}",
                    'Paid Amount'
                );

                $sheet->setCellValue(
                    "G{$paidRow}",
                    $this->paidAmount
                );

                $dueRow = $paidRow + 1;

                $sheet->setCellValue(
                    "F{$dueRow}",
                    'Due Amount'
                );

                $sheet->setCellValue(
                    "G{$dueRow}",
                    $this->dueAmount
                );

                $sheet->getStyle("F{$totalRow}:G{$dueRow}")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("G{$totalRow}:G{$dueRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            },
        ];
    }
}
