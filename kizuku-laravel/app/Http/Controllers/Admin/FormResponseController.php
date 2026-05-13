<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Form;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    /**
     * List all responses (applicants) submitted via a specific form.
     */
    public function index(Request $request, Form $form)
    {
        $form->loadMissing(['program', 'schema', 'batch']);

        $query = Applicant::where('form_id', $form->id)
            ->with([
                'program',
                'batch',
                'programSchema',
                'dynamicAnswers.formField',
                'dynamicFiles.formField',
            ]);

        // --- Optional search ---
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama',  'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        // --- Optional status filter ---
        if ($request->filled('status')) {
            $query->where('status_seleksi', $request->status);
        }

        $responses = $query->latest()->paginate(20)->withQueryString();

        $totalResponses = Applicant::where('form_id', $form->id)->count();

        return view('admin.forms.responses.index', compact('form', 'responses', 'totalResponses'));
    }

    /**
     * Show a single response detail.
     * Ensures the applicant belongs to the given form (403 otherwise).
     */
    public function show(Form $form, Applicant $applicant)
    {
        // Security: applicant must belong to this form
        if ((int) $applicant->form_id !== (int) $form->id) {
            abort(403, 'Response ini bukan bagian dari formulir yang dimaksud.');
        }

        $applicant->loadMissing([
            'program',
            'batch',
            'programSchema',
            'dynamicAnswers.formField',
            'dynamicFiles.formField',
            'form',
        ]);

        return view('admin.forms.responses.show', compact('form', 'applicant'));
    }
}
