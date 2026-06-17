<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('type', 40);
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('items')->nullable();
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->backfillExistingPrograms();
    }

    public function down(): void
    {
        Schema::dropIfExists('program_sections');
    }

    private function backfillExistingPrograms(): void
    {
        if (!Schema::hasTable('programs')) {
            return;
        }

        $programs = DB::table('programs')->get();

        foreach ($programs as $program) {
            if (DB::table('program_sections')->where('program_id', $program->id)->exists()) {
                continue;
            }

            $order = 0;

            $infoItems = array_values(array_filter([
                $this->sectionItem('Target Peserta', $this->localizedText($program->target_peserta ?? null), 'groups'),
                $this->sectionItem('Materi Utama', $this->localizedText($program->materi ?? null), 'menu_book'),
                $this->sectionItem('Fokus Pelatihan', $this->localizedText($program->focus ?? null), 'track_changes'),
                $this->sectionItem('Output Program', $this->localizedText($program->output ?? null), 'verified'),
            ], fn ($item) => $item['description'] !== ''));

            if (!empty($infoItems)) {
                $this->insertSection($program->id, 'info_grid', 'Informasi Program', null, $infoItems, $order++);
            }

            if (($program->slug ?? null) === 'tokutei-ginou-tg') {
                $fields = config('programs.tg_fields', []);
                $items = collect($fields)
                    ->map(fn ($field) => $this->sectionItem($field['display'] ?? '', '', $field['icon'] ?? 'work'))
                    ->filter(fn ($item) => $item['title'] !== '')
                    ->values()
                    ->all();

                if (!empty($items)) {
                    $title = config('programs.tg_fields_by_slug.tokutei-ginou-tg.section_title', '10 Bidang Pekerjaan Tokutei Ginou');
                    $description = config('programs.tg_fields_by_slug.tokutei-ginou-tg.section_desc', '');
                    $this->insertSection($program->id, 'cards', $title, $description, $items, $order++);
                }
            }

            $benefits = $this->linesFromText($program->benefit ?? null);
            if (!empty($benefits)) {
                $this->insertSection(
                    $program->id,
                    'checklist',
                    'Benefit Program',
                    null,
                    array_map(fn ($line) => $this->sectionItem($line), $benefits),
                    $order++
                );
            }

            $steps = $this->linesFromText($program->alur_seleksi ?? null);
            if (!empty($steps)) {
                $this->insertSection(
                    $program->id,
                    'timeline',
                    'Alur Seleksi',
                    null,
                    array_map(fn ($line) => $this->sectionItem($line), $steps),
                    $order++
                );
            }

            $faqs = $this->faqItems($program->faq ?? null);
            if (!empty($faqs)) {
                $this->insertSection($program->id, 'faq', 'FAQ', null, $faqs, $order++);
            }
        }
    }

    private function insertSection(int $programId, string $type, ?string $title, ?string $description, array $items, int $order): void
    {
        DB::table('program_sections')->insert([
            'program_id' => $programId,
            'type' => $type,
            'title' => $this->translation($title),
            'description' => $this->translation($description),
            'items' => json_encode(['id' => $items], JSON_UNESCAPED_UNICODE),
            'settings' => json_encode([], JSON_UNESCAPED_UNICODE),
            'sort_order' => $order,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function sectionItem(?string $title, ?string $description = '', ?string $icon = ''): array
    {
        return [
            'title' => trim((string) $title),
            'description' => trim((string) $description),
            'icon' => trim((string) $icon),
        ];
    }

    private function translation(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === ''
            ? null
            : json_encode(['id' => $value], JSON_UNESCAPED_UNICODE);
    }

    private function localizedText(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (is_array($decoded)) {
            if (isset($decoded['id']) && is_string($decoded['id'])) {
                return trim($decoded['id']);
            }

            foreach ($decoded as $candidate) {
                if (is_string($candidate) && trim($candidate) !== '') {
                    return trim($candidate);
                }
            }

            return '';
        }

        return trim((string) $value);
    }

    private function linesFromText(mixed $value): array
    {
        $text = $this->localizedText($value);
        $text = str_replace(['✓', 'âœ“'], '', $text);

        return collect(preg_split('/\r\n|\r|\n/', $text) ?: [])
            ->map(fn ($line) => trim(ltrim($line, "-• \t")))
            ->filter()
            ->values()
            ->all();
    }

    private function faqItems(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (isset($decoded['id']) && is_array($decoded['id'])) {
            $decoded = $decoded['id'];
        }

        if (!is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->map(fn ($item) => $this->sectionItem($item['q'] ?? '', $item['a'] ?? '', 'help'))
            ->filter(fn ($item) => $item['title'] !== '' || $item['description'] !== '')
            ->values()
            ->all();
    }
};
