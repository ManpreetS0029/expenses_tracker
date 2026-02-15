<?php

namespace App\Http\Controllers;

use App\Services\DashboardDataService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardDataService $dashboardData): View
    {
        $periodFilter = $request->get('period', 'this_month');
        $userId = $request->user()->id;
        $viewData = $dashboardData->getData($userId, $periodFilter);

        return view('pages.dashboard', $viewData);
    }
}
