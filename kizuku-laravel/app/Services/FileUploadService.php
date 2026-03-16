<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Upload multiple files and return their paths.
     *
     * @param array $files associative array of fields and their UploadedFile objects
     * @param string $folder folder to store files
     * @return array
     */
    public function uploadMultiple(array $files, string $folder = 'documents'): array
    {
        $paths = [];
        
        foreach ($files as $field => $file) {
            if ($file instanceof UploadedFile) {
                Log::info("Service: Uploading $field to $folder");
                $paths[$field] = $file->store($folder, 'public');
            }
        }
        
        return $paths;
    }

    /**
     * Delete files from storage.
     *
     * @param array $paths
     * @return void
     */
    public function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
