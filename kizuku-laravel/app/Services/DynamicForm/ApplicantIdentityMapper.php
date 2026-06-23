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
                // Resolve option label if it has options (like select, radio, checkbox)
                $resolvedValue = $value;
                if (in_array($field->type, ['radio', 'select', 'checkbox'])) {
                    $resolvedValue = $this->resolveOptionLabel($field, $value);
                }

                // 1. By Explicit Role
                if ($role && isset($roleMap[$role])) {
                    $column = $roleMap[$role];
                    $mapped[$column] = $resolvedValue;
                } else {
                    // 2. Fallback: Guess by label or field_name (case-insensitive)
                    $labelStr = strtolower($field->getTranslation('label', 'id') . ' ' . $field->field_name);
                    
                    if (empty($mapped['nama']) && (str_contains($labelStr, 'nama') || str_contains($labelStr, 'name') || str_contains($labelStr, 'lengkap'))) {
                        $mapped['nama'] = $resolvedValue;
                    }
                    elseif (empty($mapped['email']) && str_contains($labelStr, 'email')) {
                        $mapped['email'] = $resolvedValue;
                    }
                    elseif (empty($mapped['phone']) && (str_contains($labelStr, 'no') || str_contains($labelStr, 'nomor') || str_contains($labelStr, 'hp') || str_contains($labelStr, 'wa') || str_contains($labelStr, 'telepon') || str_contains($labelStr, 'phone'))) {
                        $mapped['phone'] = $resolvedValue;
                    }
                    elseif (empty($mapped['tanggal_lahir']) && !str_contains($labelStr, 'tempat') && (str_contains($labelStr, 'tanggal') || str_contains($labelStr, 'tgl') || str_contains($labelStr, 'lahir') || str_contains($labelStr, 'dob') || str_contains($labelStr, 'birth'))) {
                        $mapped['tanggal_lahir'] = $resolvedValue;
                    }
                    elseif (empty($mapped['jenis_kelamin']) && (str_contains($labelStr, 'kelamin') || str_contains($labelStr, 'gender') || str_contains($labelStr, 'sex'))) {
                        $mapped['jenis_kelamin'] = $resolvedValue;
                    }
                    elseif (empty($mapped['tempat_lahir']) && !str_contains($labelStr, 'tanggal') && !str_contains($labelStr, 'tgl') && (str_contains($labelStr, 'tempat') || str_contains($labelStr, 'pob'))) {
                        $mapped['tempat_lahir'] = $resolvedValue;
                    }
                    elseif (empty($mapped['alamat']) && (str_contains($labelStr, 'alamat') || str_contains($labelStr, 'domisili') || str_contains($labelStr, 'address'))) {
                        $mapped['alamat'] = $resolvedValue;
                    }
                    elseif (empty($mapped['pendidikan']) && (str_contains($labelStr, 'pendidikan') || str_contains($labelStr, 'education') || str_contains($labelStr, 'terakhir') || str_contains($labelStr, 'lulusan'))) {
                        $mapped['pendidikan'] = $resolvedValue;
                    }
                }
            }
        }

        // Final fallback if name/email/phone is still empty
        if (empty($mapped['nama'])) {
            foreach ($fields as $field) {
                if ($field->type === 'text' && !empty($answers[$field->field_name])) {
                    $mapped['nama'] = $answers[$field->field_name];
                    break;
                }
            }
            if (empty($mapped['nama'])) {
                $mapped['nama'] = 'Pendaftar Baru';
            }
        }
        if (empty($mapped['email'])) {
            $mapped['email'] = '-';
        }
        if (empty($mapped['phone'])) {
            $mapped['phone'] = '-';
        }

        if (isset($mapped['jenis_kelamin'])) {
            $genderStr = strtolower(trim($mapped['jenis_kelamin']));
            if (str_starts_with($genderStr, 'l') || str_contains($genderStr, 'laki')) {
                $mapped['jenis_kelamin'] = 'L';
            } elseif (str_starts_with($genderStr, 'p') || str_contains($genderStr, 'perempuan')) {
                $mapped['jenis_kelamin'] = 'P';
            } else {
                $mapped['jenis_kelamin'] = null;
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
