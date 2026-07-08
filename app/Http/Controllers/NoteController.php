<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PatientTreatmentItem;
use App\Models\SubTreatment;
use App\Models\Treatment;
use App\Models\Notes;
use App\Models\NoteImage;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PDF;

class NoteController extends Controller
{
    public function index(Request $request, $patient_id)
    {
        $Totalamount = Notes::where(['patient_id' => $patient_id])->sum('amount');
        $discount = Notes::where(['patient_id' => $patient_id])->sum('discount');
        $NetAmount = $Totalamount - $discount;
        $Paidamount = Payment::where('patient_id', $patient_id)->sum('amount');
        $patient = Patient::findOrFail($patient_id);
        $Treatment = Treatment::get();
        $SubTreatments = SubTreatment::get();
        $treatmentPlans = TreatmentPlan::with('details')->where('patient_id', $patient_id)
            ->orderBy('date', 'desc')
            ->get();
        //dd($treatmentPlans);

        $notes = Notes::with(['treatment', 'subTreatment', 'images'])
            ->where('patient_id', $patient_id);

        if ($request->filled('tooth_no')) {
            $toothValues = array_filter(array_map('trim', explode(',', $request->tooth_no)));
            $notes->where(function ($query) use ($toothValues) {
                foreach ($toothValues as $tooth) {
                    $query->orWhere('tooth_no', 'like', '%' . $tooth . '%');
                }
            });
        }
        $notes = $notes->orderBy('date', 'desc')->paginate(config('app.per_page'));
        // dd($notes);
        return view('notes.index', compact('NetAmount', 'Treatment', 'patient', 'notes', 'Totalamount', 'Paidamount', 'patient_id', 'treatmentPlans'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'treatment' => 'required',
            'sub_treatment' => 'nullable|array',
            'sub_treatment.*' => 'nullable|exists:sub_treatment,sub_treatment_id',
            'next_appointment_date' => 'nullable',
            'comments' => 'nullable|string',
            'tooth_no' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120'
        ]);
        $netamount =  $request->amount - $request->discount;

        $note = Notes::create([
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'treatment_id' => $request->treatment,
            'sub_treatment_id' => $request->has('sub_treatment') ? implode(',', $request->sub_treatment) : null,
            'next_appointment_date' => $request->next_appointment_date,
            'comments' => $request->comments,
            'tooth_no' => $request->tooth_no,
            'discount' => $request->discount,
            'Net_amount' => $netamount
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->saveNoteImage($note, $image);
            }
        }

        return redirect()->back()->with('success', 'Notes added successfully.');
    }

    public function images($id)
    {
        $note = Notes::with(['treatment', 'images'])->findOrFail($id);
        return view('notes.images', compact('note'));
    }

    public function deleteImage(Request $request, $id)
    {
        $image = NoteImage::findOrFail($id);
        $noteId = $image->note_id;

        $this->deleteNoteImageFile($image);
        $image->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('notes.images', $noteId)->with('success', 'Image deleted successfully.');
    }

    public function edit($id)
    {
        $Notes = Notes::with('images')->findOrFail($id);
        // convert comma-separated sub_treatment_id into array for the frontend
        $Notes->sub_treatment_id = $Notes->sub_treatment_id ? explode(',', $Notes->sub_treatment_id) : [];
        return response()->json($Notes);
    }

    public function update(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'treatment' => 'nullable',
            'sub_treatment' => 'nullable|array',
            'sub_treatment.*' => 'nullable|exists:sub_treatment,sub_treatment_id',
            'next_appointment_date' => 'nullable',
            'comments' => 'nullable|string',
            'amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tooth_no' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);
        $netamount =  $request->amount - ($request->discount ?? 0);

        $data = [
            'date' => $request->date,
            'amount' => $request->amount,
            'discount' => $request->discount,
            'treatment_id' => $request->treatment,
            'sub_treatment_id' => $request->has('sub_treatment') ? implode(',', $request->sub_treatment) : null,
            'next_appointment_date' => $request->next_appointment_date,
            'comments' => $request->comments,
            'tooth_no' => $request->tooth_no,
            'Net_amount' => $netamount,
            'updated_at' => now(),

        ];

        $note = Notes::findOrFail($request->id);
        $note->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $this->saveNoteImage($note, $image);
            }
        }

        return redirect()->route('notes.index', $request->patient_id)->with('success', 'Notes updated successfully.');
    }

    public function destroy($id)
    {
        $note = Notes::with('images')->findOrFail($id);

        foreach ($note->images as $image) {
            $this->deleteNoteImageFile($image);
            $image->delete();
        }

        $note->delete();

        return redirect()->back()->with('success', 'Notes deleted successfully.');
    }

    private function galleryUploadPath($noteId)
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/dental_clinic/uploads/notes/' . $noteId;
    }

    private function saveNoteImage(Notes $note, $image)
    {
        $path = $this->galleryUploadPath($note->id);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($path, $filename);

        return NoteImage::create([
            'note_id' => $note->id,
            'filename' => $filename,
            'file_path' => '/uploads/notes/' . $note->id . '/' . $filename,
        ]);
    }

    private function deleteNoteImageFile(NoteImage $image)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $image->file_path;

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    public function generateInvoice($id)
    {
        $payments = Payment::with('patient')->where('id', $id)->first();

        $pdf = PDF::loadView('payments.invoice', compact('payments'));

        return $pdf->stream("invoice_{$payments->id}.pdf"); // Opens in new tab
    }
}
