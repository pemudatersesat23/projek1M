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
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'required|string|max:1000',
        ]);

        $imageName = time() . '_logo.' . $request->logo->extension();
        $request->logo->move(public_path('image/partner_campuses'), $imageName);

        $bannerName = time() . '_banner.' . $request->banner->extension();
        $request->banner->move(public_path('image/partner_campuses'), $bannerName);
        
        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate();
        $tr->setSource('id');
        $tr->setTarget('ja');
        
        $nameJp = $tr->translate($request->name);
        $descJp = $tr->translate($request->description);

        PartnerCampus::create([
            'name' => [
                'id' => $request->name,
                'jp' => $nameJp,
            ],
            'description' => [
                'id' => $request->description,
                'jp' => $descJp,
            ],
            'logo' => 'image/partner_campuses/' . $imageName,
            'banner' => 'image/partner_campuses/' . $bannerName,
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'required|string|max:1000',
        ]);

        if ($request->hasFile('logo')) {
            $oldImagePath = public_path($partnerCampus->logo);
            if (file_exists($oldImagePath) && \is_file($oldImagePath)) {
                @unlink($oldImagePath);
            }

            $imageName = time() . '_logo.' . $request->logo->extension();
            $request->logo->move(public_path('image/partner_campuses'), $imageName);
            $partnerCampus->logo = 'image/partner_campuses/' . $imageName;
        }

        if ($request->hasFile('banner')) {
            $oldBannerPath = public_path($partnerCampus->banner);
            if ($oldBannerPath && file_exists($oldBannerPath) && \is_file($oldBannerPath)) {
                @unlink($oldBannerPath);
            }

            $bannerName = time() . '_banner.' . $request->banner->extension();
            $request->banner->move(public_path('image/partner_campuses'), $bannerName);
            $partnerCampus->banner = 'image/partner_campuses/' . $bannerName;
        }

        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate();
        $tr->setSource('id');
        $tr->setTarget('ja');
        
        $nameJp = $tr->translate($request->name);
        $descJp = $tr->translate($request->description);

        $partnerCampus->name = [
            'id' => $request->name,
            'jp' => $nameJp,
        ];

        $partnerCampus->description = [
            'id' => $request->description,
            'jp' => $descJp,
        ];
        
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

        $oldBannerPath = public_path($partnerCampus->banner);
        if ($oldBannerPath && file_exists($oldBannerPath) && \is_file($oldBannerPath)) {
            @unlink($oldBannerPath);
        }

        $partnerCampus->delete();

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil dihapus.');
    }
}
