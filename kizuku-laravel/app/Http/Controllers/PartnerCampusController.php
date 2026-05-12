<?php

namespace App\Http\Controllers;

use App\Models\PartnerCampus;
use App\Http\Requests\PartnerCampusRequest;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

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

    public function store(PartnerCampusRequest $request)
    {
        $validated = $request->validated();

        $logoPath   = $request->file('logo')->store('partner_campuses/logos', 'public');
        $bannerPath = $request->file('banner')->store('partner_campuses/banners', 'public');

        [$nameJp, $descJp] = $this->translateContent($validated['name'], $validated['description']);

        PartnerCampus::create([
            'name' => [
                'id' => $validated['name'],
                'jp' => $nameJp,
            ],
            'description' => [
                'id' => $validated['description'],
                'jp' => $descJp,
            ],
            'logo'   => $logoPath,
            'banner' => $bannerPath,
        ]);

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil ditambahkan.');
    }

    public function edit(PartnerCampus $partnerCampus)
    {
        return view('admin.partner.edit', compact('partnerCampus'));
    }

    public function update(PartnerCampusRequest $request, PartnerCampus $partnerCampus)
    {
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            // Delete old logo safely via Storage API
            if ($partnerCampus->logo && Storage::disk('public')->exists($partnerCampus->logo)) {
                Storage::disk('public')->delete($partnerCampus->logo);
            }
            $partnerCampus->logo = $request->file('logo')->store('partner_campuses/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            // Delete old banner safely via Storage API
            if ($partnerCampus->banner && Storage::disk('public')->exists($partnerCampus->banner)) {
                Storage::disk('public')->delete($partnerCampus->banner);
            }
            $partnerCampus->banner = $request->file('banner')->store('partner_campuses/banners', 'public');
        }

        [$nameJp, $descJp] = $this->translateContent($validated['name'], $validated['description']);

        $partnerCampus->name = [
            'id' => $validated['name'],
            'jp' => $nameJp,
        ];
        $partnerCampus->description = [
            'id' => $validated['description'],
            'jp' => $descJp,
        ];

        $partnerCampus->save();

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil diperbarui.');
    }

    public function destroy(PartnerCampus $partnerCampus)
    {
        // Delete images safely via Storage API (no public_path, no @unlink)
        if ($partnerCampus->logo && Storage::disk('public')->exists($partnerCampus->logo)) {
            Storage::disk('public')->delete($partnerCampus->logo);
        }
        if ($partnerCampus->banner && Storage::disk('public')->exists($partnerCampus->banner)) {
            Storage::disk('public')->delete($partnerCampus->banner);
        }

        $partnerCampus->delete();

        return redirect()->route('admin.partner-campus.index')
                         ->with('success', 'Kampus partner berhasil dihapus.');
    }

    /**
     * Translate content to Japanese using GoogleTranslate.
     * Returns [$nameJp, $descJp].
     */
    private function translateContent(string $name, string $description): array
    {
        try {
            $tr = new GoogleTranslate('ja');
            $tr->setSource('id');
            $nameJp = $tr->translate($name);
            $descJp = $tr->translate($description);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PartnerCampus translate error: ' . $e->getMessage());
            $nameJp = $name;
            $descJp = $description;
        }

        return [$nameJp, $descJp];
    }
}
