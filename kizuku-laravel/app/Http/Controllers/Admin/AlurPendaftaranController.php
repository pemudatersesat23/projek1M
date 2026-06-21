<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlurPendaftaran;
use Illuminate\Http\Request;

class AlurPendaftaranController extends Controller
{
    public function index()
    {
        $alurs = AlurPendaftaran::orderBy('order')->get();
        return view('admin.alur.index', compact('alurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required|string|max:50',
            'title_id' => 'required|string|max:255',
            'title_jp' => 'nullable|string|max:255',
            'description_id' => 'required|string',
            'description_jp' => 'nullable|string',
        ]);

        $maxOrder = AlurPendaftaran::max('order') ?? 0;

        AlurPendaftaran::create([
            'icon' => $request->icon,
            'title' => [
                'id' => $request->title_id,
                'jp' => $request->title_jp,
            ],
            'description' => [
                'id' => $request->description_id,
                'jp' => $request->description_jp,
            ],
            'order' => $maxOrder + 1,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.alur.index')->with('success', 'Langkah baru berhasil ditambahkan.');
    }

    public function update(Request $request, AlurPendaftaran $alur)
    {
        $request->validate([
            'icon' => 'required|string|max:50',
            'title_id' => 'required|string|max:255',
            'title_jp' => 'nullable|string|max:255',
            'description_id' => 'required|string',
            'description_jp' => 'nullable|string',
        ]);

        $alur->update([
            'icon' => $request->icon,
            'title' => [
                'id' => $request->title_id,
                'jp' => $request->title_jp,
            ],
            'description' => [
                'id' => $request->description_id,
                'jp' => $request->description_jp,
            ],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.alur.index')->with('success', 'Langkah berhasil diperbarui.');
    }

    public function destroy(AlurPendaftaran $alur)
    {
        $alur->delete();
        return redirect()->route('admin.alur.index')->with('success', 'Langkah berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:alur_pendaftarans,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($request->orders as $orderData) {
            AlurPendaftaran::where('id', $orderData['id'])->update(['order' => $orderData['order']]);
        }

        return response()->json(['message' => 'Urutan berhasil disimpan.']);
    }
}
