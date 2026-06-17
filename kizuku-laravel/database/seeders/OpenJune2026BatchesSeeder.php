<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpenJune2026BatchesSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::active()->ordered()->get();
        $whatsappNumber = \App\Models\Setting::get('whatsapp_number', '6281217549529');

        DB::transaction(function () use ($programs, $whatsappNumber) {
            foreach ($programs as $program) {
                $existing = Batch::where('program_id', $program->id)
                    ->whereDate('tanggal_buka', '2026-06-18')
                    ->first();
                $batchName = $this->batchName($program, $existing);

                Batch::where('program_id', $program->id)
                    ->whereIn('status', ['dibuka', 'diperpanjang'])
                    ->when($existing, fn ($query) => $query->where('id', '!=', $existing->id))
                    ->update(['status' => 'ditutup']);

                $hasPublishedForm = $program->forms()
                    ->where('status', 'published')
                    ->where('is_active', true)
                    ->where('accepts_responses', true)
                    ->exists();

                $ctaType = $hasPublishedForm ? 'internal_form' : 'whatsapp';
                $whatsappLink = $ctaType === 'whatsapp'
                    ? 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode(
                        'Halo Admin Kizuku, saya ingin mendaftar ' . $program->getTranslation('nama_program', 'id', false) . ' untuk ' . $batchName . '.'
                    )
                    : null;

                $payload = [
                    'nama_batch' => ['id' => $batchName],
                    'status' => 'dibuka',
                    'tanggal_buka' => '2026-06-18',
                    'tanggal_tutup' => '2026-07-18',
                    'tanggal_mulai' => '2026-08-01',
                    'tanggal_selesai' => null,
                    'tanggal_estimasi_selesai' => $this->estimatedFinishDate($program->slug),
                    'kuota' => 25,
                    'cta_type' => $ctaType,
                    'link_form' => null,
                    'whatsapp_link' => $whatsappLink,
                ];

                if ($existing) {
                    $existing->update($payload);
                } else {
                    $program->batches()->create($payload);
                }
            }
        });
    }

    private function batchName(Program $program, ?Batch $existing): string
    {
        if ($existing) {
            return $existing->getTranslation('nama_batch', 'id', false);
        }

        $nextNumber = Batch::withTrashed()
            ->where('program_id', $program->id)
            ->count() + 1;

        return "Batch {$nextNumber} - Juni 2026";
    }

    private function estimatedFinishDate(string $slug): ?string
    {
        return match ($slug) {
            'tokutei-ginou-tg' => '2027-01-01',
            'engineer-jepang-gijinkoku' => '2027-02-01',
            'kursus-bahasa-jepang' => '2026-09-01',
            'engineer-jepang-ex-internship' => '2026-11-01',
            default => null,
        };
    }
}
