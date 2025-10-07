<?php

namespace App\Http\Controllers;

use App\Models\ConcernForm;
use App\Models\Patient;
use App\Models\PatientConcerform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConcernFormMasterController extends Controller
{

    public function index()
    {
        $Concerforms = ConcernForm::orderBy('id', 'desc')->paginate(config('app.per_page'));

        return view('concern_form_master.index', compact('Concerforms'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            ConcernForm::create([
                'title'    => $request->title,
                'description' => $request->text,
                'strIP' => $request->ip(),
                'clinic_id' => auth()->user()->clinic_id,

            ]);
            DB::commit();
            return redirect()->route('concern_form_master.index')->with('success', 'Concern Form Added Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $Concerform = ConcernForm::where('id', $id)->first();

        return json_encode($Concerform);
    }


    public function update(Request $request)
    {

        DB::beginTransaction();

        try {

            ConcernForm::where('id', $request->id)->update([
                'title'    => $request->title,
                'description'  => $request->description,
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('concern_form_master.index')->with('success', 'Concern Form Updated Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function delete(Request $request)
    {
        try {
            ConcernForm::where('id', $request->id)->delete();

            return back()->with(['success', 'Concern Form Deleted Successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
