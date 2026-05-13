<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Program;
use App\Models\ProgramSchema;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FinalDynamicFormQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_end_to_end_dynamic_form_flow_responses_download_and_export(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch, $schema, $form] = $this->createQaForm(status: 'published');

        $this->actingAs($admin)
            ->get(route('admin.forms.index'))
            ->assertOk()
            ->assertSee('Form QA Jepang')
            ->assertSee(route('admin.forms.responses.index', $form), false);

        $this->actingAs($admin)
            ->get(route('admin.forms.builder', $form))
            ->assertOk()
            ->assertSee('Questions')
            ->assertSee('Responses')
            ->assertSee('Nama Lengkap');

        $this->actingAs($admin)
            ->get(route('admin.forms.preview', $form))
            ->assertOk()
            ->assertSee('Nama Lengkap')
            ->assertSee('Level Bahasa Jepang')
            ->assertSee('Upload CV');

        $this->get(route('programs.show', $program->slug))
            ->assertOk()
            ->assertSee('name="form_id"', false)
            ->assertSee('id="dynamic-fields-container"', false)
            ->assertSee('type="submit"', false)
            ->assertDontSee('Formulir pendaftaran untuk program ini belum tersedia')
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertDontSee('name="nama"', false)
            ->assertDontSee('name="email"', false)
            ->assertDontSee('name="phone"', false);

        $this->getJson(route('api.dynamic-fields', [
            'program_id' => $program->id,
            'schema_id' => $schema->id,
            'batch_id' => $batch->id,
        ]))->assertOk()
            ->assertJsonPath('form_id', $form->id)
            ->assertJsonFragment(['field_name' => 'nama_lengkap'])
            ->assertJsonFragment(['field_name' => 'upload_cv']);

        $this->from(route('programs.show', $program->slug))->post(route('pendaftaran.store'), [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'schema_id' => $schema->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'QA User',
                'email' => 'qa.user@example.com',
                'nomor_whatsapp' => '081234567890',
                'tanggal_lahir' => '2000-05-13',
                'alamat' => "Jalan QA, Kendari\nSulawesi",
                'level_bahasa_jepang' => 'N4',
                'minat_bidang' => ['Perawat', 'Pertanian'],
            ],
            'dynamic_files' => [
                'upload_cv' => UploadedFile::fake()->create('cv qa.pdf', 12, 'application/pdf'),
            ],
        ])->assertRedirect(route('programs.show', $program->slug))
            ->assertSessionHas('success', 'Terima kasih');

        $applicant = Applicant::with(['dynamicAnswers', 'dynamicFiles'])->where('nama', 'QA User')->firstOrFail();

        $this->assertSame($program->id, $applicant->program_id);
        $this->assertSame($batch->id, $applicant->batch_id);
        $this->assertSame($schema->id, $applicant->schema_id);
        $this->assertSame($form->id, $applicant->form_id);
        $this->assertSame($form->version, $applicant->form_version_snapshot);
        $this->assertSame('Form QA Jepang', $applicant->form_title_snapshot['id']);
        $this->assertSame('qa.user@example.com', $applicant->email);
        $this->assertSame('081234567890', $applicant->phone);
        $this->assertSame('2000-05-13', $applicant->tanggal_lahir->toDateString());
        $this->assertSame("Jalan QA, Kendari\nSulawesi", $applicant->alamat);
        $this->assertGreaterThanOrEqual(7, $applicant->dynamicAnswers->count());
        $this->assertCount(1, $applicant->dynamicFiles);
        Storage::disk('local')->assertExists($applicant->dynamicFiles->first()->file_path);

        $this->actingAs($admin)
            ->get(route('admin.applicants.index'))
            ->assertOk()
            ->assertSee('QA User')
            ->assertSee('qa.user@example.com')
            ->assertSee('081234567890');

        $this->actingAs($admin)
            ->get(route('admin.applicants.show', $applicant))
            ->assertOk()
            ->assertSee('QA User')
            ->assertSee('Form QA Jepang')
            ->assertSee('Upload CV')
            ->assertDontSee('private/registrations');

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.index', $form))
            ->assertOk()
            ->assertSee('QA User')
            ->assertSee('Export CSV');

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.show', [$form, $applicant]))
            ->assertOk()
            ->assertSee('QA User')
            ->assertSee('Level Bahasa Jepang')
            ->assertSee('cv_qa.pdf')
            ->assertDontSee('private/registrations');

        $file = $applicant->dynamicFiles->first();
        $this->actingAs($admin)
            ->get(route('admin.applicants.dynamic-files.download', [$applicant, $file]))
            ->assertOk();

        $otherApplicant = $this->createLegacyApplicant($program, $batch, 'Legacy QA');
        $this->actingAs($admin)
            ->get(route('admin.applicants.show', $otherApplicant))
            ->assertOk()
            ->assertSee('Legacy QA');

        $this->actingAs($admin)
            ->get(route('admin.forms.responses.show', [$form, $otherApplicant]))
            ->assertForbidden();

        $csv = $this->actingAs($admin)
            ->get(route('admin.forms.responses.export.csv', $form))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('submitted_at,applicant_id,applicant_name,email,phone,program,batch,schema,form_title,form_version', $csv);
        $this->assertStringContainsString('Nama Lengkap', $csv);
        $this->assertStringContainsString('Level Bahasa Jepang', $csv);
        $this->assertStringContainsString('Perawat, Pertanian', $csv);
        $this->assertStringContainsString('Uploaded: cv_qa.pdf', $csv);
        $this->assertStringContainsString('QA User', $csv);
        $this->assertStringNotContainsString('Legacy QA', $csv);
        $this->assertStringNotContainsString('private/registrations', $csv);
        $this->assertStringNotContainsString('storage/app', $csv);
    }

    public function test_engineering_jepang_admin_program_schema_form_public_response_and_export_flow(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        [$program, $batch, $reguler, $beasiswa] = $this->createEngineeringProgramGraph();

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Data Program')
            ->assertSee('Batch Pendaftaran')
            ->assertSee('Skema Program')
            ->assertSee('Form Builder Baru')
            ->assertSee('Data Pendaftar');

        $this->get(route('admin.programs.index'))->assertOk()->assertSee('Engineering Jepang');
        $this->get(route('admin.programs.edit', $program))->assertOk()->assertSee('engineering-jepang');

        $program->update([
            'deskripsi' => [
                'id' => 'Program persiapan kerja bidang engineering ke Jepang untuk lulusan teknik yang ingin berkarier secara profesional di perusahaan Jepang. Update QA.',
                'jp' => 'Program persiapan kerja bidang engineering ke Jepang untuk lulusan teknik yang ingin berkarier secara profesional di perusahaan Jepang. Update QA.',
            ],
        ]);
        $this->assertStringContainsString('Update QA', $program->refresh()->getTranslation('deskripsi', 'id'));

        $this->get(route('admin.batches.index'))->assertOk()->assertSee('Batch Engineering Jepang Mei 2026');
        $this->get(route('admin.batches.edit', $batch))->assertOk()->assertSee('internal_form');

        $this->get(route('admin.program-schemas.index'))
            ->assertOk()
            ->assertSee('Reguler')
            ->assertSee('Beasiswa');
        $this->get(route('admin.program-schemas.edit', $reguler))
            ->assertOk()
            ->assertSee('data-program-id="' . $program->id . '"', false);

        $regularForm = $this->createDashboardForm($program, $reguler, 'Form Pendaftaran Engineering Jepang - Reguler', 'Terima kasih. Pendaftaran Anda berhasil dikirim. Tim kami akan menghubungi Anda untuk proses selanjutnya.');
        $regularFields = $this->createRegularEngineeringFields($regularForm);

        $experience = $regularFields['pengalaman_kerja'];
        $this->patchJson(route('admin.forms.fields.update', [$regularForm, $experience]), [
            'program_id' => $program->id,
            'schema_id' => $reguler->id,
            'label_id' => 'Pengalaman Kerja / Magang',
            'field_name' => 'pengalaman_kerja',
            'type' => 'textarea',
            'field_role' => 'none',
            'is_required' => 0,
            'status' => 'aktif',
        ])->assertOk();

        $duplicate = $this->postJson(route('admin.forms.fields.duplicate', [$regularForm, $experience]))
            ->assertOk()
            ->json('field');
        $this->assertSame('none', $duplicate['field_role']);
        $this->deleteJson(route('admin.forms.fields.destroy', [$regularForm, $duplicate['id']]))->assertOk();
        $this->assertSoftDeleted('form_fields', ['id' => $duplicate['id']]);

        $order = collect($regularFields)->pluck('id')->values()->all();
        [$order[6], $order[7]] = [$order[7], $order[6]];
        $this->postJson(route('admin.forms.fields.reorder', $regularForm), ['order' => $order])->assertOk();
        $this->assertSame(7, FormField::findOrFail($order[6])->sort_order);

        $this->get(route('admin.forms.preview', $regularForm))
            ->assertOk()
            ->assertSee('Pengalaman Kerja / Magang')
            ->assertSee('Upload CV');
        $this->postJson(route('admin.forms.publish', $regularForm))->assertOk();
        $regularForm->refresh();
        $this->assertSame('published', $regularForm->status);

        $beasiswaForm = $this->createDashboardForm($program, $beasiswa, 'Form Pendaftaran Engineering Jepang - Beasiswa', 'Pendaftaran beasiswa berhasil dikirim. Tim seleksi akan menghubungi Anda setelah proses verifikasi.');
        $this->createBeasiswaEngineeringFields($beasiswaForm);
        $this->get(route('admin.forms.preview', $beasiswaForm))->assertOk()->assertSee('Alasan Mengikuti Beasiswa');
        $this->postJson(route('admin.forms.publish', $beasiswaForm))->assertOk();
        $beasiswaForm->refresh();
        $this->assertSame('published', $beasiswaForm->status);

        $this->get(route('programs.show', $program->slug))
            ->assertOk()
            ->assertSee('Engineering Jepang')
            ->assertSee('Update QA')
            ->assertSee('Batch Engineering Jepang Mei 2026')
            ->assertSee('Daftar Sekarang')
            ->assertSee('Reguler')
            ->assertSee('Beasiswa')
            ->assertSee('id="dynamic-fields-container"', false)
            ->assertDontSee('Formulir pendaftaran untuk program ini belum tersedia')
            ->assertDontSee('name="nama"', false)
            ->assertDontSee('name="email"', false);

        $this->getJson(route('api.dynamic-fields', [
            'program_id' => $program->id,
            'schema_id' => $reguler->id,
            'batch_id' => $batch->id,
        ]))->assertOk()
            ->assertJsonPath('form_id', $regularForm->id)
            ->assertJsonFragment(['field_name' => 'upload_cv'])
            ->assertJsonFragment(['field_name' => 'bidang_engineering'])
            ->assertJsonMissing(['field_name' => 'nama_kampus']);

        $this->getJson(route('api.dynamic-fields', [
            'program_id' => $program->id,
            'schema_id' => $beasiswa->id,
            'batch_id' => $batch->id,
        ]))->assertOk()
            ->assertJsonPath('form_id', $beasiswaForm->id)
            ->assertJsonFragment(['field_name' => 'nama_kampus'])
            ->assertJsonFragment(['field_name' => 'upload_surat_rekomendasi'])
            ->assertJsonMissing(['field_name' => 'bidang_engineering']);

        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm))
            ->assertRedirect(route('programs.show', $program->slug))
            ->assertSessionHas('success', 'Terima kasih. Pendaftaran Anda berhasil dikirim. Tim kami akan menghubungi Anda untuk proses selanjutnya.');

        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $this->beasiswaEngineeringPayload($program, $batch, $beasiswa, $beasiswaForm))
            ->assertRedirect(route('programs.show', $program->slug))
            ->assertSessionHas('success', 'Pendaftaran beasiswa berhasil dikirim. Tim seleksi akan menghubungi Anda setelah proses verifikasi.');

        $regularApplicant = Applicant::with(['dynamicAnswers', 'dynamicFiles'])->where('nama', 'QA Engineering Reguler')->firstOrFail();
        $beasiswaApplicant = Applicant::with(['dynamicAnswers', 'dynamicFiles'])->where('nama', 'QA Engineering Beasiswa')->firstOrFail();

        $this->assertSame($regularForm->id, $regularApplicant->form_id);
        $this->assertSame($reguler->id, $regularApplicant->schema_id);
        $this->assertSame('qa.reguler@example.com', $regularApplicant->email);
        $this->assertSame('081234567890', $regularApplicant->phone);
        $this->assertCount(10, $regularApplicant->dynamicAnswers);
        $this->assertCount(3, $regularApplicant->dynamicFiles);
        Storage::disk('local')->assertExists($regularApplicant->dynamicFiles->first()->file_path);

        $this->assertSame($beasiswaForm->id, $beasiswaApplicant->form_id);
        $this->assertSame($beasiswa->id, $beasiswaApplicant->schema_id);
        $this->assertSame('qa.beasiswa@example.com', $beasiswaApplicant->email);
        $this->assertCount(11, $beasiswaApplicant->dynamicAnswers);
        $this->assertCount(2, $beasiswaApplicant->dynamicFiles);

        $this->get(route('admin.applicants.index'))
            ->assertOk()
            ->assertSee('QA Engineering Reguler')
            ->assertSee('QA Engineering Beasiswa')
            ->assertSee('Engineering Jepang')
            ->assertSee('Batch Engineering Jepang Mei 2026')
            ->assertSee('Reguler')
            ->assertSee('Beasiswa');

        $this->get(route('admin.applicants.show', $regularApplicant))
            ->assertOk()
            ->assertSee('Form Pendaftaran Engineering Jepang - Reguler')
            ->assertSee('Pengalaman Kerja / Magang')
            ->assertSee('Upload CV')
            ->assertSee('Reguler')
            ->assertDontSee('private/registrations');

        $this->get(route('admin.applicants.show', $beasiswaApplicant))
            ->assertOk()
            ->assertSee('Alasan Mengikuti Beasiswa')
            ->assertSee('Upload Surat Rekomendasi')
            ->assertSee('Beasiswa')
            ->assertDontSee('private/registrations');

        $this->get(route('admin.forms.responses.index', $regularForm))
            ->assertOk()
            ->assertSee('QA Engineering Reguler')
            ->assertDontSee('QA Engineering Beasiswa');
        $this->get(route('admin.forms.responses.show', [$regularForm, $regularApplicant]))
            ->assertOk()
            ->assertSee('Software Engineering')
            ->assertDontSee('private/registrations');
        $this->get(route('admin.forms.responses.show', [$regularForm, $beasiswaApplicant]))->assertForbidden();

        $this->get(route('admin.forms.responses.index', $beasiswaForm))
            ->assertOk()
            ->assertSee('QA Engineering Beasiswa')
            ->assertDontSee('QA Engineering Reguler');
        $this->get(route('admin.forms.responses.show', [$beasiswaForm, $beasiswaApplicant]))
            ->assertOk()
            ->assertSee('Universitas QA')
            ->assertSee('surat_rekomendasi.pdf')
            ->assertDontSee('private/registrations');

        $regularCsv = $this->get(route('admin.forms.responses.export.csv', $regularForm))->assertOk()->streamedContent();
        $this->assertStringContainsString('submitted_at,applicant_id,applicant_name,email,phone,program,batch,schema,form_title,form_version', $regularCsv);
        $this->assertStringContainsString('Bidang Engineering yang Diminati', $regularCsv);
        $this->assertStringContainsString('Software Engineering, Industrial Engineering', $regularCsv);
        $this->assertStringContainsString('Uploaded: cv_reguler.pdf', $regularCsv);
        $this->assertStringContainsString('QA Engineering Reguler', $regularCsv);
        $this->assertStringNotContainsString('QA Engineering Beasiswa', $regularCsv);
        $this->assertStringNotContainsString('private/registrations', $regularCsv);

        $beasiswaCsv = $this->get(route('admin.forms.responses.export.csv', $beasiswaForm))->assertOk()->streamedContent();
        $this->assertStringContainsString('Nama Kampus / Sekolah Asal', $beasiswaCsv);
        $this->assertStringContainsString('Alasan Mengikuti Beasiswa', $beasiswaCsv);
        $this->assertStringContainsString('QA Engineering Beasiswa', $beasiswaCsv);
        $this->assertStringNotContainsString('QA Engineering Reguler', $beasiswaCsv);
        $this->assertStringNotContainsString('private/registrations', $beasiswaCsv);

        $this->get(route('admin.applicants.dynamic-files.download', [$regularApplicant, $beasiswaApplicant->dynamicFiles->first()]))
            ->assertForbidden();

        $this->get(route('admin.dashboard'))->assertOk();
        auth()->logout();
        $this->get(route('admin.dashboard'))->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.dashboard'))
            ->assertRedirect('/');

        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), array_replace_recursive(
                $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm),
                ['dynamic_answers' => ['nama_lengkap' => '']]
            ))->assertSessionHasErrors('dynamic_answers.nama_lengkap');

        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), array_replace_recursive(
                $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm),
                ['dynamic_answers' => ['email' => 'invalid-email']]
            ))->assertSessionHasErrors('dynamic_answers.email');

        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), array_replace_recursive(
                $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm),
                ['dynamic_answers' => ['pendidikan_terakhir' => '']]
            ))->assertSessionHasErrors('dynamic_answers.pendidikan_terakhir');

        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), array_replace_recursive(
                $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm),
                ['dynamic_answers' => ['level_bahasa_jepang' => '']]
            ))->assertSessionHasErrors('dynamic_answers.level_bahasa_jepang');

        $invalidPhpPayload = $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm);
        $invalidPhpPayload['dynamic_files']['upload_cv'] = UploadedFile::fake()->create('shell.php', 1, 'text/plain');
        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $invalidPhpPayload)
            ->assertSessionHasErrors('dynamic_files.upload_cv');

        $tooLargePayload = $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm);
        $tooLargePayload['dynamic_files']['upload_cv'] = UploadedFile::fake()->create('big.pdf', 3000, 'application/pdf');
        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $tooLargePayload)
            ->assertSessionHasErrors('dynamic_files.upload_cv');

        $wrongFormPayload = $this->beasiswaEngineeringPayload($program, $batch, $beasiswa, $beasiswaForm);
        $wrongFormPayload['form_id'] = $regularForm->id;
        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $wrongFormPayload)
            ->assertSessionHasErrors('form_id');

        $unknownAnswerPayload = $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm);
        $unknownAnswerPayload['dynamic_answers']['is_admin'] = '1';
        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $unknownAnswerPayload)
            ->assertSessionHasErrors('dynamic_answers.is_admin');

        $unknownFilePayload = $this->regularEngineeringPayload($program, $batch, $reguler, $regularForm);
        $unknownFilePayload['dynamic_files']['malware'] = UploadedFile::fake()->create('malware.pdf', 1, 'application/pdf');
        $this->from(route('programs.show', $program->slug))
            ->post(route('pendaftaran.store'), $unknownFilePayload)
            ->assertSessionHasErrors('dynamic_files.malware');
    }

    public function test_publish_validation_rejects_invalid_form_configurations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        [, , , $noName] = $this->createQaForm(status: 'draft', includeName: false);
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $noName))->assertUnprocessable();

        [, , , $noContact] = $this->createQaForm('qa-no-contact', status: 'draft', includeEmail: false, includePhone: false);
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $noContact))->assertUnprocessable();

        [, , , $duplicateRole] = $this->createQaForm('qa-duplicate-role', status: 'draft');
        $this->addField($duplicateRole, 'nama_lengkap_dua', 'Nama Lain', 'text', 'applicant_name');
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $duplicateRole))->assertUnprocessable();

        [, , , $duplicateName] = $this->createQaForm('qa-duplicate-name', status: 'draft');
        $this->addField($duplicateName, 'nama_lengkap', 'Nama Bentrok', 'text', 'none');
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $duplicateName))->assertUnprocessable();

        [, , , $choiceNoOptions] = $this->createQaForm('qa-choice-empty', status: 'draft');
        $this->addField($choiceNoOptions, 'pilihan_kosong', 'Pilihan Kosong', 'select', 'none', options: null);
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $choiceNoOptions))->assertUnprocessable();

        [, , , $choiceDuplicate] = $this->createQaForm('qa-choice-duplicate', status: 'draft');
        $this->addField($choiceDuplicate, 'pilihan_duplikat', 'Pilihan Duplikat', 'select', 'none', options: [
            ['value' => 'sama', 'label' => ['id' => 'Sama 1']],
            ['value' => 'sama', 'label' => ['id' => 'Sama 2']],
        ]);
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $choiceDuplicate))->assertUnprocessable();

        [, , , $blockedFile] = $this->createQaForm('qa-blocked-file', status: 'draft');
        $this->addField($blockedFile, 'file_berbahaya', 'File Berbahaya', 'file', 'none', acceptedFileTypes: ['pdf', 'php']);
        $this->actingAs($admin)->postJson(route('admin.forms.publish', $blockedFile))->assertUnprocessable();

        [, , , $valid] = $this->createQaForm('qa-valid-publish', status: 'draft');
        $this->actingAs($admin)
            ->postJson(route('admin.forms.publish', $valid))
            ->assertOk();

        $valid->refresh();
        $this->assertSame('published', $valid->status);
        $this->assertTrue($valid->is_active);
        $this->assertTrue($valid->accepts_responses);
        $this->assertNotNull($valid->published_at);
    }

    public function test_builder_field_crud_duplicate_reorder_and_soft_delete_endpoints(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$program, , , $form] = $this->createQaForm('qa-builder-crud', status: 'draft');

        $response = $this->actingAs($admin)->postJson(route('admin.forms.fields.store', $form), [
            'program_id' => $program->id,
            'schema_id' => $form->schema_id,
            'label_id' => 'Pertanyaan Text Baru',
            'field_name' => 'pertanyaan_text_baru',
            'type' => 'text',
            'field_role' => 'none',
            'is_required' => 1,
            'status' => 'aktif',
        ])->assertOk();

        $fieldId = $response->json('field.id');

        $this->actingAs($admin)->patchJson(route('admin.forms.fields.update', [$form, $fieldId]), [
            'program_id' => $program->id,
            'schema_id' => $form->schema_id,
            'label_id' => 'Pertanyaan Select Baru',
            'field_name' => 'pertanyaan_select_baru',
            'type' => 'select',
            'field_role' => 'none',
            'is_required' => 1,
            'status' => 'aktif',
            'options' => json_encode([
                ['value' => 'opsi_a', 'label' => ['id' => 'Opsi A', 'jp' => 'Opsi A']],
                ['value' => 'opsi_b', 'label' => ['id' => 'Opsi B', 'jp' => 'Opsi B']],
            ]),
        ])->assertOk();

        $field = FormField::findOrFail($fieldId);
        $this->assertSame('pertanyaan_select_baru', $field->field_name);
        $this->assertSame('select', $field->type);

        $duplicate = $this->actingAs($admin)
            ->postJson(route('admin.forms.fields.duplicate', [$form, $field]))
            ->assertOk()
            ->json('field');

        $this->assertSame('none', $duplicate['field_role']);
        $this->assertNotSame($field->field_name, $duplicate['field_name']);

        $this->actingAs($admin)->postJson(route('admin.forms.fields.reorder', $form), [
            'order' => [$duplicate['id'], $field->id],
        ])->assertOk();

        $this->assertSame(1, FormField::find($duplicate['id'])->sort_order);
        $this->assertSame(2, FormField::find($field->id)->sort_order);

        $this->actingAs($admin)
            ->deleteJson(route('admin.forms.fields.destroy', [$form, $field]))
            ->assertOk();

        $this->assertSoftDeleted('form_fields', ['id' => $field->id]);
    }

    public function test_public_registration_rejects_invalid_dynamic_payloads(): void
    {
        [$program, $batch, $schema, $form] = $this->createQaForm(status: 'published');

        $base = [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'schema_id' => $schema->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'Invalid QA',
                'email' => 'invalid.qa@example.com',
                'nomor_whatsapp' => '081234567890',
                'tanggal_lahir' => '2000-05-13',
                'alamat' => 'Alamat QA',
                'level_bahasa_jepang' => 'N5',
                'minat_bidang' => ['Perawat'],
            ],
        ];

        $this->postInvalid($base, ['dynamic_answers' => array_merge($base['dynamic_answers'], ['nama_lengkap' => ''])])
            ->assertSessionHasErrors('dynamic_answers.nama_lengkap');

        $this->postInvalid($base, ['dynamic_answers' => array_merge($base['dynamic_answers'], ['email' => 'bad-email'])])
            ->assertSessionHasErrors('dynamic_answers.email');

        $this->postInvalid($base, ['dynamic_answers' => array_merge($base['dynamic_answers'], ['level_bahasa_jepang' => 'N1'])])
            ->assertSessionHasErrors('dynamic_answers.level_bahasa_jepang');

        $this->postInvalid($base, ['dynamic_answers' => array_merge($base['dynamic_answers'], ['minat_bidang' => ['Admin']])])
            ->assertSessionHasErrors('dynamic_answers.minat_bidang.0');

        $this->postInvalid($base, ['dynamic_answers' => array_merge($base['dynamic_answers'], ['is_admin' => '1'])])
            ->assertSessionHasErrors('dynamic_answers.is_admin');

        $this->postInvalid($base, ['dynamic_files' => ['malware' => UploadedFile::fake()->create('malware.pdf', 1, 'application/pdf')]])
            ->assertSessionHasErrors('dynamic_files.malware');

        $this->postInvalid($base, ['dynamic_files' => ['upload_cv' => UploadedFile::fake()->create('shell.php', 1, 'text/plain')]])
            ->assertSessionHasErrors('dynamic_files.upload_cv');

        $this->postInvalid($base, ['dynamic_files' => ['upload_cv' => UploadedFile::fake()->create('big.pdf', 3000, 'application/pdf')]])
            ->assertSessionHasErrors('dynamic_files.upload_cv');

        $batch->update(['status' => 'ditutup']);
        $this->postInvalid($base)->assertSessionHasErrors('batch_id');
        $batch->update(['status' => 'dibuka']);

        [$otherProgram, , $otherSchema, $otherForm] = $this->createQaForm('other-program', status: 'published');
        $this->postInvalid($base, ['schema_id' => $otherSchema->id])->assertSessionHasErrors('schema_id');
        $this->postInvalid($base, ['form_id' => $otherForm->id])->assertSessionHasErrors('form_id');

        $this->assertDatabaseMissing('applicants', ['nama' => 'Invalid QA']);
        $this->assertNotSame($program->id, $otherProgram->id);
    }

    public function test_dynamic_download_idor_is_rejected(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['role' => 'admin']);
        [$program, $batch, , $form] = $this->createQaForm(status: 'published');
        $applicantA = $this->createLegacyApplicant($program, $batch, 'Applicant A', $form);
        $applicantB = $this->createLegacyApplicant($program, $batch, 'Applicant B', $form);
        $fileField = $form->fields()->where('type', 'file')->firstOrFail();

        $path = 'private/registrations/' . $applicantA->id . '/qa.pdf';
        Storage::disk('local')->put($path, 'qa');
        $file = $applicantA->dynamicFiles()->create([
            'form_field_id' => $fileField->id,
            'file_path' => $path,
            'original_name' => 'qa.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1,
            'field_label_snapshot' => $fileField->getTranslations('label'),
            'field_type_snapshot' => 'file',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.applicants.dynamic-files.download', [$applicantB, $file]))
            ->assertForbidden();
    }

    private function createEngineeringProgramGraph(): array
    {
        $program = Program::create([
            'nama_program' => ['id' => 'Engineering Jepang', 'jp' => 'Engineering Jepang'],
            'slug' => 'engineering-jepang',
            'status' => 'aktif',
            'is_featured' => true,
            'has_schema' => true,
            'sort_order' => 1,
            'deskripsi' => [
                'id' => 'Program persiapan kerja bidang engineering ke Jepang untuk lulusan teknik yang ingin berkarier secara profesional di perusahaan Jepang.',
                'jp' => 'Program persiapan kerja bidang engineering ke Jepang untuk lulusan teknik yang ingin berkarier secara profesional di perusahaan Jepang.',
            ],
            'focus' => [
                'id' => 'Program Engineering Jepang adalah program persiapan bagi peserta dengan latar belakang teknik yang ingin bekerja di Jepang.',
                'jp' => 'Program Engineering Jepang adalah program persiapan bagi peserta dengan latar belakang teknik yang ingin bekerja di Jepang.',
            ],
            'target_peserta' => [
                'id' => "- Minimal lulusan SMA/SMK/D3/S1\n- Diutamakan jurusan teknik\n- Sehat jasmani dan rohani\n- Bersedia mengikuti pelatihan bahasa Jepang\n- Memiliki motivasi kerja di Jepang",
                'jp' => "- Minimal lulusan SMA/SMK/D3/S1\n- Diutamakan jurusan teknik\n- Sehat jasmani dan rohani\n- Bersedia mengikuti pelatihan bahasa Jepang\n- Memiliki motivasi kerja di Jepang",
            ],
            'benefit' => [
                'id' => "- Bimbingan bahasa Jepang\n- Persiapan interview kerja\n- Pendampingan dokumen\n- Materi budaya kerja Jepang\n- Cocok untuk lulusan teknik\n- Pendampingan hingga proses keberangkatan",
                'jp' => "- Bimbingan bahasa Jepang\n- Persiapan interview kerja\n- Pendampingan dokumen\n- Materi budaya kerja Jepang\n- Cocok untuk lulusan teknik\n- Pendampingan hingga proses keberangkatan",
            ],
            'materi' => ['id' => 'Bahasa Jepang, budaya kerja Jepang, dokumen, interview, dan administrasi keberangkatan.', 'jp' => 'Bahasa Jepang, budaya kerja Jepang, dokumen, interview, dan administrasi keberangkatan.'],
            'durasi' => ['id' => '6 sampai 12 bulan', 'jp' => '6 sampai 12 bulan'],
            'biaya' => ['id' => 'Rp 15.000.000', 'jp' => 'Rp 15.000.000'],
        ]);

        $startDate = now()->addDays(45);
        $batch = Batch::create([
            'program_id' => $program->id,
            'nama_batch' => ['id' => 'Batch Engineering Jepang Mei 2026', 'jp' => 'Batch Engineering Jepang Mei 2026'],
            'tanggal_buka' => now()->toDateString(),
            'tanggal_tutup' => now()->addDays(30)->toDateString(),
            'tanggal_mulai' => $startDate->toDateString(),
            'tanggal_selesai' => $startDate->copy()->addYear()->toDateString(),
            'kuota' => 30,
            'status' => 'dibuka',
            'cta_type' => 'internal_form',
        ]);

        $reguler = ProgramSchema::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama_skema' => ['id' => 'Reguler', 'jp' => 'Reguler'],
            'slug' => 'reguler',
            'tipe' => 'reguler',
            'status' => 'aktif',
            'harga' => 15000000,
            'sort_order' => 1,
            'deskripsi' => ['id' => 'Skema reguler untuk peserta yang mengikuti program Engineering Jepang secara mandiri.', 'jp' => 'Skema reguler untuk peserta yang mengikuti program Engineering Jepang secara mandiri.'],
        ]);

        $beasiswa = ProgramSchema::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama_skema' => ['id' => 'Beasiswa', 'jp' => 'Beasiswa'],
            'slug' => 'beasiswa',
            'tipe' => 'beasiswa',
            'status' => 'aktif',
            'harga' => 0,
            'sort_order' => 2,
            'deskripsi' => ['id' => 'Skema beasiswa untuk peserta terpilih yang memenuhi kriteria tertentu.', 'jp' => 'Skema beasiswa untuk peserta terpilih yang memenuhi kriteria tertentu.'],
        ]);

        return [$program, $batch, $reguler, $beasiswa];
    }

    private function createDashboardForm(Program $program, ProgramSchema $schema, string $title, string $successMessage): Form
    {
        $this->post(route('admin.forms.store'), [
            'program_id' => $program->id,
            'schema_id' => $schema->id,
            'title_id' => $title,
            'description_id' => str_contains($title, 'Beasiswa')
                ? 'Formulir ini digunakan untuk pendaftaran skema beasiswa Engineering Jepang.'
                : 'Silakan isi data pendaftaran dengan benar. Pastikan semua dokumen yang diunggah valid dan dapat dibaca.',
            'success_message_id' => $successMessage,
        ])->assertRedirect();

        return Form::where('program_id', $program->id)
            ->where('schema_id', $schema->id)
            ->where('title->id', $title)
            ->firstOrFail();
    }

    private function createRegularEngineeringFields(Form $form): array
    {
        return [
            'nama_lengkap' => $this->storeFieldFromBuilder($form, 'Nama Lengkap', 'nama_lengkap', 'text', 'applicant_name', true),
            'email' => $this->storeFieldFromBuilder($form, 'Email', 'email', 'email', 'applicant_email', true),
            'nomor_whatsapp' => $this->storeFieldFromBuilder($form, 'Nomor WhatsApp', 'nomor_whatsapp', 'phone', 'applicant_phone', true),
            'tanggal_lahir' => $this->storeFieldFromBuilder($form, 'Tanggal Lahir', 'tanggal_lahir', 'date', 'applicant_birth_date', true),
            'alamat_lengkap' => $this->storeFieldFromBuilder($form, 'Alamat Lengkap', 'alamat_lengkap', 'textarea', 'applicant_address', true),
            'pendidikan_terakhir' => $this->storeFieldFromBuilder($form, 'Pendidikan Terakhir', 'pendidikan_terakhir', 'select', 'applicant_education', true, options: $this->qaOptions(['SMA/SMK', 'D3', 'S1', 'S2'])),
            'jurusan' => $this->storeFieldFromBuilder($form, 'Jurusan', 'jurusan', 'text', 'none', true),
            'pengalaman_kerja' => $this->storeFieldFromBuilder($form, 'Pengalaman Kerja', 'pengalaman_kerja', 'textarea', 'none', false),
            'level_bahasa_jepang' => $this->storeFieldFromBuilder($form, 'Level Bahasa Jepang', 'level_bahasa_jepang', 'select', 'none', true, options: $this->qaOptions(['Belum Pernah Belajar', 'N5', 'N4', 'N3', 'N2'])),
            'bidang_engineering' => $this->storeFieldFromBuilder($form, 'Bidang Engineering yang Diminati', 'bidang_engineering', 'checkbox', 'none', true, options: $this->qaOptions(['Mechanical Engineering', 'Electrical Engineering', 'Civil Engineering', 'Software Engineering', 'Industrial Engineering'])),
            'upload_cv' => $this->storeFieldFromBuilder($form, 'Upload CV', 'upload_cv', 'file', 'none', true, acceptedFileTypes: ['pdf', 'doc', 'docx']),
            'upload_ijazah' => $this->storeFieldFromBuilder($form, 'Upload Ijazah', 'upload_ijazah', 'file', 'none', true, acceptedFileTypes: ['pdf', 'jpg', 'jpeg', 'png']),
            'upload_ktp' => $this->storeFieldFromBuilder($form, 'Upload KTP', 'upload_ktp', 'file', 'none', true, acceptedFileTypes: ['pdf', 'jpg', 'jpeg', 'png']),
        ];
    }

    private function createBeasiswaEngineeringFields(Form $form): array
    {
        return [
            'nama_lengkap' => $this->storeFieldFromBuilder($form, 'Nama Lengkap', 'nama_lengkap', 'text', 'applicant_name', true),
            'email' => $this->storeFieldFromBuilder($form, 'Email', 'email', 'email', 'applicant_email', true),
            'nomor_whatsapp' => $this->storeFieldFromBuilder($form, 'Nomor WhatsApp', 'nomor_whatsapp', 'phone', 'applicant_phone', true),
            'tanggal_lahir' => $this->storeFieldFromBuilder($form, 'Tanggal Lahir', 'tanggal_lahir', 'date', 'applicant_birth_date', true),
            'alamat_lengkap' => $this->storeFieldFromBuilder($form, 'Alamat Lengkap', 'alamat_lengkap', 'textarea', 'applicant_address', true),
            'pendidikan_terakhir' => $this->storeFieldFromBuilder($form, 'Pendidikan Terakhir', 'pendidikan_terakhir', 'select', 'applicant_education', true, options: $this->qaOptions(['SMA/SMK', 'D3', 'S1', 'S2'])),
            'jurusan' => $this->storeFieldFromBuilder($form, 'Jurusan', 'jurusan', 'text', 'none', true),
            'level_bahasa_jepang' => $this->storeFieldFromBuilder($form, 'Level Bahasa Jepang', 'level_bahasa_jepang', 'select', 'none', true, options: $this->qaOptions(['Belum Pernah Belajar', 'N5', 'N4', 'N3', 'N2'])),
            'nama_kampus' => $this->storeFieldFromBuilder($form, 'Nama Kampus / Sekolah Asal', 'nama_kampus', 'text', 'none', true),
            'nilai_rata_rata' => $this->storeFieldFromBuilder($form, 'IPK / Nilai Rata-rata', 'nilai_rata_rata', 'number', 'none', true),
            'alasan_beasiswa' => $this->storeFieldFromBuilder($form, 'Alasan Mengikuti Beasiswa', 'alasan_beasiswa', 'textarea', 'none', true),
            'upload_surat_rekomendasi' => $this->storeFieldFromBuilder($form, 'Upload Surat Rekomendasi', 'upload_surat_rekomendasi', 'file', 'none', true, acceptedFileTypes: ['pdf', 'jpg', 'jpeg', 'png']),
            'upload_sertifikat_pendukung' => $this->storeFieldFromBuilder($form, 'Upload Sertifikat Pendukung', 'upload_sertifikat_pendukung', 'file', 'none', false, acceptedFileTypes: ['pdf', 'jpg', 'jpeg', 'png']),
        ];
    }

    private function storeFieldFromBuilder(
        Form $form,
        string $label,
        string $fieldName,
        string $type,
        string $role = 'none',
        bool $required = false,
        ?array $options = null,
        ?array $acceptedFileTypes = null,
    ): FormField {
        $payload = [
            'program_id' => $form->program_id,
            'schema_id' => $form->schema_id,
            'label_id' => $label,
            'field_name' => $fieldName,
            'type' => $type,
            'field_role' => $role,
            'is_required' => $required ? 1 : 0,
            'status' => 'aktif',
        ];

        if ($options !== null) {
            $payload['options'] = json_encode($options);
        }

        if ($acceptedFileTypes !== null) {
            $payload['accepted_file_types'] = json_encode($acceptedFileTypes);
            $payload['max_file_size'] = 2048;
        }

        $fieldId = $this->postJson(route('admin.forms.fields.store', $form), $payload)
            ->assertOk()
            ->json('field.id');

        return FormField::findOrFail($fieldId);
    }

    private function qaOptions(array $values): array
    {
        return array_map(fn (string $value) => [
            'value' => $value,
            'label' => ['id' => $value, 'jp' => $value],
        ], $values);
    }

    private function regularEngineeringPayload(Program $program, Batch $batch, ProgramSchema $schema, Form $form): array
    {
        return [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'schema_id' => $schema->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'QA Engineering Reguler',
                'email' => 'qa.reguler@example.com',
                'nomor_whatsapp' => '081234567890',
                'tanggal_lahir' => '2000-01-01',
                'alamat_lengkap' => 'Jl. QA Testing No. 1',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Teknik Informatika',
                'pengalaman_kerja' => 'Pernah magang sebagai software engineer.',
                'level_bahasa_jepang' => 'N4',
                'bidang_engineering' => ['Software Engineering', 'Industrial Engineering'],
            ],
            'dynamic_files' => [
                'upload_cv' => UploadedFile::fake()->create('cv_reguler.pdf', 100, 'application/pdf'),
                'upload_ijazah' => UploadedFile::fake()->create('ijazah_reguler.pdf', 100, 'application/pdf'),
                'upload_ktp' => UploadedFile::fake()->create('ktp_reguler.pdf', 100, 'application/pdf'),
            ],
        ];
    }

    private function beasiswaEngineeringPayload(Program $program, Batch $batch, ProgramSchema $schema, Form $form): array
    {
        return [
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'schema_id' => $schema->id,
            'form_id' => $form->id,
            'dynamic_answers' => [
                'nama_lengkap' => 'QA Engineering Beasiswa',
                'email' => 'qa.beasiswa@example.com',
                'nomor_whatsapp' => '081298765432',
                'tanggal_lahir' => '2001-02-02',
                'alamat_lengkap' => 'Jl. QA Beasiswa No. 2',
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Teknik Mesin',
                'level_bahasa_jepang' => 'N5',
                'nama_kampus' => 'Universitas QA',
                'nilai_rata_rata' => '3.75',
                'alasan_beasiswa' => 'Saya ingin mengikuti program ini untuk meningkatkan kemampuan profesional dan bekerja di Jepang.',
            ],
            'dynamic_files' => [
                'upload_surat_rekomendasi' => UploadedFile::fake()->create('surat_rekomendasi.pdf', 100, 'application/pdf'),
                'upload_sertifikat_pendukung' => UploadedFile::fake()->create('sertifikat_pendukung.pdf', 100, 'application/pdf'),
            ],
        ];
    }

    private function postInvalid(array $base, array $override = [])
    {
        return $this->from(route('programs.show', Program::find($base['program_id'])->slug))
            ->post(route('pendaftaran.store'), array_replace_recursive($base, $override))
            ->assertRedirect(route('programs.show', Program::find($base['program_id'])->slug));
    }

    private function createQaForm(
        string $slug = 'program-qa-jepang',
        string $status = 'draft',
        bool $includeName = true,
        bool $includeEmail = true,
        bool $includePhone = true,
    ): array {
        $program = Program::create([
            'nama_program' => ['id' => 'Program QA Jepang', 'jp' => 'QA日本プログラム'],
            'slug' => $slug,
            'status' => 'aktif',
            'is_featured' => false,
            'sort_order' => 1,
            'has_schema' => true,
        ]);

        $batch = Batch::create([
            'program_id' => $program->id,
            'nama_batch' => ['id' => 'Batch QA Mei 2026'],
            'status' => 'dibuka',
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addMonth(),
            'cta_type' => 'internal_form',
        ]);

        $schema = ProgramSchema::create([
            'program_id' => $program->id,
            'batch_id' => $batch->id,
            'nama_skema' => ['id' => 'Reguler', 'jp' => '通常'],
            'slug' => $slug . '-reguler',
            'tipe' => 'reguler',
            'status' => 'aktif',
            'sort_order' => 1,
        ]);

        $form = Form::create([
            'program_id' => $program->id,
            'schema_id' => $schema->id,
            'batch_id' => $batch->id,
            'title' => ['id' => 'Form QA Jepang', 'jp' => 'QA応募フォーム'],
            'description' => ['id' => 'Form QA end-to-end'],
            'success_message' => ['id' => 'Terima kasih'],
            'status' => $status,
            'is_active' => $status === 'published',
            'accepts_responses' => $status === 'published',
            'version' => 1,
            'published_at' => $status === 'published' ? now() : null,
        ]);

        if ($includeName) {
            $this->addField($form, 'nama_lengkap', 'Nama Lengkap', 'text', 'applicant_name', required: true);
        }

        if ($includeEmail) {
            $this->addField($form, 'email', 'Email', 'email', 'applicant_email', required: true);
        }

        if ($includePhone) {
            $this->addField($form, 'nomor_whatsapp', 'Nomor WhatsApp', 'phone', 'applicant_phone', required: true);
        }

        $this->addField($form, 'tanggal_lahir', 'Tanggal Lahir', 'date', 'applicant_birth_date');
        $this->addField($form, 'alamat', 'Alamat', 'textarea', 'applicant_address');
        $this->addField($form, 'level_bahasa_jepang', 'Level Bahasa Jepang', 'select', 'none', required: true, options: [
            ['value' => 'N5', 'label' => ['id' => 'N5', 'jp' => 'N5']],
            ['value' => 'N4', 'label' => ['id' => 'N4', 'jp' => 'N4']],
            ['value' => 'N3', 'label' => ['id' => 'N3', 'jp' => 'N3']],
        ]);
        $this->addField($form, 'minat_bidang', 'Minat Bidang', 'checkbox', 'none', options: [
            ['value' => 'Perawat', 'label' => ['id' => 'Perawat']],
            ['value' => 'Pertanian', 'label' => ['id' => 'Pertanian']],
            ['value' => 'Perhotelan', 'label' => ['id' => 'Perhotelan']],
        ]);
        $this->addField($form, 'upload_cv', 'Upload CV', 'file', 'none', acceptedFileTypes: ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']);

        return [$program, $batch, $schema, $form];
    }

    private function addField(
        Form $form,
        string $fieldName,
        string $label,
        string $type,
        string $role = 'none',
        bool $required = false,
        ?array $options = [],
        ?array $acceptedFileTypes = null,
    ): FormField {
        return FormField::create([
            'form_id' => $form->id,
            'program_id' => $form->program_id,
            'schema_id' => $form->schema_id,
            'label' => ['id' => $label, 'jp' => $label],
            'field_name' => $fieldName,
            'type' => $type,
            'field_role' => $role,
            'options' => in_array($type, config('dynamic_forms.choice_field_types', []), true) ? $options : null,
            'accepted_file_types' => $type === 'file' ? ($acceptedFileTypes ?? ['pdf']) : null,
            'max_file_size' => $type === 'file' ? 2048 : null,
            'is_required' => $required,
            'status' => 'aktif',
            'sort_order' => ($form->fields()->max('sort_order') ?? 0) + 1,
        ]);
    }

    private function createLegacyApplicant(Program $program, Batch $batch, string $name, ?Form $form = null): Applicant
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
            'alamat' => 'Jl. Legacy',
            'phone' => '08123456789',
            'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
            'pendidikan' => 'SMA',
            'status_seleksi' => 'baru',
        ]);
    }
}
