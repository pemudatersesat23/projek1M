<?php

namespace App\Http\Controllers;

use App\Models\PartnerCampus;
use Illuminate\Http\Request;

class PartnerCampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campuses = PartnerCampus::latest()->get();
        return view('admin.partner.index', compact('campuses'));
    }

    public function create()
    {
        return view('admin.partner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->logo->extension();
        $request->logo->move(public_path('image/partner_campuses'), $imageName);

        PartnerCampus::create([
            'name' => $request->name,
            'logo' => 'image/partner_campuses/' . $imageName,
        ]);

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil ditambahkan.');
    }

    public function edit(PartnerCampus $partnerCampus)
    {
        return view('admin.partner.edit', compact('partnerCampus'));
    }

    public function update(Request $request, PartnerCampus $partnerCampus)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            $oldImagePath = public_path($partnerCampus->logo);
            if (file_exists($oldImagePath) && \is_file($oldImagePath)) {
                @unlink($oldImagePath);
            }

            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('image/partner_campuses'), $imageName);
            $partnerCampus->logo = 'image/partner_campuses/' . $imageName;
        }

        $partnerCampus->name = $request->name;
        $partnerCampus->save();

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil diperbarui.');
    }

    public function destroy(PartnerCampus $partnerCampus)
    {
        $oldImagePath = public_path($partnerCampus->logo);
        if (file_exists($oldImagePath) && \is_file($oldImagePath)) {
            @unlink($oldImagePath);
        }

        $partnerCampus->delete();

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil dihapus.');
    }
}
