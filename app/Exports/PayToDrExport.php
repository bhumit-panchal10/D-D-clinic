<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\PayToDr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PayToDrExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $fromDate, $toDate, $doctorId;
    protected $totalAmount = 0;

    public function __construct($fromDate, $toDate, $doctorId)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->doctorId = $doctorId;
    }

    public function collection()
    {
        $query = PayToDr::select(
            'pay_to_drs.*',
            'patients.name',
            'doctors.doctor_name'
        )
            //->where('pay_to_drs.clinic_id', auth()->user()->clinic_id)
            ->leftJoin('doctors', 'doctors.id', '=', 'pay_to_drs.doctor_id')
            ->leftJoin('patients', 'patients.id', '=', 'pay_to_drs.patient_id')
            ->orderBy('pay_to_drs.created_at', 'desc');

        // Apply date filter only if present
        $query->when(
            $this->fromDate,
            fn($query, $FromDate) =>
            $query->where('pay_to_drs.created_at', '>=', date('Y-m-d 00:00:00', strtotime($FromDate)))
        );

        $query->when(
            $this->toDate,
            fn($query, $ToDate) =>
            $query->where('pay_to_drs.created_at', '<=', date('Y-m-d 23:59:59', strtotime($ToDate)))
        );

        $query->when(
            $this->doctorId,
            fn($query, $doctorId) =>
            $query->where('pay_to_drs.doctor_id', '=', $doctorId)
        );

        $results = $query->get();
        // Calculate total amount
        $this->totalAmount = $query->sum('amount');

        return $results;
    }

    public function headings(): array
    {
        return ['Sr. No', 'Patient Name', 'Doctor Name', 'Date', 'Amount', 'Mode'];
    }

    public function map($query): array
    {

        static $index = 1;
        return [
            $index++,
            $query->name,
            $query->doctor_name,
            date('d-m-Y', strtotime($query->created_at)),
            $query->amount,
            $query->mode == 0 ? "Cash" : "Online"
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
