<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosis;

class DiagnosisController extends Controller
{

    public function getByType($type)
    {

        $diagnosis = Diagnosis::where('type', $type)
            ->orderBy('Diagnosis_name', 'asc')
            ->get(['id', 'Diagnosis_name']); // only required fields

        return response()->json($diagnosis);
    }
    public function index(Request $request)
    {
        $Diagnosis = Diagnosis::orderBy('id', 'desc')->paginate(config('app.per_page'));

        $editdiagnosis = null;

        if ($request->has('edit')) {
            $editdiagnosis = Diagnosis::find($request->edit);
        }

        return view('Diagnosis.index', compact('Diagnosis', 'editdiagnosis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Diagnosis_name' => 'required|string|max:255|unique:Diagnosis,Diagnosis_name',
            'type' => 'required',

        ], [
            'Diagnosis_name.unique' => 'This Diagnosis already exists.',
        ]);


        Diagnosis::create($request->all());

        return redirect()->route('Diagnosis.index')->with('success', 'Diagnosis added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Diagnosis_name' => 'required|string|max:255|unique:Diagnosis,Diagnosis_name,' . $id,
            'type' => 'required',

        ], [
            'Diagnosis_name.unique' => 'This Diagnosis already exists.',
        ]);

        $Diagnosis = Diagnosis::findOrFail($id);
        $Diagnosis->update($request->all());

        return redirect()->route('Diagnosis.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Diagnosis updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $Diagnosis = Diagnosis::findOrFail($id);
        $Diagnosis->delete();


        return redirect()->route('Diagnosis.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Diagnosis deleted successfully!');
    }
}
