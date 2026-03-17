<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PendaftaranRequest;
use App\Models\Applicant;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle the incoming registration request.
     */
    public function register(PendaftaranRequest $request)
    {
        try {
            // Create applicant from validated data
            // Note: PendaftaranRequest already handles validation for program_id, batch_id, etc.
            $applicant = Applicant::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim. Silakan tunggu konfirmasi melalui WhatsApp atau Email.',
                'data' => [
                    'id' => $applicant->id,
                    'nama' => $applicant->nama,
                    'status_seleksi' => $applicant->status_seleksi
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('API Registration Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
            ], 500);
        }
    }
}
