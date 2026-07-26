<?php
namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $service) {}

    public function staff()
    {
        $data = $this->service->dataStaff(auth()->id());
        return view('dashboard.staff', $data);
    }

    public function admin()
    {
        $data = $this->service->dataAdmin();
        return view('dashboard.admin', $data);
    }

    public function spv()
    {
        $data = $this->service->dataSpv();
        return view('dashboard.spv', $data);
    }
}
