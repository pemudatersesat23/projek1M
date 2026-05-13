<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\ApplicantDynamicFile;
use App\Models\ApplicantFormAnswer;
use App\Models\Batch;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormResponseExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_empty_form_responses_as_header_only_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, , $form] = $this->createFormWithFields();

        $response = $this->actingAs($admin)
            ->get(route('admin.forms.responses.export.csv', $form));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();

        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('submitted_at,applicant_id,applicant_name,email,phone,program,batch,schema,form_title,form_version', $csv);
        $this->assertStringContainsString('Nama Lengkap', $csv);
        $this->assertStringContainsString('希望職種', $csv);
        $this->assertStringContainsString('Dokumen', $csv);
    }

    public function test_admin_can_export_only_current_form_responses_with_dynamic_values_and_safe_file_names(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch, $form, $fields] = $this->createFormWithFields();
        [, , $otherForm] = $this->createFormWithFields('other-export-program');

        $applicant = $this->createApplicant($program, $batch, $form, 'Budi Export');
        $this->createApplicant($program, $batch, $otherForm, 'Other Form Applicant');
        $this->createApplicant($program, $batch, null, 'Legacy Applicant');

        ApplicantFormAnswer::create([
            'applicant_id' => $applicant->id,
            'form_field_id' => $fields['name']->id,
            'value' => "Budi, Tokyo\n京都",
            'field_label_snapshot' => $fields['name']->getTranslations('label'),
            'field_type_snapshot' => 'text',
        ]);

        ApplicantFormAnswer::create([
            'applicant_id' => $applicant->id,
            'form_field_id' => $fields['select']->id,
            'value' => 'tokutei',
            'field_label_snapshot' => $fields['select']->getTranslations('label'),
            'field_type_snapshot' => 'select',
        ]);

        ApplicantFormAnswer::create([
            'applicant_id' => $applicant->id,
            'form_field_id' => $fields['checkbox']->id,
            'value' => ['perawat', 'pertanian'],
            'field_label_snapshot' => $fields['checkbox']->getTranslations('label'),
            'field_type_snapshot' => 'checkbox',
        ]);

        ApplicantDynamicFile::create([
            'applicant_id' => $applicant->id,
            'form_field_id' => $fields['file']->id,
            'file_path' => 'private/registrations/1/sensitive.pdf',
            'original_name' => '履歴書.pdf',
            'mime_type' => 'application/pdf',
            'size' => 10,
            'field_label_snapshot' => $fields['file']->getTranslations('label'),
            'field_type_snapshot' => 'file',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.forms.responses.export.csv', $form));

        $response->assertOk();

        $csv = $response->streamedContent();

        $this->assertStringContainsString('Budi Export', $csv);
        $this->assertStringContainsString('Budi, Tokyo', $csv);
        $this->assertStringContainsString('京都', $csv);
        $this->assertStringContainsString('tokutei', $csv);
        $this->assertStringContainsString('perawat, pertanian', $csv);
        $this->assertStringContainsString('Uploaded: 履歴書.pdf', $csv);

        $this->assertStringNotContainsString('private/registrations', $csv);
        $this->assertStringNotContainsString('sensitive.pdf', $csv);
        $this->assertStringNotContainsString('Other Form Applicant', $csv);
        $this->assertStringNotContainsString('Legacy Applicant', $csv);
    }

    public function test_responses_index_shows_export_csv_button(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, , $form] = $this->createFormWithFields();

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.index', $form))
            ->assertOk()
            ->assertSee('Export CSV')
            ->assertSee(route('admin.forms.responses.export.csv', $form), false);
    }

    private function createFormWithFields(string $slug = 'export-program'): array
    {
        $program = Program::create([
            'nama_program' => ['id' => 'Program Export', 'jp' => 'エクスポートプログラム'],
            'slug' => $slug,
            'status' => 'aktif',
            'is_featured' => false,
            'sort_order' => 1,
            'has_schema' => false,
        ]);

        $batch = Batch::create([
            'program_id' => $program->id,
            'nama_batch' => ['id' => 'Batch Export'],
            'status' => 'dibuka',
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'cta_type' => 'internal_form',
        ]);

        $form = Form::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'title' => ['id' => 'Form Export', 'jp' => 'フォーム 応募'],
            'status' => 'published',
            'is_active' => true,
            'accepts_responses' => true,
            'version' => 3,
            'published_at' => now(),
        ]);

        $fields = [
            'name' => FormField::create([
                'form_id' => $form->id,
                'program_id' => $program->id,
                'label' => ['id' => 'Nama Lengkap'],
                'field_name' => 'nama_lengkap',
                'type' => 'text',
                'field_role' => 'applicant_name',
                'is_required' => true,
                'status' => 'aktif',
                'sort_order' => 1,
            ]),
            'select' => FormField::create([
                'form_id' => $form->id,
                'program_id' => $program->id,
                'label' => ['id' => '希望職種'],
                'field_name' => 'bidang',
                'type' => 'select',
                'options' => [['value' => 'tokutei', 'label' => ['id' => 'Tokutei']]],
                'is_required' => false,
                'status' => 'aktif',
                'sort_order' => 2,
            ]),
            'checkbox' => FormField::create([
                'form_id' => $form->id,
                'program_id' => $program->id,
                'label' => ['id' => 'Minat Kerja'],
                'field_name' => 'minat_kerja',
                'type' => 'checkbox',
                'options' => [
                    ['value' => 'perawat', 'label' => ['id' => 'Perawat']],
                    ['value' => 'pertanian', 'label' => ['id' => 'Pertanian']],
                ],
                'is_required' => false,
                'status' => 'aktif',
                'sort_order' => 3,
            ]),
            'file' => FormField::create([
                'form_id' => $form->id,
                'program_id' => $program->id,
                'label' => ['id' => 'Dokumen'],
                'field_name' => 'dokumen',
                'type' => 'file',
                'accepted_file_types' => ['pdf'],
                'max_file_size' => 2048,
                'is_required' => false,
                'status' => 'aktif',
                'sort_order' => 4,
            ]),
        ];

        return [$program, $batch, $form, $fields];
    }

    private function createApplicant(Program $program, Batch $batch, ?Form $form, string $name): Applicant
    {
        return Applicant::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'form_id' => $form?->id,
            'form_version_snapshot' => $form?->version,
            'form_title_snapshot' => $form?->title,
            'nama' => $name,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Makassar',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test',
            'phone' => '08123456789',
            'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'baru',
        ]);
    }
}
