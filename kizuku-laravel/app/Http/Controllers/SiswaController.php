<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kota', 'like', "%{$s}%");
            });
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswas = $query->latest()->get();

        $stats = [
            'total'     => Siswa::count(),
            'aktif'     => Siswa::where('status', 'Aktif')->count(),
            'berangkat' => Siswa::where('status', 'Berangkat')->count(),
            'proses'    => Siswa::where('status', 'Proses')->count(),
        ];

        return view('admin.siswa.index', compact('siswas', 'stats'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'wa'      => 'required|string|max:50',
            'kota'    => 'required|string|max:255',
            'program' => 'required|string|max:255',
        ]);

        $extraFields = $this->buildExtraFields($request);

        Siswa::create([
            'nama'           => $request->nama,
            'wa'             => $request->wa,
            'email'          => $request->email,
            'kota'           => $request->kota,
            'program'        => $request->program,
            'status'         => $request->status ?? 'Aktif',
            'pendidikan'     => $request->pendidikan,
            'catatan'        => $request->catatan,
            'tgl_lahir'      => $request->tgl_lahir,
            'extra_fields'   => $extraFields ? json_encode($extraFields) : null,
            'payment_status' => $request->payment_status ?? 'Pending',
        ]);

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Data \"{$request->nama}\" berhasil disimpan!");
    }

    public function show(Siswa $siswa)
    {
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'wa'      => 'required|string|max:50',
            'kota'    => 'required|string|max:255',
            'program' => 'required|string|max:255',
        ]);

        $extraFields = $this->buildExtraFields($request);

        $data = [
            'nama'       => $request->nama,
            'wa'         => $request->wa,
            'email'      => $request->email,
            'kota'       => $request->kota,
            'program'    => $request->program,
            'status'     => $request->status ?? $siswa->status,
            'pendidikan' => $request->pendidikan,
            'catatan'    => $request->catatan,
            'tgl_lahir'  => $request->tgl_lahir,
        ];

        if ($extraFields) {
            $data['extra_fields'] = json_encode($extraFields);
        }

        if ($request->has('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }

        $siswa->update($data);

        // Redirect back to detail page if updating payment status from show page
        if ($request->has('payment_status') && !$request->has('pendidikan')) {
            return redirect()->route('admin.siswa.show', $siswa)
                             ->with('success', "Status pembayaran \"{$siswa->nama}\" berhasil diupdate!");
        }

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Data \"{$siswa->nama}\" berhasil diupdate!");
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama;
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Data \"{$nama}\" berhasil dihapus!");
    }

    /**
     * Build extra fields array from request based on program type.
     */
    private function buildExtraFields(Request $request): ?array
    {
        $program = $request->program;
        $extra = [];

        if ($program === 'Engineering') {
            if ($request->extra_jurusan) $extra['jurusan'] = $request->extra_jurusan;
            if ($request->extra_ipk) $extra['ipk'] = $request->extra_ipk;
            if ($request->extra_skill_software) $extra['skill_software'] = $request->extra_skill_software;
        } elseif ($program === 'Tokutei Ginou (TG)') {
            if ($request->extra_level_bahasa) $extra['level_bahasa'] = $request->extra_level_bahasa;
            if ($request->extra_sertifikat_skill) $extra['sertifikat_skill'] = $request->extra_sertifikat_skill;
            if ($request->extra_bidang) $extra['bidang'] = $request->extra_bidang;
        } elseif ($program === 'Returnee / Ex Jepang') {
            if ($request->extra_perusahaan) $extra['perusahaan'] = $request->extra_perusahaan;
            if ($request->extra_lama_kontrak) $extra['lama_kontrak'] = $request->extra_lama_kontrak;
        }

        return !empty($extra) ? $extra : null;
    }
}
