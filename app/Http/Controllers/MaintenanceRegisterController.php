<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRegister;
use Carbon\Carbon;

class MaintenanceRegisterController extends Controller
{
    // Display all maintenance records
    public function index()
    {
        $maintenances = MaintenanceRegister::orderBy('created_at', 'desc')->paginate(config('app.per_page'));
        return view('maintenance.index', compact('maintenances'));
    }

    // Show create form
    public function create()
    {
        return view('maintenance.create');
    }

    // Store new maintenance record
    public function store(Request $request)
    {
        $request->validate([
            'item_name'             => 'required|string|max:50',
            'complain_details'      => 'required|string|max:255',
            'repair_person_name'    => 'required|string|max:50',
            'repair_given_date'     => 'required|date',
            'quotation_amount'      => 'required|numeric|min:0',
            'payment_paid_amount'   => 'required|numeric|min:0',
        ]);

        MaintenanceRegister::create($request->all());

        return redirect()->route('maintenance.index')->with('success', 'Maintenance record added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $maintenance = MaintenanceRegister::findOrFail($id);
        return view('maintenance.edit', compact('maintenance'));
    }

    // Update maintenance record
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name'             => 'required|string|max:50',
            'complain_details'      => 'required|string|max:255',
            'repair_person_name'    => 'required|string|max:50',
            'repair_given_date'     => 'required|date',
            'quotation_amount'      => 'required|numeric|min:0',
            'payment_paid_amount'   => 'required|numeric|min:0',
        ]);

        $maintenance = MaintenanceRegister::findOrFail($id);
        $maintenance->update($request->all());

        return redirect()->route('maintenance.index')->with('success', 'Maintenance record updated successfully.');
    }

    // Delete maintenance record
    public function destroy($id)
    {
        MaintenanceRegister::findOrFail($id)->delete();
        return redirect()->route('maintenance.index')->with('success', 'Maintenance record deleted successfully.');
    }

    // Mark item as received with current date
    public function markAsReceived(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:maintenance_registers,id',
            'pay_due_amount' => 'required|numeric|min:0',
            'received_comment' => 'nullable|string|max:255',
        ]);

        $maintenance = MaintenanceRegister::findOrFail($request->id);

        $maintenance->payment_paid_amount += $request->pay_due_amount;
        $maintenance->repair_received_date = now();
        $maintenance->received_comment = $request->received_comment;

        $maintenance->save();

        return redirect()->back()->with('success', 'Item marked as received successfully.');
    }
}
