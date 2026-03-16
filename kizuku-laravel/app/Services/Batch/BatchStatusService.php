<?php

namespace App\Services\Batch;

use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BatchStatusService
{
    /**
     * Update statuses for all batches based on today's date.
     *
     * @return array Summary of updates
     */
    public function updateAllStatuses(): array
    {
        $today = Carbon::today();
        $batches = Batch::all();
        $updatedCount = 0;

        foreach ($batches as $batch) {
            $newStatus = $this->determineStatus($batch, $today);

            if ($batch->status !== $newStatus) {
                Log::info("Updating Batch ID {$batch->id} status from {$batch->status} to {$newStatus}");
                $batch->update(['status' => $newStatus]);
                $updatedCount++;
            }
        }

        return [
            'total_processed' => $batches->count(),
            'updated' => $updatedCount
        ];
    }

    /**
     * Determine what the status should be based on dates.
     *
     * @param Batch $batch
     * @param Carbon $today
     * @return string
     */
    public function determineStatus(Batch $batch, Carbon $today): string
    {
        // Skip manual statuses like 'berjalan' or 'selesai' if they are already set 
        // to avoid overriding administrative overrides, UNLESS you want it fully automated.
        // For this requirement, we focus on pendaftaran logic:
        
        if (!$batch->tanggal_buka || !$batch->tanggal_tutup) {
            return $batch->status;
        }

        if ($today->lt($batch->tanggal_buka)) {
            return 'akan_dibuka';
        }

        if ($today->between($batch->tanggal_buka, $batch->tanggal_tutup)) {
            return 'dibuka';
        }

        if ($today->gt($batch->tanggal_tutup)) {
            return 'ditutup';
        }

        return $batch->status;
    }
}
