<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateBatchStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbarui status batch pendaftaran secara otomatis berdasarkan tanggal buka dan tutup';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\Batch\BatchStatusService $service)
    {
        $this->info('Memulai pembaruan status batch...');
        
        $result = $service->updateAllStatuses();
        
        $this->info("Selesai! Berhasil memproses {$result['total_processed']} batch.");
        $this->info("Jumlah batch yang statusnya berubah: {$result['updated']}.");
    }
}
