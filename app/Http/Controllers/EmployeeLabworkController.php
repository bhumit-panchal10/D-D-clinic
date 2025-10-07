<?php

namespace App\Http\Controllers;

use App\Models\Labwork;
use App\Models\Lab;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;
use Illuminate\Http\Request;

class EmployeeLabworkController extends Controller
{
    public function index(Request $request)
    {
        $labworks = Labwork::paginate(config('app.per_page'));
        return view('employee.labworks.full_list', compact('labworks'));
    }

    public function markCollected($id)
{
    $labwork = Labwork::findOrFail($id);
    $labwork->collection_date = now();
    $labwork->save();

    return redirect()->route('employee.labworks.full_list')
        ->with('success', 'Labwork marked as collected.');
}

public function markReceived($id)
{
    $labwork = Labwork::findOrFail($id);
    $labwork->received_date = now();
    $labwork->save();

    return redirect()->route('employee.labworks.full_list')
        ->with('success', 'Labwork marked as received.');
}
}
