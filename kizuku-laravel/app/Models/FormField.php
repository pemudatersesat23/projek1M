<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class FormField extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'program_id',
        'schema_id',
        'label',
        'field_name',
        'type',
        'placeholder',
        'description',
        'options',
        'accepted_file_types',
        'max_file_size',
        'is_required',
        'status',
        'sort_order'
    ];

    public $translatable = [
        'label',
        'placeholder',
        'description'
    ];

    protected $casts = [
        'options' => 'array',
        'accepted_file_types' => 'array',
        'is_required' => 'boolean'
    ];

    // Relations
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function schema()
    {
        return $this->belongsTo(ProgramSchema::class, 'schema_id');
    }

    public function answers()
    {
        return $this->hasMany(ApplicantFormAnswer::class);
    }

    public function dynamicFiles()
    {
        return $this->hasMany(ApplicantDynamicFile::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    public function scopeForProgram($query, $programId)
    {
        return $query->where('program_id', $programId)->whereNull('schema_id');
    }

    public function scopeForSchema($query, $schemaId)
    {
        return $query->where('schema_id', $schemaId);
    }

    // Helpers
    public function isFile()
    {
        return $this->type === config('dynamic_forms.file_field_type', 'file');
    }

    public function isChoiceField()
    {
        return in_array($this->type, config('dynamic_forms.choice_field_types', ['select', 'radio', 'checkbox']));
    }

    public function isRequired()
    {
        return $this->is_required;
    }

    public function getOptionValues()
    {
        if (empty($this->options)) {
            return [];
        }
        
        return array_map(function ($option) {
            return $option['value'] ?? null;
        }, $this->options);
    }

    public function getLabelForLocale($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->getTranslation('label', $locale);
    }
}
