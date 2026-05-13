<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Form;
use App\Models\Program;
use App\Models\ProgramSchema;
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

    public function test_admin_can_create_form_from_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program] = $this->createProgramAndBatch('engineering-jepang-test');

        $schema = ProgramSchema::create([
            'program_id' => $program->id,
            'nama_skema' => ['id' => 'Reguler'],
            'slug' => 'reguler',
            'tipe' => 'reguler',
            'status' => 'aktif',
            'harga' => 15000000,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.forms.store'), [
            'program_id' => $program->id,
            'schema_id' => $schema->id,
            'title_id' => 'Form Pendaftaran Engineering Jepang - Reguler',
            'title_jp' => '',
        ]);

        $form = Form::where('program_id', $program->id)
            ->where('schema_id', $schema->id)
            ->firstOrFail();

        $response->assertRedirect(route('admin.forms.builder', $form));

        $this->assertSame('draft', $form->status);
        $this->assertTrue((bool) $form->is_active);
        $this->assertFalse((bool) $form->accepts_responses);
        $this->assertSame(1, $form->version);
        $this->assertSame('Form Pendaftaran Engineering Jepang - Reguler', $form->getTranslation('title', 'id'));
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

    public function test_admin_pages_tolerate_soft_deleted_program_or_batch_relations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch] = $this->createProgramAndBatch('orphan-relation-test');

        $schema = ProgramSchema::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama_skema' => ['id' => 'Reguler Orphan'],
            'slug' => 'reguler-orphan',
            'tipe' => 'reguler',
            'status' => 'aktif',
            'harga' => 1000,
            'sort_order' => 1,
        ]);

        $form = Form::create([
            'program_id' => $program->id,
            'schema_id' => $schema->id,
            'batch_id' => $batch->id,
            'title' => ['id' => 'Form Orphan'],
            'status' => 'published',
            'is_active' => true,
            'accepts_responses' => true,
            'version' => 1,
            'published_at' => now(),
        ]);

        $applicant = $this->createApplicant($program, $batch, $form, 'Applicant Orphan');

        $program->delete();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Applicant Orphan')
            ->assertSee('Program tidak tersedia')
            ->assertSee('Batch Test');

        $this->actingAs($admin)
            ->get(route('admin.batches.index'))
            ->assertOk()
            ->assertSee('Program tidak tersedia');

        $this->actingAs($admin)
            ->get(route('admin.program-schemas.index'))
            ->assertOk()
            ->assertSee('Program tidak tersedia');

        $this->actingAs($admin)
            ->get(route('admin.forms.index'))
            ->assertOk()
            ->assertSee('Form Orphan');

        $this->actingAs($admin)
            ->get(route('admin.applicants.show', $applicant))
            ->assertOk()
            ->assertSee('Applicant Orphan');

        $batch->delete();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Applicant Orphan')
            ->assertSee('Batch tidak tersedia');
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
