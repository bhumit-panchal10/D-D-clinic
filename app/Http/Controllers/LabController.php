<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;

class LabController extends Controller
{
    public function index()
    {
        $labs = Lab::orderBy('id', 'desc')->paginate(config('app.per_page'));
        return view('lab.index', compact('labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_name' => 'required|string|max:50|unique:labs,lab_name',
            'contact_person' => 'required|string|max:30',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:30',
        ], [
            'lab_name.unique' => 'This lab already exists.',
        ]);

        Lab::create([
            'lab_name' => $request->lab_name,
            'contact_person' => $request->contact_person,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'email' => $request->email,
        ]);

        return redirect()->route('lab.index')->with('success', 'Lab added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lab_name' => 'required|string|max:50|unique:labs,lab_name,' . $id,
            'contact_person' => 'required|string|max:30',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:30',
        ], [
            'lab_name.unique' => 'This lab already exists.',
        ]);

        $lab = Lab::findOrFail($id);
        $lab->update([
            'lab_name' => $request->lab_name,
            'contact_person' => $request->contact_person,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'email' => $request->email,
        ]);

        return redirect()->route('lab.index', ['page' => $request->query('page', 1)])->with('success', 'Lab updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);
        $lab->delete();

        return redirect()->route('lab.index', ['page' => $request->query('page', 1)])->with('success', 'Lab deleted successfully!');
    }
}
