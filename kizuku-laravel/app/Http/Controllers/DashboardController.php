<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Program;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Applicant::count();
        $lolos = Applicant::where('status_seleksi', 'lolos')->count();
        $baru = Applicant::where('status_seleksi', 'baru')->count();
        $mingguIni = Applicant::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        // Growth calculation
        $thisMonth = Applicant::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)->count();
        $lastMonth = Applicant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();
        $growthTotal = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : 0;

        // Program distribution
        $programStats = [];
        $activePrograms = Program::where('status', 'aktif')->get();
        
        foreach ($activePrograms as $prog) {
            $count = Applicant::where('program_id', $prog->id)->count();
            $programStats[] = [
                'nama'   => $prog->nama_program,
                'jumlah' => $count,
                'persen'  => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        // Monthly report
        $laporanBulanan = [];
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            $monthTotal = Applicant::whereMonth('created_at', $date->month)
                                ->whereYear('created_at', $date->year)->count();
            if ($monthTotal > 0) {
                $laporanBulanan[] = [
                    'bulan'   => $date->translatedFormat('F Y'),
                    'total'   => $monthTotal,
                    'lolos'   => Applicant::where('status_seleksi', 'lolos')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
                ];
            }
        }

        // Recent applicants
        $applicantsTerbaru = Applicant::with(['program', 'batch'])->latest()->take(5)->get();

        return view('admin.dashboard', [
            'stats' => [
                'total'        => $total,
                'program'      => Program::count(),
                'batch_aktif'  => \App\Models\Batch::where('status', 'dibuka')->count(),
                'lolos'        => $lolos,
                'baru'         => $baru,
                'minggu_ini'   => $mingguIni,
                'growth_total' => $growthTotal,
            ],
            'programStats'   => $programStats,
            'laporanBulanan' => $laporanBulanan,
            'applicantsTerbaru' => $applicantsTerbaru,
        ]);
    }
}
