<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function rules(): array
    {
        return [
            'model_code' => 'required',
            'serial_no' => 'required|unique:product,serial_no',
            'dealer_code' => 'required',
            'invoice_no' => 'required',
            'status' => 'required',

        ];
    }

    public function collection(Collection $rows)
    {
        //dd($rows['model_code']);
        $session = Auth::user()->id;

    foreach ($rows as $row) {
        
        if($row['status'] == 1){
            $user = array(
                "model_code" => $row['model_code'],
                "serial_no" => $row['serial_no'],
                "dealer_code" => $row['dealer_code'],
                "invoice_no" => $row['invoice_no'],
                "location" => $row['location'],
                "status" => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'financedate' => date('Y-m-d H:i:s'),
            );
        }else{
            $user = array(
                "model_code" => $row['model_code'],
                "serial_no" => $row['serial_no'],
                "dealer_code" => $row['dealer_code'],
                "invoice_no" => $row['invoice_no'],
                "location" => $row['location'],
                "status" => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
            );
        }    
            
            
         DB::table('product')->insertGetId($user);
    }

        return $user;
    }
}
