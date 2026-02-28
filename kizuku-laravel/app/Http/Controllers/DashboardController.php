<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Siswa::count();
        $lunas = Siswa::where('payment_status', 'Lunas')->count();
        $pending = $total - $lunas;
        $mingguIni = Siswa::where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        // Growth calculation (compare this month vs last month)
        $thisMonth = Siswa::whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year)->count();
        $lastMonth = Siswa::whereMonth('created_at', Carbon::now()->subMonth()->month)
                          ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();
        $growthTotal = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : 0;

        // Program distribution
        $programs = ['Engineering', 'Tokutei Ginou (TG)', 'Kelas Bahasa Jepang', 'Returnee / Ex Jepang'];
        $programStats = [];
        foreach ($programs as $prog) {
            $count = Siswa::where('program', $prog)->count();
            $programStats[] = [
                'nama'   => $prog,
                'jumlah' => $count,
                'persen'  => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        // Monthly report (last 6 months)
        $laporanBulanan = [];
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            $monthTotal = Siswa::whereMonth('created_at', $date->month)
                               ->whereYear('created_at', $date->year)->count();
            $monthLunas = Siswa::where('payment_status', 'Lunas')
                               ->whereMonth('created_at', $date->month)
                               ->whereYear('created_at', $date->year)->count();
            if ($monthTotal > 0) {
                $laporanBulanan[] = [
                    'bulan'   => $date->translatedFormat('F Y'),
                    'total'   => $monthTotal,
                    'lunas'   => $monthLunas,
                    'pending' => $monthTotal - $monthLunas,
                ];
            }
        }

        // Recent registrants
        $siswasTerbaru = Siswa::latest()->take(5)->get();

        return view('admin.dashboard', [
            'stats' => [
                'total'        => $total,
                'lunas'        => $lunas,
                'pending'      => $pending,
                'minggu_ini'   => $mingguIni,
                'growth_total' => $growthTotal,
            ],
            'programStats'   => $programStats,
            'laporanBulanan' => $laporanBulanan,
            'siswasTerbaru'  => $siswasTerbaru,
        ]);
    }
}
