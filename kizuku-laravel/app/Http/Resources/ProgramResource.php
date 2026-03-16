<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_program' => $this->nama_program,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'durasi' => $this->durasi,
            'benefit' => $this->benefit,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'active_batch' => $this->batches->where('status', 'dibuka')->first() ? [
                'id' => $this->batches->where('status', 'dibuka')->first()->id,
                'nama_batch' => $this->batches->where('status', 'dibuka')->first()->nama_batch,
                'tanggal_buka' => $this->batches->where('status', 'dibuka')->first()->tanggal_buka,
                'tanggal_tutup' => $this->batches->where('status', 'dibuka')->first()->tanggal_tutup,
            ] : null,
            'upcoming_batch' => $this->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first() ? [
                'id' => $this->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first()->id,
                'nama_batch' => $this->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first()->nama_batch,
                'tanggal_buka' => $this->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first()->tanggal_buka,
            ] : null,
        ];
    }
}
