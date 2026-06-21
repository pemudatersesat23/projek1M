<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Program;
use App\Models\Batch;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get all necessary data for the dashboard.
     * 
     * @return array
     */
    public function getDashboardData(): array
    {
        $total = Applicant::count();
        $lolos = Applicant::where('status_seleksi', 'lolos')->count();
        $baru = Applicant::where('status_seleksi', 'baru')->count();
        $mingguIni = Applicant::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        return [
            'stats' => [
                'total'        => $total,
                'program'      => Program::count(),
                'batch_aktif'  => Batch::where('status', 'dibuka')->count(),
                'lolos'        => $lolos,
                'baru'         => $baru,
                'minggu_ini'   => $mingguIni,
                'growth_total' => $this->calculateGrowth(),
            ],
            'programStats'      => $this->getProgramStats($total),
            'laporanBulanan'    => $this->getMonthlyReport(),
            'applicantsTerbaru' => $this->getRecentApplicants(),
        ];
    }

    /**
     * Calculate Month-over-Month growth percentage for applicants.
     *
     * @return int
     */
    private function calculateGrowth(): int
    {
        $thisMonth = Applicant::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)->count();
        $lastMonth = Applicant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();

        return $lastMonth > 0 ? (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100) : 0;
    }

    /**
     * Get distribution of applicants per active program.
     * Fixed N+1 issue by using withCount().
     *
     * @param int $total
     * @return array
     */
    private function getProgramStats(int $total): array
    {
        $programStats = [];
        $activePrograms = Program::where('status', 'aktif')->withCount('applicants')->get();
        
        foreach ($activePrograms as $prog) {
            $count = $prog->applicants_count;
            $programStats[] = [
                'nama'   => $prog->nama_program,
                'jumlah' => $count,
                'persen' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
            ];
        }

        return $programStats;
    }

    /**
     * Get monthly application summary for the last 6 months.
     * Menggunakan SQL GROUP BY untuk efisiensi — hanya mengambil data agregat dari DB,
     * bukan load semua baris lalu di-group di PHP.
     *
     * @return array
     */
    private function getMonthlyReport(): array
    {
        $laporanBulanan = [];
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        // Single query dengan SQL GROUP BY — jauh lebih efisien dari load-all-in-PHP
        $monthlyStats = Applicant::selectRaw('
                YEAR(created_at)  AS tahun,
                MONTH(created_at) AS bulan_num,
                COUNT(*)          AS total,
                SUM(CASE WHEN status_seleksi = "lolos" THEN 1 ELSE 0 END) AS lolos
            ')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($r) => $r->tahun . '-' . str_pad($r->bulan_num, 2, '0', STR_PAD_LEFT));

        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            $key  = $date->format('Y-m');

            if ($monthlyStats->has($key)) {
                $stat = $monthlyStats->get($key);
                $laporanBulanan[] = [
                    'bulan' => $date->translatedFormat('F Y'),
                    'total' => (int) $stat->total,
                    'lolos' => (int) $stat->lolos,
                ];
            }
        }

        return $laporanBulanan;
    }

    /**
     * Get recent applicants with eager loaded relationships.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentApplicants()
    {
        return Applicant::with(['program', 'batch'])->latest()->take(5)->get();
    }
}
