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

    public function test_export_download_returns_applicant_excel(): void
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
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');

        $this->assertStringContainsString('.xls', $response->headers->get('content-disposition'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('<table', $content);
        $this->assertStringContainsString('Budi Export', $content);
    }

    public function test_export_status_filter_affects_preview_and_excel_download(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch] = $this->createProgramAndBatch();

        Applicant::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama' => 'Budi Baru',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Makassar',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test',
            'phone' => '08123456789',
            'email' => 'budi.baru@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'baru',
        ]);

        Applicant::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama' => 'Sari Lolos',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Makassar',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test',
            'phone' => '08123456780',
            'email' => 'sari.lolos@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'lolos',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.export', ['status' => 'lolos']))
            ->assertOk()
            ->assertSee('Sari Lolos')
            ->assertDontSee('Budi Baru');

        $content = $this->actingAs($admin)
            ->get(route('admin.export.download', ['status' => 'lolos']))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Sari Lolos', $content);
        $this->assertStringNotContainsString('Budi Baru', $content);
    }

    public function test_export_program_and_batch_filters_affect_preview_and_excel_download(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$programA, $batchA] = $this->createProgramAndBatch('program-export-a');
        [$programB, $batchB] = $this->createProgramAndBatch('program-export-b');

        Applicant::create([
            'program_id' => $programA->id,
            'batch_id' => $batchA->id,
            'nama' => 'Applicant Program A',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Makassar',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test',
            'phone' => '08123456789',
            'email' => 'program.a@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'baru',
        ]);

        Applicant::create([
            'program_id' => $programB->id,
            'batch_id' => $batchB->id,
            'nama' => 'Applicant Program B',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Makassar',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test',
            'phone' => '08123456780',
            'email' => 'program.b@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'review',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.export', ['program_id' => $programB->id]))
            ->assertOk()
            ->assertSee('Applicant Program B')
            ->assertDontSee('Applicant Program A')
            ->assertDontSee('Filter Program ID');

        $content = $this->actingAs($admin)
            ->get(route('admin.export.download', ['batch_id' => $batchA->id]))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Applicant Program A', $content);
        $this->assertStringNotContainsString('Applicant Program B', $content);
    }

    public function test_admin_can_open_builder_and_empty_responses_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, , $form] = $this->createFormFixture();

        $this->actingAs($admin)
            ->get(route('admin.forms.builder', $form))
            ->assertOk()
            ->assertSee('Add Question')
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

    public function test_admin_can_create_program_with_dynamic_content_sections(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.programs.store'), [
            'nama_program' => 'Program Universal Test',
            'slug' => 'program-universal-test',
            'deskripsi' => 'Deskripsi program universal.',
            'durasi' => '3 Bulan',
            'biaya' => 'Rp 1.000.000',
            'status' => 'aktif',
            'sort_order' => 1,
            'sections' => [
                [
                    'type' => 'cards',
                    'title' => 'Bidang Kerja Fleksibel',
                    'description' => 'Konten ini berasal dari database.',
                    'sort_order' => 0,
                    'is_active' => 1,
                    'items' => [
                        ['title' => 'Perhotelan Premium', 'description' => 'Front office dan hospitality.', 'icon' => 'hotel'],
                        ['title' => 'Manufaktur Modern', 'description' => 'Produksi dan quality control.', 'icon' => 'factory'],
                    ],
                ],
                [
                    'type' => 'checklist',
                    'title' => 'Keunggulan Program',
                    'description' => '',
                    'sort_order' => 1,
                    'is_active' => 1,
                    'items' => [
                        ['title' => 'Bisa diatur dari dashboard', 'description' => '', 'icon' => 'check'],
                    ],
                ],
            ],
        ]);

        $program = Program::where('slug', 'program-universal-test')->firstOrFail();
        $response->assertRedirect(route('admin.programs.index'));

        $this->assertCount(2, $program->sections);
        $this->assertSame('Bidang Kerja Fleksibel', $program->sections()->first()->getTranslation('title', 'id'));

        Batch::create([
            'program_id' => $program->id,
            'nama_batch' => ['id' => 'Batch Universal'],
            'status' => 'dibuka',
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'cta_type' => 'whatsapp',
        ]);

        $this->get(route('programs.show', $program->slug))
            ->assertOk()
            ->assertSee('Bidang Kerja Fleksibel')
            ->assertSee('Konten ini berasal dari database.')
            ->assertSee('Perhotelan Premium')
            ->assertSee('Bisa diatur dari dashboard');
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
