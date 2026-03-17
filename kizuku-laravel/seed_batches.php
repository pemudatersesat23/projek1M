<?php

use App\Models\Program;
use App\Models\Batch;
use Carbon\Carbon;

// Make sure we have programs
$programs = Program::all();

if ($programs->isEmpty()) {
    echo "No programs found. Please add programs first.\n";
    exit;
}

foreach ($programs as $program) {
    // Check if it already has a "Batch 1"
    $exists = Batch::where('program_id', $program->id)
                  ->where('nama_batch', 'Batch 1')
                  ->exists();
    
    if (!$exists) {
        Batch::create([
            'program_id' => $program->id,
            'nama_batch' => 'Batch 1',
            'status' => 'dibuka',
            'tanggal_buka' => Carbon::now()->subDays(5),
            'tanggal_tutup' => Carbon::now()->addDays(14),
            'tanggal_mulai' => Carbon::now()->addDays(20),
            'tanggal_selesai' => Carbon::now()->addMonths(6),
            'tanggal_estimasi_selesai' => Carbon::now()->addMonths(6),
            'kuota' => 20,
            'cta_type' => 'internal_form'
        ]);
        echo "Created Batch 1 for program: " . $program->nama_program . "\n";
    } else {
        // If it exists but might be closed, open it as requested
        Batch::where('program_id', $program->id)
             ->where('nama_batch', 'Batch 1')
             ->update(['status' => 'dibuka']);
        echo "Updated Batch 1 to 'dibuka' for: " . $program->nama_program . "\n";
    }
}
