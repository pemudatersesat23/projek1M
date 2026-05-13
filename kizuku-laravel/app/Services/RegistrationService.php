<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\ApplicantDynamicFile;
use App\Models\ApplicantFormAnswer;
use App\Models\Form;
use App\Services\DynamicForm\ApplicantIdentityMapper;
use App\Services\DynamicForm\DynamicFileUploadService;
use App\Services\DynamicForm\DynamicValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    public function __construct(
        private readonly DynamicValidationService $validator,
        private readonly DynamicFileUploadService $uploader,
        private readonly DynamicFormService $forms,
        private readonly ApplicantIdentityMapper $identityMapper,
    ) {
    }

    public function register(Request $request): Applicant
    {
        $form = Form::findOrFail($request->input('form_id'));
        $activeFields = $this->forms->getFieldsForForm($form);

        $this->validator->validateDynamicPayload($request, $activeFields);

        $uploadedPaths = [];

        try {
            return DB::transaction(function () use ($request, $form, $activeFields, &$uploadedPaths) {
                $dynamicAnswers = $request->input('dynamic_answers', []);
                $identityData = $this->identityMapper->map($activeFields, $dynamicAnswers);

                $applicant = Applicant::create(array_merge($identityData, [
                    'program_id' => $request->input('program_id'),
                    'batch_id' => $request->input('batch_id'),
                    'schema_id' => $request->input('schema_id'),
                    'form_id' => $form->id,
                    'form_version_snapshot' => $form->version,
                    'form_title_snapshot' => $form->getTranslations('title'),
                    'status_seleksi' => 'baru',
                ]));

                foreach ($activeFields->filter(fn ($field) => ! $field->isFile()) as $field) {
                    $rawValue = $dynamicAnswers[$field->field_name] ?? null;
                    if ($rawValue === null && ! $field->is_required) {
                        continue;
                    }

                    ApplicantFormAnswer::create([
                        'applicant_id' => $applicant->id,
                        'form_field_id' => $field->id,
                        'value' => is_array($rawValue) ? $rawValue : (string) $rawValue,
                        'field_label_snapshot' => $field->getTranslations('label'),
                        'field_type_snapshot' => $field->type,
                    ]);
                }

                foreach ($activeFields->filter(fn ($field) => $field->isFile()) as $field) {
                    $fileKey = "dynamic_files.{$field->field_name}";
                    if (! $request->hasFile($fileKey)) {
                        continue;
                    }

                    $meta = $this->uploader->upload($request->file($fileKey), $applicant->id, $field->id);
                    $uploadedPaths[] = $meta['path'];

                    ApplicantDynamicFile::create([
                        'applicant_id' => $applicant->id,
                        'form_field_id' => $field->id,
                        'file_path' => $meta['path'],
                        'original_name' => $meta['original_name'],
                        'mime_type' => $meta['mime_type'],
                        'size' => $meta['size'],
                        'field_label_snapshot' => $field->getTranslations('label'),
                        'field_type_snapshot' => $field->type,
                    ]);
                }

                return $applicant;
            });
        } catch (\Throwable $e) {
            if (! empty($uploadedPaths)) {
                $this->uploader->deleteMany($uploadedPaths);
            }

            throw $e;
        }
    }
}
