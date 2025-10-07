<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
{
    $vendors = Vendor::orderBy('created_at', 'desc')->paginate(config('app.per_page')); // Show latest first
    return view('vendor.index', compact('vendors'));
}


    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name'          => 'required|string|min:3|max:100',
            'contact_person_name'   => 'required|string|min:3|max:100',
            'mobile'                => 'required|digits:10|unique:vendors,mobile',
            'email'                 => 'nullable|email|max:100|unique:vendors,email',
            'address'               => 'nullable|string|max:255',
        ],[
            'mobile.unique' => 'This mobile number is already in use.',
            'email.unique'  => 'This email ID is already in use.',
        ]);

        Vendor::create($request->all());

        return redirect()->route('vendor.index')->with('success', 'Vendor added successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        $currentPage = request()->input('page', 1); // Get the current page number

        return view('vendor.edit', compact('vendor', 'currentPage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name'          => 'required|string|min:3|max:100',
            'contact_person_name'   => 'required|string|min:3|max:100',
            'mobile'                => 'required|digits:10|unique:vendors,mobile,' . $id,
            'email'                 => 'nullable|email|max:100|unique:vendors,email,' . $id,
            'address'               => 'nullable|string|max:255',
        ], [
            'mobile.unique' => 'This mobile number is already in use.',
            'email.unique'  => 'This email ID is already in use.',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->all());

        // Get the current page number
        $currentPage = request()->input('page', 1);

        return redirect()->route('vendor.index', ['page' => $currentPage])->with('success', 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
