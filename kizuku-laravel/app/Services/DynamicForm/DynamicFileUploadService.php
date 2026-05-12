<?php

namespace App\Services\DynamicForm;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DynamicFileUploadService
{
    /**
     * Upload a single dynamic file to private local storage.
     *
     * @param  UploadedFile  $file
     * @param  int           $applicantId
     * @param  int           $fieldId
     * @return array{path: string, original_name: string, mime_type: string, size: int}
     */
    public function upload(UploadedFile $file, int $applicantId, int $fieldId): array
    {
        $this->guardExtension($file);

        $ext          = strtolower($file->getClientOriginalExtension());
        $hash         = Str::random(32);
        $storedName   = "dynamic_{$fieldId}_{$hash}.{$ext}";
        $directory    = "private/registrations/{$applicantId}";

        $path = Storage::disk('local')->putFileAs($directory, $file, $storedName);

        if (!$path) {
            throw new \RuntimeException("Gagal menyimpan file: {$file->getClientOriginalName()}");
        }

        return [
            'path'          => $path,
            'original_name' => $this->sanitizeName($file->getClientOriginalName()),
            'mime_type'     => $file->getMimeType() ?? 'application/octet-stream',
            'size'          => (int) ceil($file->getSize() / 1024), // KB
        ];
    }

    /**
     * Delete a file from private local storage.
     * Silent on missing file — cleanup must never throw.
     */
    public function delete(string $path): void
    {
        try {
            Storage::disk('local')->delete($path);
        } catch (\Throwable) {
            // Silently fail; don't let cleanup throw and mask original error
        }
    }

    /**
     * Delete multiple files. Used for transaction rollback cleanup.
     *
     * @param  string[]  $paths
     */
    public function deleteMany(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function guardExtension(UploadedFile $file): void
    {
        $blocked = config('dynamic_forms.blocked_file_extensions', []);
        $ext     = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, $blocked)) {
            throw new \InvalidArgumentException(
                "Ekstensi file '{$ext}' tidak diizinkan."
            );
        }
    }

    private function sanitizeName(string $name): string
    {
        // Strip any path traversal and keep printable ASCII
        $name = basename($name);
        $name = preg_replace('/[^\w.\-]/', '_', $name);
        return mb_substr($name, 0, 200);
    }
}
