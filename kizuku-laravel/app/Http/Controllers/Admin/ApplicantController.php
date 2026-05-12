<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Program;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the applicants.
     */
    public function index(Request $request)
    {
        $query = Applicant::with(['program', 'batch']);

        // Filter by program
        if ($request->has('program_id') && $request->program_id != '') {
            $query->where('program_id', $request->program_id);
        }

        // Filter by batch
        if ($request->has('batch_id') && $request->batch_id != '') {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_seleksi', $request->status);
        }

        $applicants = $query->latest()->paginate(15)->withQueryString();
        $programs = Program::all();
        $batches = Batch::all();

        return view('admin.applicants.index', compact('applicants', 'programs', 'batches'));
    }

    /**
     * Display the specified applicant.
     */
    public function show(Applicant $applicant)
    {
        $applicant->load([
            'program',
            'batch',
            'document',
            'dynamicAnswers',
            'dynamicFiles',
        ]);
        return view('admin.applicants.show', compact('applicant'));
    }


    /**
     * Update the applicant's selection status.
     */
    public function updateStatus(Request $request, Applicant $applicant)
    {
        $request->validate([
            'status_seleksi' => 'required|in:baru,review,lolos,tidak_lolos,interview',
        ]);

        $applicant->update([
            'status_seleksi' => $request->status_seleksi
        ]);

        return redirect()->back()->with('success', 'Status seleksi berhasil diperbarui.');
    }

    /**
     * Remove the specified applicant from storage.
     */
    public function destroy(Applicant $applicant)
    {
        $applicant->delete();
        return redirect()->route('admin.applicants.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }
}
