<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ProgramSection extends Model
{
    use HasTranslations, SoftDeletes;

    public const TYPES = [
        'info_grid' => 'Info Cards',
        'text' => 'Teks Bebas',
        'cards' => 'Kartu / Bidang',
        'checklist' => 'Checklist',
        'timeline' => 'Timeline',
        'faq' => 'FAQ',
    ];

    protected $fillable = [
        'program_id',
        'type',
        'title',
        'description',
        'items',
        'settings',
        'sort_order',
        'is_active',
    ];

    public array $translatable = [
        'title',
        'description',
        'items',
    ];

    protected $casts = [
        'settings' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function localizedTitle(): string
    {
        return $this->localizedTranslation('title');
    }

    public function localizedDescription(): string
    {
        return $this->localizedTranslation('description');
    }

    public function localizedItems(): array
    {
        $locale = app()->getLocale();
        $items = $this->getTranslation('items', $locale, false)
            ?: $this->getTranslation('items', 'id', false)
            ?: [];

        return is_array($items) ? $items : [];
    }

    private function localizedTranslation(string $field): string
    {
        $locale = app()->getLocale();

        return (string) (
            $this->getTranslation($field, $locale, false)
            ?: $this->getTranslation($field, 'id', false)
            ?: ''
        );
    }
}
