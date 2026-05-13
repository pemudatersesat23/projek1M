<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Program;
use App\Models\ProgramSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BackfillFormsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forms:backfill-defaults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill forms default, inject base fields and config documents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting forms backfill...');
        
        $formsCreated = 0;
        $fieldsLinked = 0;
        $baseFieldsInjected = 0;
        $docFieldsInjected = 0;
        $warnings = [];

        DB::transaction(function () use (&$formsCreated, &$fieldsLinked, &$baseFieldsInjected, &$docFieldsInjected, &$warnings) {
            
            // 1. Get all combinations of program_id and schema_id from existing form_fields
            $existingGroups = FormField::whereNull('form_id')
                ->select('program_id', 'schema_id')
                ->distinct()
                ->get();

            // Also, we want to ensure ALL active programs get at least a default form
            $allPrograms = Program::active()->get();
            $allSchemas = ProgramSchema::active()->get();

            $groups = collect();

            foreach ($existingGroups as $group) {
                $groups->push(['program_id' => $group->program_id, 'schema_id' => $group->schema_id]);
            }

            foreach ($allPrograms as $p) {
                if (!$groups->contains(fn($g) => $g['program_id'] === $p->id && $g['schema_id'] === null)) {
                    $groups->push(['program_id' => $p->id, 'schema_id' => null]);
                }
            }

            foreach ($allSchemas as $s) {
                if (!$groups->contains(fn($g) => $g['program_id'] === $s->program_id && $g['schema_id'] === $s->id)) {
                    $groups->push(['program_id' => $s->program_id, 'schema_id' => $s->id]);
                }
            }

            // 2. Process each group
            foreach ($groups as $group) {
                $programId = $group['program_id'];
                $schemaId = $group['schema_id'];

                // Create or find default form
                $form = Form::firstOrCreate([
                    'program_id' => $programId,
                    'schema_id' => $schemaId,
                    'batch_id' => null, // Default form is not batch-specific
                ], [
                    'title' => ['id' => 'Form Pendaftaran Default', 'jp' => 'デフォルト登録フォーム'],
                    'status' => 'published',
                    'is_active' => true,
                    'accepts_responses' => true,
                    'published_at' => now(),
                    'version' => 1,
                ]);

                if ($form->wasRecentlyCreated) {
                    $formsCreated++;
                }

                // Link existing form_fields
                $linked = FormField::whereNull('form_id')
                    ->where('program_id', $programId)
                    ->where('schema_id', $schemaId)
                    ->update(['form_id' => $form->id]);
                $fieldsLinked += $linked;

                // 3. Inject Base Fields
                $baseFields = [
                    ['label' => 'Nama Lengkap', 'name' => 'nama_lengkap', 'type' => 'text', 'role' => 'applicant_name', 'req' => true],
                    ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'role' => 'applicant_email', 'req' => true],
                    ['label' => 'Nomor WhatsApp', 'name' => 'nomor_whatsapp', 'type' => 'phone', 'role' => 'applicant_phone', 'req' => true],
                    ['label' => 'Tempat Lahir', 'name' => 'tempat_lahir', 'type' => 'text', 'role' => 'none', 'req' => false],
                    ['label' => 'Tanggal Lahir', 'name' => 'tanggal_lahir', 'type' => 'date', 'role' => 'applicant_birth_date', 'req' => false],
                    ['label' => 'Alamat', 'name' => 'alamat', 'type' => 'textarea', 'role' => 'applicant_address', 'req' => false],
                    ['label' => 'Pendidikan', 'name' => 'pendidikan', 'type' => 'text', 'role' => 'applicant_education', 'req' => false],
                ];

                // Check existing roles in this form to prevent duplicates
                $existingRoles = FormField::where('form_id', $form->id)->whereNotNull('field_role')->where('field_role', '!=', 'none')->pluck('field_role')->toArray();
                $existingNames = FormField::where('form_id', $form->id)->pluck('field_name')->toArray();

                $sortOrder = -100; // Put them at the very top

                foreach ($baseFields as $bf) {
                    if (in_array($bf['role'], $existingRoles) && $bf['role'] !== 'none') continue;
                    if (in_array($bf['name'], $existingNames)) continue;

                    FormField::create([
                        'form_id' => $form->id,
                        'program_id' => $programId,
                        'schema_id' => $schemaId,
                        'label' => ['id' => $bf['label'], 'jp' => $bf['label']],
                        'field_name' => $bf['name'],
                        'type' => $bf['type'],
                        'field_role' => $bf['role'],
                        'is_required' => $bf['req'],
                        'status' => 'aktif',
                        'sort_order' => $sortOrder++,
                    ]);

                    $baseFieldsInjected++;
                    $existingRoles[] = $bf['role'];
                    $existingNames[] = $bf['name'];
                }

                // 4. Inject Default Document Fields
                $program = Program::find($programId);
                if ($program) {
                    $slug = $program->slug;
                    $configDocs = config('programs.docs_per_slug.' . $slug);
                    
                    if (!$configDocs) {
                        $configDocs = config('programs.docs_per_slug._default', []);
                    }

                    foreach ($configDocs as $docKey => $translationKey) {
                        $fieldName = 'upload_' . $docKey;
                        
                        if (in_array($fieldName, $existingNames)) continue;

                        $labelId = __('messages.form.docs.' . $docKey, [], 'id');
                        // Fallback to key if translation fails or is empty
                        if (str_starts_with($labelId, 'messages.')) {
                            $labelId = str_replace('_', ' ', Str::title($docKey));
                        }

                        FormField::create([
                            'form_id' => $form->id,
                            'program_id' => $programId,
                            'schema_id' => $schemaId,
                            'label' => ['id' => $labelId, 'jp' => $labelId],
                            'field_name' => $fieldName,
                            'type' => 'file',
                            'field_role' => 'none',
                            'accepted_file_types' => ["pdf", "jpg", "jpeg", "png", "doc", "docx"],
                            'max_file_size' => 2048,
                            'is_required' => true, // Defaulting to true as per requirements
                            'status' => 'aktif',
                            'sort_order' => $sortOrder++,
                        ]);

                        $docFieldsInjected++;
                        $existingNames[] = $fieldName;
                    }
                }

                // 5. Publish Readiness Check
                $form->refresh();
                $fields = clone $form->fields; // avoid modifying the relation
                $activeFieldsCount = $fields->where('status', 'aktif')->count();
                $hasName = $fields->where('field_role', 'applicant_name')->isNotEmpty();
                $hasContact = $fields->whereIn('field_role', ['applicant_email', 'applicant_phone'])->isNotEmpty();
                
                $uniqueNames = $fields->pluck('field_name')->unique()->count() === $fields->count();

                if ($activeFieldsCount === 0 || !$hasName || !$hasContact || !$uniqueNames) {
                    $warnings[] = "Form ID {$form->id} (Program: {$programId}, Schema: {$schemaId}) is marked published but might not meet readiness criteria.";
                }
            }
        });

        $remainingNull = FormField::whereNull('form_id')->count();

        $this->info("--- BACKFILL REPORT ---");
        $this->info("Forms created: {$formsCreated}");
        $this->info("FormFields linked: {$fieldsLinked}");
        $this->info("Base fields injected: {$baseFieldsInjected}");
        $this->info("Document fields injected: {$docFieldsInjected}");
        $this->info("FormFields remaining with null form_id: {$remainingNull}");
        
        if (!empty($warnings)) {
            $this->warn("Warnings:");
            foreach ($warnings as $w) {
                $this->warn("- " . $w);
            }
        } else {
            $this->info("No warnings. All generated forms meet publish readiness.");
        }
    }
}
