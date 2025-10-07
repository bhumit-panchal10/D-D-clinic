<?php
namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class PatientsExport implements FromQuery, WithHeadings, WithMapping, WithCustomStartCell
{
    protected $fromDate;
    protected $toDate;
    private static $serialNumber = 0; // Counter for Sr. No

    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function query()
    {
        return Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return ['Sr. No', 'Patient Name', 'Mobile', 'DOB', 'Reference By', 'Govt. Employee'];
    }

    public function map($patient): array
    {
        self::$serialNumber++; // Increment serial number

        return [
            self::$serialNumber, // Correct serial number
            $patient->name,
            $patient->mobile1,
            $patient->dob ? date('d-m-Y', strtotime($patient->dob)) : '-',
            $patient->reference_by ?? '-',
            $patient->is_government_employee == 'yes' ? 'Yes' : 'No',
        ];
    }

    public function startCell(): string
    {
        return 'A1'; // Ensures headings start from A1
    }
}
