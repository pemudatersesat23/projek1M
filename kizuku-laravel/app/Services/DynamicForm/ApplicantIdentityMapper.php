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
            if ($role && isset($roleMap[$role])) {
                $column = $roleMap[$role];
                $value = $answers[$field->field_name] ?? null;

                if ($value !== null) {
                    $mapped[$column] = $value;
                }
            }
        }

        return $mapped;
    }
}
