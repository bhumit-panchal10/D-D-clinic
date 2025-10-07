<?php

namespace App\Http\Controllers;

use App\Models\Dosage;
use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    public function index()
    {
        $dosages = Dosage::get();
        $medicines = Medicine::select(
            'medicines.*',
            'dosages.dosage'
        )
            ->join('dosages', 'dosages.id', '=', 'medicines.dosage_id')
            ->paginate(config('app.per_page'));

        return view('medicines.index', compact('medicines', 'dosages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255|unique:medicines,medicine_name',
            'dosage_id' => 'required|exists:dosages,id',
            'comment' => 'nullable',
        ], [
            'medicine_name.unique' => 'This medicine already exists.',
            'medicine_name.required' => 'The medicine name is required.',
            'dosage_id.exists' => 'The selected dosage is invalid.',
            'dosage_id.required' => 'The dosage is required.',
        ]);

        Medicine::create($request->all());

        return redirect()->back()->with('success', 'Medicine added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255|unique:medicines,medicine_name,' . $id,
            'dosage_id' => 'required|exists:dosages,id',
            'comment' => 'nullable',
        ], [
            'medicine_name.unique' => 'This medicine already exists.',
            'medicine_name.required' => 'The medicine name is required.',
            'dosage_id.exists' => 'The selected dosage is invalid.',
            'dosage_id.required' => 'The dosage is required.',
        ]);

        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->all());

        return redirect()->back()->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->back()->with('success', 'Medicine deleted successfully!');
    }
}
