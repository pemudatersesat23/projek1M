<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::latest()->get();
        return view('admin.hero.index', compact('heroSections'));
    }

    public function create()
    {
        return view('admin.hero.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'btn_primary_text' => 'required|string|max:50',
            'btn_primary_link' => 'required|string',
            'btn_secondary_text' => 'required|string|max:50',
            'btn_secondary_link' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('hero', 'public');
        }

        HeroSection::create($data);

        return redirect()->route('admin.hero-sections.index')->with('success', 'Banner Hero berhasil ditambahkan.');
    }

    public function edit(HeroSection $heroSection)
    {
        return view('admin.hero.edit', compact('heroSection'));
    }

    public function update(Request $request, HeroSection $heroSection)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'btn_primary_text' => 'required|string|max:50',
            'btn_primary_link' => 'required|string',
            'btn_secondary_text' => 'required|string|max:50',
            'btn_secondary_link' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($heroSection->image_path) {
                Storage::disk('public')->delete($heroSection->image_path);
            }
            $data['image_path'] = $request->file('image')->store('hero', 'public');
        }

        $heroSection->update($data);

        return redirect()->route('admin.hero-sections.index')->with('success', 'Banner Hero berhasil diperbarui.');
    }

    public function destroy(HeroSection $heroSection)
    {
        if ($heroSection->image_path) {
            Storage::disk('public')->delete($heroSection->image_path);
        }
        $heroSection->delete();
        return redirect()->route('admin.hero-sections.index')->with('success', 'Banner Hero berhasil dihapus.');
    }
}
