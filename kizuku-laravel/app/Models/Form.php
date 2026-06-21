<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Form extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'program_id',
        'schema_id',
        'batch_id',
        'title',
        'description',
        'success_message',
        'status',
        'is_active',
        'accepts_responses',
        'version',
        'published_at',
    ];

    public $translatable = [
        'title',
        'description',
        'success_message',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'accepts_responses' => 'boolean',
        'published_at' => 'datetime',
        'title' => 'array',
        'description' => 'array',
        'success_message' => 'array',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function schema()
    {
        return $this->belongsTo(ProgramSchema::class, 'schema_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAcceptsResponses($query)
    {
        return $query->where('accepts_responses', true);
    }

    public function scopeForProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    public function scopeForSchema($query, $schemaId)
    {
        return $query->where('schema_id', $schemaId);
    }

    public function scopeForBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function canAcceptResponses(): bool
    {
        return $this->is_active && $this->accepts_responses && $this->isPublished();
    }
}
