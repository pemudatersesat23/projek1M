<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = \App\Models\Program::with(['batches' => function($query) {
            $query->where('status', 'dibuka');
        }])->where('status', 'aktif')->get();

        return \App\Http\Resources\ProgramResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Action not allowed'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = \App\Models\Program::with(['batches' => function($query) {
            $query->where('status', 'dibuka');
        }])->findOrFail($id);

        return new \App\Http\Resources\ProgramResource($program);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Action not allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Action not allowed'], 405);
    }

    /**
     * Get the active batch for a specific program.
     */
    public function activeBatch(string $id)
    {
        $program = \App\Models\Program::findOrFail($id);
        
        $activeBatch = $program->batches()
            ->where('status', 'dibuka')
            ->first();

        if (!$activeBatch) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran Ditutup'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $activeBatch->id,
                'nama_batch' => $activeBatch->nama_batch,
                'tanggal_buka' => $activeBatch->tanggal_buka?->format('Y-m-d'),
                'tanggal_tutup' => $activeBatch->tanggal_tutup?->format('Y-m-d'),
                'kuota' => $activeBatch->kuota,
            ]
        ]);
    }
}
