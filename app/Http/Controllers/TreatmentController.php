<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $treatments = Treatment::orderBy('id', 'desc')->paginate(config('app.per_page'));

        $editTreatment = null;

        if ($request->has('edit')) {
            $editTreatment = Treatment::find($request->edit);
        }

        return view('treatments.index', compact('treatments', 'editTreatment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'treatment_name' => 'required|string|max:255|unique:treatments,treatment_name',
            'type' => 'required',
            'lab_work' => 'required|in:yes,no',
        ], [
            'treatment_name.unique' => 'This treatment already exists.',
        ]);

        Treatment::create($request->all());

        return redirect()->route('treatment.index')->with('success', 'Treatment added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'treatment_name' => 'required|string|max:255|unique:treatments,treatment_name,' . $id,
            'type' => 'required',
            'lab_work' => 'required|in:yes,no',
        ], [
            'treatment_name.unique' => 'This treatment already exists.',
        ]);

        $treatment = Treatment::findOrFail($id);
        $treatment->update($request->all());

        return redirect()->route('treatment.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Treatment updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();


        return redirect()->route('treatment.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Treatment deleted successfully!');
    }
}
