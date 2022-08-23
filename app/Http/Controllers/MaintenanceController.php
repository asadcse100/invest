<?php

namespace App\Http\Controllers;

use App\Helpers\MsgState;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(MaintenanceService $maintenanceService)
    {
        if ($maintenanceService->hasMaintenance()) {
            $title = __("Under Maintenance");
            $heading = __("Our website is temporarily offline!");
            $message = $maintenanceService->getNotice();
            $support = MsgState::helps('default');

            return view('errors.offline', compact('message', 'title', 'heading', 'support'));
        }
        return redirect('/');
    }
}
