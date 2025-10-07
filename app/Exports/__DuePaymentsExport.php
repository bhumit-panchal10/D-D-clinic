<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DuePaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    private $data = [];
    private $totalAmount = 0;
    private $totalNet = 0;
    private $totalPaid = 0;
    private $totalDue = 0;
    private $totalDiscount = 0;

    public function collection()
    {
        $orders = Order::with('payments')->get();
        $srNo = 1;

        foreach ($orders as $order) {
            $paidAmount = $order->payments->sum('amount');
            $dueAmount = max($order->net_amount - $paidAmount, 0);

            $this->data[] = [
                'sr_no' => $srNo++,
                'patient_name' => $order->patient->name,
                'invoice_no' => $order->invoice_no,
                'amount' => $order->amount,
                'discount' => $order->discount,
                'net_amount' => $order->net_amount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
            ];

            // Calculate totals
            $this->totalAmount += $order->amount;
            $this->totalDiscount += $order->discount;
            $this->totalNet += $order->net_amount;
            $this->totalPaid += $paidAmount;
            $this->totalDue += $dueAmount;
        }

        // Add Total Row
        $this->data[] = [
            'sr_no' => '',
            'patient_name' => 'Total',
            'invoice_no' => '',
            'amount' => $this->totalAmount,
            'discount' => $this->totalDiscount,
            'net_amount' => $this->totalNet,
            'paid_amount' => $this->totalPaid,
            'due_amount' => $this->totalDue,
        ];

        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Patient Name',
            'Invoice No',
            'Amount',
            'Discount',
            'Net Amount',
            'Paid Payment',
            'Due Payment',
        ];
    }

    public function map($row): array
    {
        return [
            $row['sr_no'],
            $row['patient_name'],
            $row['invoice_no'],
            number_format($row['amount'], 2),
            number_format($row['discount'], 2),
            number_format($row['net_amount'], 2),
            number_format($row['paid_amount'], 2),
            number_format($row['due_amount'], 2),
        ];
    }
}
