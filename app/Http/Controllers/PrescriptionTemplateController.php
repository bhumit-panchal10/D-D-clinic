<?php

namespace App\Http\Controllers;

use App\Models\Dosage;
use App\Models\Medicine;
use App\Models\PrescriptionTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionTemplateController extends Controller
{
    public function index()
    {
        $templates = PrescriptionTemplate::withCount('items')
            ->latest()
            ->paginate(config('app.per_page', 15));

        return view('prescription_templates.index', compact('templates'));
    }

    public function create()
    {
        $medicines = Medicine::orderBy('medicine_name')->get();
        $dosages = Dosage::orderBy('dosage')->get();

        return view(
            'prescription_templates.create',
            compact('medicines', 'dosages')
        );
    }

    public function store(Request $request)
    {
        $data = $this->validateTemplate($request);

        DB::transaction(function () use ($data, $request) {
            $template = PrescriptionTemplate::create([
                'template_name' => $data['template_name'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->saveTemplateItems($template, $data);
        });

        return redirect()
            ->route('prescription-templates.index')
            ->with('success', 'Prescription template created successfully.');
    }

    public function edit(PrescriptionTemplate $template)
    {
        $template->load(['items.medicine', 'items.dosage']);

        $medicines = Medicine::orderBy('medicine_name')->get();
        $dosages = Dosage::orderBy('dosage')->get();

        return view(
            'prescription_templates.edit',
            compact('template', 'medicines', 'dosages')
        );
    }

    public function update(
        Request $request,
        PrescriptionTemplate $template
    ) {
        $data = $this->validateTemplate($request);

        DB::transaction(function () use ($data, $request, $template) {
            $template->update([
                'template_name' => $data['template_name'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $template->items()->delete();

            $this->saveTemplateItems($template, $data);
        });

        return redirect()
            ->route('prescription-templates.index')
            ->with('success', 'Prescription template updated successfully.');
    }

    public function destroy(PrescriptionTemplate $template)
    {
        $template->delete();

        return redirect()
            ->route('prescription-templates.index')
            ->with('success', 'Prescription template deleted successfully.');
    }

    /**
     * Used on the prescription create page.
     */
    public function items(PrescriptionTemplate $template)
    {
        if (!$template->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This prescription template is inactive.',
                'items' => [],
            ], 404);
        }

        $template->load([
            'items.medicine',
            'items.dosage',
        ]);

        return response()->json([
            'success' => true,
            'template_name' => $template->template_name,

            'items' => $template->items->map(function ($item) {
                return [
                    'medicine_id' => $item->medicine_id,
                    'medicine_name' => optional($item->medicine)->medicine_name,
                    'dosage_id' => $item->dosage_id,
                    'dosage_text' => optional($item->dosage)->dosage,
                    'days' => $item->days,
                    'qty' => $item->medicine_qty,
                    'comment' => $item->comments ?? '',
                    'content' =>
                    optional($item->medicine)->content ?? '',
                ];
            })->values(),
        ]);
    }

    private function validateTemplate(Request $request): array
    {
        return $request->validate([
            'template_name' => [
                'required',
                'string',
                'max:255',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'medicine_id' => [
                'required',
                'array',
                'min:1',
            ],

            'medicine_id.*' => [
                'required',
                'exists:medicines,id',
            ],

            'dosage_id' => [
                'required',
                'array',
                'min:1',
            ],

            'dosage_id.*' => [
                'required',
                'exists:dosages,id',
            ],

            'days' => [
                'nullable',
                'array',
            ],

            'days.*' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'qtys' => [
                'nullable',
                'array',
            ],

            'qtys.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'comments' => [
                'nullable',
                'array',
            ],

            'comments.*' => [
                'nullable',
                'string',
            ],
        ]);
    }

    private function saveTemplateItems(
        PrescriptionTemplate $template,
        array $data
    ): void {
        foreach ($data['medicine_id'] as $index => $medicineId) {
            $template->items()->create([
                'medicine_id' => $medicineId,
                'dosage_id' => $data['dosage_id'][$index],
                'days' => $data['days'][$index] ?? null,
                'medicine_qty' => $data['qtys'][$index] ?? null,
                'comments' => $data['comments'][$index] ?? null,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
