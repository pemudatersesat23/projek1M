<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Form;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminStabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_page_uses_applicants_without_requiring_siswas_table(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertFalse(Schema::hasTable('siswas'));

        $this->actingAs($admin)
            ->get(route('admin.export'))
            ->assertOk()
            ->assertSee('Export Data Pendaftar');
    }

    public function test_export_download_returns_applicant_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch] = $this->createProgramAndBatch();

        Applicant::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama' => 'Budi Export',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Makassar',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test',
            'phone' => '08123456789',
            'email' => 'budi.export@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'baru',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.export.download'));

        $response->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->assertStringContainsString('Budi Export', $response->streamedContent());
    }

    public function test_admin_can_open_builder_and_empty_responses_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, , $form] = $this->createFormFixture();

        $this->actingAs($admin)
            ->get(route('admin.forms.builder', $form))
            ->assertOk()
            ->assertSee('Questions')
            ->assertSee('Responses');

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.index', $form))
            ->assertOk()
            ->assertSee('Belum ada response untuk form ini.');
    }

    public function test_response_detail_rejects_applicant_from_different_form_or_without_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch, $formA] = $this->createFormFixture('form-a');
        [, , $formB] = $this->createFormFixture('form-b');

        $applicantA = $this->createApplicant($program, $batch, $formA, 'Applicant A');
        $applicantB = $this->createApplicant($program, $batch, $formB, 'Applicant B');
        $legacyApplicant = $this->createApplicant($program, $batch, null, 'Legacy Applicant');

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.show', [$formA, $applicantA]))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.show', [$formA, $applicantB]))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.show', [$formA, $legacyApplicant]))
            ->assertForbidden();
    }

    private function createProgramAndBatch(string $slug = 'program-test'): array
    {
        $program = Program::create([
            'nama_program' => ['id' => 'Program Test'],
            'slug' => $slug,
            'status' => 'aktif',
            'is_featured' => false,
            'sort_order' => 1,
            'has_schema' => false,
        ]);

        $batch = Batch::create([
            'program_id' => $program->id,
            'nama_batch' => ['id' => 'Batch Test'],
            'status' => 'dibuka',
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'cta_type' => 'internal_form',
        ]);

        return [$program, $batch];
    }

    private function createFormFixture(string $slug = 'program-test'): array
    {
        [$program, $batch] = $this->createProgramAndBatch($slug);

        $form = Form::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'title' => ['id' => 'Form Test'],
            'status' => 'published',
            'is_active' => true,
            'accepts_responses' => true,
            'version' => 1,
            'published_at' => now(),
        ]);

        return [$program, $batch, $form];
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
