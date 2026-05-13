<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PendaftaranRequest;
use App\Services\RegistrationService;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(PendaftaranRequest $request, RegistrationService $registrationService)
    {
        try {
            $applicant = $registrationService->register($request);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim. Silakan tunggu konfirmasi melalui WhatsApp atau Email.',
                'data' => [
                    'id' => $applicant->id,
                    'nama' => $applicant->nama,
                    'status_seleksi' => $applicant->status_seleksi,
                    'form_id' => $applicant->form_id,
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi pendaftaran gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('API Registration Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.',
            ], 500);
        }
    }
}
