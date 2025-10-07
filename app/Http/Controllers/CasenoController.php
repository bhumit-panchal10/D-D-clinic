<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\ClinicCaseCounters;
use App\Models\Concerform;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CasenoController extends Controller
{

    public function index()
    {
        $CaseNos = ClinicCaseCounters::orderBy('id', 'desc')
            ->paginate(config('app.per_page', 10));
        // dd($CaseNos);
        return view('CoseNo.index', compact('CaseNos'));
    }

    public function edit(Request $request)
    {

        $ClinicCaseCounters = ClinicCaseCounters::where('id', $request->id)->first();
        return json_encode($ClinicCaseCounters);
    }

    public function update(Request $request)
    {

        $caseMaster = ClinicCaseCounters::where('id', $request->caseid)->first();

        if ($caseMaster->prefix !== $request->prefix || $caseMaster->postfix !== $request->postfix) {
            $caseMaster->last_number = 1;
        }

        $caseMaster->prefix = $request->prefix;
        $caseMaster->postfix = $request->postfix;

        $caseno =
            ($caseMaster->prefix ?? '') . '-' .
            str_pad($caseMaster->last_number, 4, '0', STR_PAD_LEFT) . '-' .
            ($caseMaster->postfix ?? '');

        $caseMaster->case_no = $caseno;
        $caseMaster->save();

        return redirect()->route('Caseno.index');
    }
}
