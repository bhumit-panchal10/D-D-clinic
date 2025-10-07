<?php

namespace App\Imports;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToCollection, WithHeadingRow
{
    protected $invalidData = [];

    public function collection(Collection $rows)
    {
        $session = Auth::user()->id;
        $validatedData = [];


        foreach ($rows as $index => $row) {

            $validator = Validator::make($row->toArray(), [
                'model_code' => 'required',
                'serial_no' => 'required|unique:product,serial_no',
                'dealer_code' => 'required',
                'invoice_no' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $invalidData[] = array_merge(['row_index' => $index + 2], $row->toArray(), ['errors' => $errors]);
            } else {
                $user = [
                    "model_code" => $row['model_code'],
                    "serial_no" => $row['serial_no'],
                    "dealer_code" => $row['dealer_code'],
                    "invoice_no" => $row['invoice_no'],
                    "location" => $row['location'],
                    "status" => $row['status'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $validatedData[] = $user;
            }
        }

        // if (!empty($validatedData)) {
        //     foreach ($validatedData as $data) {
        //         if (!DB::table('product')->where('serial_no', $data['serial_no'])->exists()) {
        //             DB::table('product')->insert($data);
        //         } else {
        //             // Handle or log the fact that the record is skipped
        //             Log::info('Skipped record with duplicate serial_no: ' . $data['serial_no']);
        //         }
        //     }
        // }

        //Log or handle invalid data/errors
        if (!empty($invalidData)) {
            foreach ($validatedData as $data) {
                if (!DB::table('product')->where('serial_no', $data['serial_no'])->exists()) {
                    DB::table('product')->insert($data);
                } else {
                    Log::error('Invalid data during import:', $invalidData);
                    session(['invalidData' => $invalidData]);
                }
            }
        }

        // Log or handle invalid data/errors
        // if (!empty($invalidData)) {
        //     Log::error('Invalid data during import:', $invalidData);
        // }
        // Store all errors in the session
        if (!empty($invalidData)) {
            session(['invalidData' => $invalidData]);
        }

        return [
            'validatedData' => $validatedData,
            'invalidData' => $invalidData,
        ];
    }

    public static function getInvalidData()
    {
        // return $this->invalidData;
        return session('invalidData', []);
    }

    // public function rules(): array
    // {
    //     return [
    //         'model_code' => 'required',
    //         'serial_no' => 'required|unique:product,serial_no',
    //         'dealer_code' => 'required',
    //         'invoice_no' => 'required',
    //         'status' => 'required',

    //     ];
    // }

    // public function collection(Collection $rows)
    // {
    //     //dd($rows['model_code']);
    //     $session = Auth::user()->id;

    //     foreach ($rows as $row) {

    //         if ($row['status'] == 1) {
    //             $user = array(
    //                 "model_code" => $row['model_code'],
    //                 "serial_no" => $row['serial_no'],
    //                 "dealer_code" => $row['dealer_code'],
    //                 "invoice_no" => $row['invoice_no'],
    //                 "location" => $row['location'],
    //                 "status" => $row['status'],
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'financedate' => date('Y-m-d H:i:s'),
    //             );
    //         } else {
    //             $user = array(
    //                 "model_code" => $row['model_code'],
    //                 "serial_no" => $row['serial_no'],
    //                 "dealer_code" => $row['dealer_code'],
    //                 "invoice_no" => $row['invoice_no'],
    //                 "location" => $row['location'],
    //                 "status" => $row['status'],
    //                 'created_at' => date('Y-m-d H:i:s'),
    //             );
    //         }


    //         DB::table('product')->insertGetId($user);
    //     }

    //     return $user;
    // }
}
