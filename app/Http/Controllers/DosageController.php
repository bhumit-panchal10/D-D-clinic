<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosage;

class DosageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dosages = Dosage::latest()->paginate(config('app.per_page'));

        // Check if we are editing a dosage
        $editDosage = null;
        if ($request->has('edit')) {
            $editDosage = Dosage::findOrFail($request->edit);
        }

        return view('dosages.index', compact('dosages', 'editDosage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dosage' => 'required|string|max:255|unique:dosages,dosage',
        ],[
            'dosage.unique' => 'This dosage input already exists.',
        ]);

        Dosage::create([
            'dosage' => $request->dosage,
        ]);

        return redirect()->route('dosage.index')->with('success', 'Dosage added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dosage' => 'required|string|max:255|unique:dosages,dosage,' . $id,
        ],[
            'dosage.unique' => 'This dosage input already exists.',
        ]);

        $dosage = Dosage::findOrFail($id);
        $dosage->update([
            'dosage' => $request->dosage,
        ]);

        return redirect()->route('dosage.index', ['page' => $request->query('page', 1)])->with('success', 'Dosage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $dosage = Dosage::findOrFail($id);
        $dosage->delete();

        return redirect()->route('dosage.index', ['page' => $request->query('page', 1)])->with('success', 'Dosage deleted successfully.');
    }
}
