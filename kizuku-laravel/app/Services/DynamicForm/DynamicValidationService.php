<?php

namespace App\Services\DynamicForm;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DynamicValidationService
{
    /**
     * Build validation rules for dynamic_answers fields.
     */
    public function buildAnswerRules(Collection $fields): array
    {
        $rules = [];

        foreach ($fields->filter(fn($f) => !$f->isFile()) as $field) {
            $key = 'dynamic_answers.' . $field->field_name;
            $req = $field->is_required ? 'required' : 'nullable';

            switch ($field->type) {
                case 'text':
                case 'phone':
                    $rules[$key] = [$req, 'string', 'max:255'];
                    break;

                case 'textarea':
                    $rules[$key] = [$req, 'string', 'max:2000'];
                    break;

                case 'email':
                    $rules[$key] = [$req, 'email', 'max:255'];
                    break;

                case 'number':
                    $rules[$key] = [$req, 'numeric'];
                    break;

                case 'date':
                    $rules[$key] = [$req, 'date'];
                    break;

                case 'select':
                case 'radio':
                    $validValues = $field->getOptionValues();
                    $rules[$key] = [$req, 'string', 'in:' . implode(',', $validValues)];
                    break;

                case 'checkbox':
                    $rules[$key]        = [$req === 'required' ? 'required' : 'nullable', 'array'];
                    $validValues        = $field->getOptionValues();
                    $rules[$key . '.*'] = ['string', 'in:' . implode(',', $validValues)];
                    break;
            }
        }

        return $rules;
    }

    /**
     * Build validation rules for dynamic_files fields.
     */
    public function buildFileRules(Collection $fields): array
    {
        $rules   = [];
        $blocked = config('dynamic_forms.blocked_file_extensions', []);
        $defaultExt  = config('dynamic_forms.default_allowed_file_extensions', ['pdf', 'jpg', 'jpeg', 'png']);
        $defaultSize = config('dynamic_forms.default_max_file_size', 2048);

        foreach ($fields->filter(fn($f) => $f->isFile()) as $field) {
            $key = 'dynamic_files.' . $field->field_name;
            $req = $field->is_required ? 'required' : 'nullable';

            $exts    = $field->accepted_file_types ?? $defaultExt;
            // Strip blocked extensions
            $exts    = array_filter($exts, fn($e) => !in_array(strtolower($e), $blocked));
            $mimes   = implode(',', array_filter($exts));
            $maxKb   = $field->max_file_size ?? $defaultSize;

            $rules[$key] = [$req, 'file', 'mimes:' . $mimes, 'max:' . $maxKb];
        }

        return $rules;
    }

    /**
     * Reject any unknown keys submitted in dynamic_answers or dynamic_files.
     * Throws a ValidationException if rogue keys are found.
     */
    public function validateUnknownFields(Request $request, Collection $fields): void
    {
        $validAnswerKeys = $fields->filter(fn($f) => !$f->isFile())->pluck('field_name')->all();
        $validFileKeys   = $fields->filter(fn($f) =>  $f->isFile())->pluck('field_name')->all();

        $submittedAnswers = array_keys((array) $request->input('dynamic_answers', []));
        $submittedFiles   = array_keys((array) $request->file('dynamic_files', []));

        $rogueAnswers = array_diff($submittedAnswers, $validAnswerKeys);
        $rogueFiles   = array_diff($submittedFiles,   $validFileKeys);

        $errors = [];
        foreach ($rogueAnswers as $rogue) {
            $errors["dynamic_answers.{$rogue}"] = ['Terdapat field tidak valid pada formulir.'];
        }
        foreach ($rogueFiles as $rogue) {
            $errors["dynamic_files.{$rogue}"] = ['Terdapat field tidak valid pada formulir.'];
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Run all dynamic validation (unknown fields + answer rules + file rules).
     * Returns merged validated data array.
     * Throws ValidationException on failure.
     */
    public function validateDynamicPayload(Request $request, Collection $fields): array
    {
        // 1. Block unknown field injection
        $this->validateUnknownFields($request, $fields);

        // 2. Build rules
        $answerRules = $this->buildAnswerRules($fields);
        $fileRules   = $this->buildFileRules($fields);
        $allRules    = array_merge($answerRules, $fileRules);

        if (empty($allRules)) {
            return [];
        }

        // 3. Merge request data (answers + files) for validator
        $data = array_merge(
            ['dynamic_answers' => $request->input('dynamic_answers', [])],
            ['dynamic_files'   => $request->file('dynamic_files', [])]
        );

        $validator = Validator::make($data, $allRules, $this->messages(), $this->attributes($fields));

        if ($validator->fails()) {
            throw new ValidationException($validator, back()->withErrors($validator)->withInput());
        }

        return $validator->validated();
    }

    // ── Custom messages ───────────────────────────────────────────────────────

    private function messages(): array
    {
        return [
            'dynamic_answers.*.required'    => 'Kolom :attribute wajib diisi.',
            'dynamic_answers.*.string'      => 'Kolom :attribute harus berupa teks.',
            'dynamic_answers.*.max'         => 'Kolom :attribute terlalu panjang (maks :max karakter).',
            'dynamic_answers.*.email'       => 'Kolom :attribute harus berupa alamat email yang valid.',
            'dynamic_answers.*.numeric'     => 'Kolom :attribute harus berupa angka.',
            'dynamic_answers.*.date'        => 'Kolom :attribute harus berupa tanggal yang valid.',
            'dynamic_answers.*.in'          => 'Nilai yang dipilih pada :attribute tidak valid.',
            'dynamic_answers.*.array'       => 'Kolom :attribute harus berupa pilihan.',
            'dynamic_answers.*.*.in'        => 'Salah satu pilihan :attribute tidak valid.',
            'dynamic_files.*.required'      => 'Dokumen :attribute wajib diunggah.',
            'dynamic_files.*.file'          => ':attribute harus berupa file.',
            'dynamic_files.*.mimes'         => 'Format :attribute tidak diizinkan. Gunakan: :values.',
            'dynamic_files.*.max'           => 'Ukuran :attribute melebihi batas maksimum.',
        ];
    }

    private function attributes(Collection $fields): array
    {
        $attrs = [];
        foreach ($fields as $field) {
            $label = $field->getLabelForLocale('id');
            if ($field->isFile()) {
                $attrs['dynamic_files.' . $field->field_name] = $label;
            } else {
                $attrs['dynamic_answers.' . $field->field_name] = $label;
            }
        }
        return $attrs;
    }
}
