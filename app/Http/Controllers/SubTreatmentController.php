<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTreatment;
use App\Models\Treatment;

class SubTreatmentController extends Controller
{

    public function getByTreatment($treatment_id)
    {
        $subTreatments = SubTreatment::where('treatment_id', $treatment_id)->get();

        return response()->json($subTreatments);
    }
    public function index(Request $request)
    {
        $treatments = Treatment::get();

        $subtreatments = SubTreatment::orderBy('sub_treatment_id', 'desc')->paginate(config('app.per_page'));

        $editsubTreatment = null;

        if ($request->has('edit')) {
            $editsubTreatment = SubTreatment::find($request->edit);
        }

        return view('subtreatments.index', compact('subtreatments', 'editsubTreatment', 'treatments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sub_treatment,name',
            'treatment_id' => 'required',

        ], [
            'name.unique' => 'This Sub Treatment already exists.',
        ]);


        SubTreatment::create($request->all());

        return redirect()->route('subtreatment.index')->with('success', 'Sub Treatment added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sub_treatment,name,' . $id . ',sub_treatment_id',
            'treatment_id' => 'required|exists:treatments,id',
        ]);

        SubTreatment::where('sub_treatment_id', $id)
            ->update([
                'name'         => $request->input('name'),
                'treatment_id' => $request->input('treatment_id'),
            ]);

        return redirect()->route('subtreatment.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Sub Treatment updated successfully!');
    }


    public function destroy(Request $request, $id)
    {

        $SubTreatment = SubTreatment::where('sub_treatment_id', $id);
        $SubTreatment->delete();


        return redirect()->route('subtreatment.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Sub Treatment deleted successfully!');
    }
}
