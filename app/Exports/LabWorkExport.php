<?php

namespace App\Exports;

use App\Models\Labwork;
use App\Models\Payment;
use App\Models\PayToDr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LabWorkExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fromDate, $toDate, $labId;
    protected $totalAmount = 0;

    public function __construct($fromDate, $toDate, $labId = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->labId = $labId;
    }

    public function collection()
    {
        $query = Labwork::select(
            'labworks.*',
            'labs.lab_name',
            'patients.name',
        )
            //->where('labworks.clinic_id', auth()->user()->clinic_id)
            ->leftJoin('labs', 'labs.id', '=', 'labworks.lab_id')
            ->leftJoin('patients', 'patients.id', '=', 'labworks.patient_id')
            ->orderBy('labworks.collection_date', 'desc');

        if ($this->fromDate) {
            $query->where('labworks.collection_date', '>=', date('Y-m-d 00:00:00', strtotime($this->fromDate)));
        }

        if ($this->toDate) {
            $query->where('labworks.collection_date', '<=', date('Y-m-d 23:59:59', strtotime($this->toDate)));
        }

        if ($this->labId) {
            $query->where('labworks.lab_id', $this->labId);
        }

        $results = $query->get();
        return $results;
    }

    public function headings(): array
    {
        return [
            'Sr. No',
            'Lab Name',
            'Patient Name',
            'Comment',
            'Entry Date',
            'Pickup Date',
            'Received Date'
        ];
    }

    public function map($query): array
    {

        static $index = 1;
        return [
            $index++,
            $query->lab_name,
            $query->name,
            $query->comment ?? "-",
            date('d-m-Y', strtotime($query->entry_date)),
            $query->collection_date ? \Carbon\Carbon::parse($query->collection_date)->format('d-m-Y') : '-',
            $query->received_date ? \Carbon\Carbon::parse($query->received_date)->format('d-m-Y') : '-'
        ];
    }
}
