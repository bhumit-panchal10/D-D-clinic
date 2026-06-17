<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PatientTreatmentItem;
use App\Models\Treatment;
use App\Models\Notes;
use App\Models\NoteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PDF;

class NoteController extends Controller
{
    public function index($patient_id)
    {
        $Totalamount = Notes::where(['patient_id' => $patient_id])->sum('amount');
        $discount = Notes::where(['patient_id' => $patient_id])->sum('discount');
        $NetAmount = $Totalamount - $discount;
        $Paidamount = Payment::where('patient_id', $patient_id)->sum('amount');
        $patient = Patient::findOrFail($patient_id);
        $Treatment = Treatment::get();
        $notes = Notes::with(['treatment', 'images'])->where('patient_id', $patient_id)->orderBy('id', 'asc')->paginate(config('app.per_page'));
        // dd($notes);
        return view('notes.index', compact('NetAmount', 'Treatment', 'patient', 'notes', 'Totalamount', 'Paidamount', 'patient_id'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'treatment' => 'required',
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

    public function deleteImage($id)
    {
        $image = NoteImage::findOrFail($id);
        $noteId = $image->note_id;

        $this->deleteNoteImageFile($image);
        $image->delete();

        return redirect()->route('notes.images', $noteId)->with('success', 'Image deleted successfully.');
    }

    public function edit($id)
    {

        // $patient = Patient::findOrFail($Notes->patient_id);
        $Notes = Notes::findOrFail($id);
        return response()->json($Notes);
    }

    public function update(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'treatment' => 'nullable',
            'comments' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'tooth_no' => 'nullable',
        ]);
        $netamount =  $request->amount - $request->discount;

        $data = [
            'date' => $request->date,
            'amount' => $request->amount,
            'discount' => $request->discount,
            'treatment_id' => $request->treatment,
            'comments' => $request->comments,
            'tooth_no' => $request->tooth_no,
            'Net_amount' => $netamount,
            'updated_at' => now(),

        ];

        Notes::where("id", $request->id)->update($data);

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
        return $_SERVER['DOCUMENT_ROOT'] . '/uploads/notes/' . $noteId;
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
            'file_path' => 'uploads/notes/' . $note->id . '/' . $filename,
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
