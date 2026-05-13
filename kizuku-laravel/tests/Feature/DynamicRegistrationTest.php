<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DynamicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dynamic_registration_creates_applicant_answers_and_file_metadata(): void
    {
        Storage::fake('local');
        [$program, $batch, $form] = $this->createPublishedFormWithFields();

        $response = $this->from(route('programs.show', $program->slug))->post(route('pendaftaran.store'), [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'Budi Dynamic',
                'email_pendaftar' => 'budi.dynamic@example.com',
            ],
            'dynamic_files' => [
                'dokumen' => UploadedFile::fake()->create('dokumen.txt', 1, 'text/plain'),
            ],
        ]);

        $response->assertRedirect(route('programs.show', $program->slug));
        $this->assertDatabaseHas('applicants', [
            'nama' => 'Budi Dynamic',
            'email' => 'budi.dynamic@example.com',
            'form_id' => $form->id,
        ]);

        $applicant = Applicant::with(['dynamicAnswers', 'dynamicFiles'])->firstOrFail();

        $this->assertCount(2, $applicant->dynamicAnswers);
        $this->assertCount(1, $applicant->dynamicFiles);
        Storage::disk('local')->assertExists($applicant->dynamicFiles->first()->file_path);
    }

    public function test_registration_rejects_form_that_does_not_match_program_batch_resolution(): void
    {
        [$program, $batch, $form] = $this->createPublishedFormWithFields('valid-program');
        [, , $otherForm] = $this->createPublishedFormWithFields('other-program');

        $this->from(route('programs.show', $program->slug))->post(route('pendaftaran.store'), [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'form_id' => $otherForm->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'Wrong Form',
                'email_pendaftar' => 'wrong.form@example.com',
            ],
            'dynamic_files' => [
                'dokumen' => UploadedFile::fake()->create('dokumen.txt', 1, 'text/plain'),
            ],
        ])->assertRedirect(route('programs.show', $program->slug))
            ->assertSessionHasErrors('form_id');

        $this->assertDatabaseMissing('applicants', ['nama' => 'Wrong Form']);
    }

    public function test_registration_rejects_unknown_dynamic_field(): void
    {
        [$program, $batch, $form] = $this->createPublishedFormWithFields();

        $this->from(route('programs.show', $program->slug))->post(route('pendaftaran.store'), [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'Rogue Field',
                'email_pendaftar' => 'rogue.field@example.com',
                'field_tidak_dikenal' => 'bad',
            ],
            'dynamic_files' => [
                'dokumen' => UploadedFile::fake()->create('dokumen.txt', 1, 'text/plain'),
            ],
        ])->assertRedirect(route('programs.show', $program->slug))
            ->assertSessionHasErrors('dynamic_answers.field_tidak_dikenal');

        $this->assertDatabaseMissing('applicants', ['nama' => 'Rogue Field']);
    }

    public function test_registration_rejects_blocked_file_extension(): void
    {
        [$program, $batch, $form] = $this->createPublishedFormWithFields();

        $this->from(route('programs.show', $program->slug))->post(route('pendaftaran.store'), [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'Blocked File',
                'email_pendaftar' => 'blocked.file@example.com',
            ],
            'dynamic_files' => [
                'dokumen' => UploadedFile::fake()->create('shell.php', 1, 'text/plain'),
            ],
        ])->assertRedirect(route('programs.show', $program->slug))
            ->assertSessionHasErrors();

        $this->assertDatabaseMissing('applicants', ['nama' => 'Blocked File']);
    }

    private function createPublishedFormWithFields(string $slug = 'dynamic-program'): array
    {
        $program = Program::create([
            'nama_program' => ['id' => 'Program Dynamic'],
            'slug' => $slug,
            'status' => 'aktif',
            'is_featured' => false,
            'sort_order' => 1,
            'has_schema' => false,
        ]);

        $batch = Batch::create([
            'program_id' => $program->id,
            'nama_batch' => ['id' => 'Batch Dynamic'],
            'status' => 'dibuka',
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'cta_type' => 'internal_form',
        ]);

        $form = Form::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'title' => ['id' => 'Form Dynamic'],
            'status' => 'published',
            'is_active' => true,
            'accepts_responses' => true,
            'version' => 1,
            'published_at' => now(),
        ]);

        FormField::create([
            'form_id' => $form->id,
            'program_id' => $program->id,
            'label' => ['id' => 'Nama Lengkap'],
            'field_name' => 'nama_lengkap',
            'type' => 'text',
            'field_role' => 'applicant_name',
            'is_required' => true,
            'status' => 'aktif',
            'sort_order' => 1,
        ]);

        FormField::create([
            'form_id' => $form->id,
            'program_id' => $program->id,
            'label' => ['id' => 'Email'],
            'field_name' => 'email_pendaftar',
            'type' => 'email',
            'field_role' => 'applicant_email',
            'is_required' => true,
            'status' => 'aktif',
            'sort_order' => 2,
        ]);

        FormField::create([
            'form_id' => $form->id,
            'program_id' => $program->id,
            'label' => ['id' => 'Dokumen'],
            'field_name' => 'dokumen',
            'type' => 'file',
            'accepted_file_types' => ['txt'],
            'max_file_size' => 2048,
            'is_required' => true,
            'status' => 'aktif',
            'sort_order' => 3,
        ]);

        return [$program, $batch, $form];
    }
}
