<?php

namespace App\Services\DynamicForm;

use Illuminate\Support\Collection;

class ApplicantIdentityMapper
{
    /**
     * Map dynamic answers to legacy applicant columns based on field roles.
     *
     * @param Collection $fields Collection of FormField models
     * @param array $answers Keyed by field_name
     * @return array Keyed by applicant column names
     */
    public function map(Collection $fields, array $answers): array
    {
        $mapped = [];

        // Role to column mapping
        $roleMap = [
            'applicant_name'       => 'nama',
            'applicant_email'      => 'email',
            'applicant_phone'      => 'phone',
            'applicant_birth_date' => 'tanggal_lahir',
            'applicant_gender'     => 'jenis_kelamin',
            'applicant_pob'        => 'tempat_lahir',
            'applicant_address'    => 'alamat',
            'applicant_education'  => 'pendidikan',
        ];

        foreach ($fields as $field) {
            $role = $field->field_role;
            $value = $answers[$field->field_name] ?? null;

            if ($value !== null) {
                // 1. By Explicit Role
                if ($role && isset($roleMap[$role])) {
                    $column = $roleMap[$role];
                    $mapped[$column] = $value;
                } else {
                    // 2. Fallback: Guess by label or field_name (case-insensitive)
                    $labelStr = strtolower($field->getTranslation('label', 'id') . ' ' . $field->field_name);
                    
                    if (empty($mapped['jenis_kelamin']) && (str_contains($labelStr, 'kelamin') || str_contains($labelStr, 'gender'))) {
                        // Map option value back to readable text if possible, though mapper usually deals with raw value
                        // But since we want "Laki-laki" instead of "option_1", we try to parse it.
                        // Actually, ApplicantIdentityMapper runs BEFORE ApplicantFormAnswer is saved, 
                        // so we can resolve the option label here.
                        $mapped['jenis_kelamin'] = $this->resolveOptionLabel($field, $value);
                    }
                    elseif (empty($mapped['tempat_lahir']) && (str_contains($labelStr, 'tempat') || str_contains($labelStr, 'pob'))) {
                        $mapped['tempat_lahir'] = $value;
                    }
                    elseif (empty($mapped['alamat']) && (str_contains($labelStr, 'alamat') || str_contains($labelStr, 'domisili') || str_contains($labelStr, 'address'))) {
                        $mapped['alamat'] = $value;
                    }
                    elseif (empty($mapped['pendidikan']) && (str_contains($labelStr, 'pendidikan') || str_contains($labelStr, 'education') || str_contains($labelStr, 'terakhir'))) {
                        $mapped['pendidikan'] = $this->resolveOptionLabel($field, $value);
                    }
                }
            }
        }

        return $mapped;
    }

    private function resolveOptionLabel($field, $value)
    {
        if (!$value) return $value;
        $opts = is_array($field->options) ? $field->options : json_decode($field->options, true);
        if (is_array($opts)) {
            foreach ($opts as $opt) {
                if (isset($opt['value']) && $opt['value'] == $value) {
                    return $opt['label']['id'] ?? ($opt['label']['jp'] ?? $value);
                }
            }
        }
        return $value;
    }
}
