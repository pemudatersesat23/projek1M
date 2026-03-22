<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keunggulan;
use Illuminate\Http\Request;

class KeunggulanController extends Controller
{
    public function index()
    {
        $keunggulans = Keunggulan::orderBy('order', 'asc')->get();
        return view('admin.keunggulans.index', compact('keunggulans'));
    }

    public function create()
    {
        return view('admin.keunggulans.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_id' => 'required|string',
            'description_id' => 'required|string',
            'icon' => 'required|string',
            'order' => 'required|integer',
        ]);

        $keunggulan = new Keunggulan();
        $keunggulan->icon = $validated['icon'];
        $keunggulan->order = $validated['order'];
        $keunggulan->is_active = $request->has('is_active');
        
        $keunggulan->setTranslation('title', 'id', $validated['title_id']);
        $keunggulan->setTranslation('description', 'id', $validated['description_id']);
        
        $keunggulan->save();

        return redirect()->route('admin.keunggulans.index')->with('success', 'Keunggulan berhasil ditambahkan!');
    }

    public function edit(Keunggulan $keunggulan)
    {
        return view('admin.keunggulans.form', compact('keunggulan'));
    }

    public function update(Request $request, Keunggulan $keunggulan)
    {
        $validated = $request->validate([
            'title_id' => 'required|string',
            'description_id' => 'required|string',
            'icon' => 'required|string',
            'order' => 'required|integer',
        ]);

        $keunggulan->icon = $validated['icon'];
        $keunggulan->order = $validated['order'];
        $keunggulan->is_active = $request->has('is_active');
        
        $keunggulan->setTranslation('title', 'id', $validated['title_id']);
        $keunggulan->setTranslation('description', 'id', $validated['description_id']);
        
        $keunggulan->save();

        return redirect()->route('admin.keunggulans.index')->with('success', 'Keunggulan berhasil diperbarui!');
    }

    public function destroy(Keunggulan $keunggulan)
    {
        $keunggulan->delete();
        return redirect()->route('admin.keunggulans.index')->with('success', 'Keunggulan berhasil dihapus!');
    }
}
