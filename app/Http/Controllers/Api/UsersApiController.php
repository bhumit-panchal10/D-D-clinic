<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\IpAddress;
use Illuminate\Support\Facades\Auth;

class UsersApiController extends Controller
{
    private $web_url;
    public function __construct()
    {
        $this->web_url =  "https://nck2187.com/finance/api/";
    }

    public function product(Request $request)
    {
        //$session = Auth::user()->id;
        $Data = json_encode($request->all());
        //dd($Data);

        $InsertedID = DB::table('logtable')->insertGetId(["request" => $Data]);

        $check = Product::orderBy('productId', 'desc')
            ->where(['serial_no' => $request->serialNumber])
            ->first();
        
        $GetIp=$_SERVER['REMOTE_ADDR'];
        // dd($GetIp);
        
        $ipaddress = IpAddress::where(['ipaddress' => $GetIp])->count();
        $responsedata = [];
        
        if($ipaddress){
            if (!empty($check)) {
                if (
                    $check->serial_no == $request->serialNumber && $check->model_code == $request->materialCode
                    && $check->status == 0 
                    
                ) 
                // 15/12/2023 as per discus with client
                //&& $check->dealer_code == $request->dealerCode
                {
                    $update = DB::table('product')
                        ->where(['status' => 0, 'serial_no' => $request->serialNumber])
                        ->update([
                            'status' => 1,
                            'financedate' => date('Y-m-d H:i:s'),
                            'validateBy' => 1,
                        ]);
    
                    $responsedata = [
                        "responseStatus" => "0",
                        "responseMessage" => "Valid Serial Number"
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);
    
                    return [
                        "responseStatus" => "0",
                        "responseMessage" => "Valid Serial Number"
                    ];
                } else if (
                    $check->serial_no == $request->serialNumber && $check->model_code == $request->materialCode
                    && $check->status == 1 
                )
                // && $check->dealer_code == $request->dealerCode
                {
                    $responsedata = [
                        "responseStatus" => "-3",
                        "responseMessage" => "Serial Number Already Validated"
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);
    
                    return [
                        "responseStatus" => "-3",
                        "responseMessage" => "Serial Number Already Validated"
                    ];
                } else if (
                    $check->serial_no == $request->serialNumber && $check->model_code != $request->materialCode
                ) {
                    $responsedata = [
                        "responseStatus" => "-4",
                        "responseMessage" => "Invalid Material code"
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);
                    return [
                        "responseStatus" => "-4",
                        "responseMessage" => "Invalid Material code"
                    ];
                } else if (
                    $check->serial_no == $request->serialNumber 
                )
                // && $check->dealer_code != $request->dealerCode
                {
                    $responsedata = [
                        "responseStatus" => "-5",
                        "responseMessage" => "Serial Number not billed to this dealer"
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);
                    return [
                        "responseStatus" => "-5",
                        "responseMessage" => "Serial Number not billed to this dealer"
                    ];
                }
            } else {
                $check = Product::orderBy('productId', 'desc')
                    ->where(['model_code' => $request->materialCode])
                    ->first();
                if (empty($check)) {
                    $responsedata = [
                        "responseStatus" => "-1",
                        "responseMessage" => "Invalid Serial Number"
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);
                    return [
                        "responseStatus" => "-1",
                        "responseMessage" => "Invalid Serial Number"
                    ];
                } else {
                    $responsedata = [
                        "responseStatus" => "-2",
                        "responseMessage" => "Mismatch in model and serial number"
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);
                    return [
                        "responseStatus" => "-2",
                        "responseMessage" => "Mismatch in model and serial number"
                    ];
                }
            }
        }else{
                $responsedata = [
                        "responseStatus" => "-6",
                        "responseMessage" => "Invalid Ip Address" .'('. $GetIp .')'
                    ];
                    $Data = json_encode($responsedata);
    
                    $LogTable = DB::table('logtable')
                        ->where(['id' => $InsertedID])
                        ->update([
                            'response' => $Data,
                            'created_at' => date('Y-m-d H:i:s'),
                            'strIP' => $request->ip()
                        ]);    
                
             return [
                    "responseStatus" => "-6",
                    "responseMessage" => "Invalid Ip Address" .'('. $GetIp .')'
                ];
        }
    }
}
