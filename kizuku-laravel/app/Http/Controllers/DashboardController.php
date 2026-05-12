<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     * Semua logic dan query database telah dipindahkan ke DashboardService
     * untuk mencegah N+1 query problem dan membuat controller tipis.
     * 
     * @param DashboardService $dashboardService
     * @return \Illuminate\View\View
     */
    public function index(DashboardService $dashboardService)
    {
        $data = $dashboardService->getDashboardData();

        return view('admin.dashboard', $data);
    }
}
