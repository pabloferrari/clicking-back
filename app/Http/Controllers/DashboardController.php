<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Classes\DashboardService;
use Log;

class DashboardController extends Controller
{
    public $dashboardService;

    public function __construct(DashboardService $dashboardService) {
        $this->dashboardService = $dashboardService;
    }

    
    public function index(Request $request) {
        
        $user = Auth::user();
        if($user->hasRole('student')) {
            return response()->json([ 'data' => $this->dashboardService->studentDashboard($user)]);
        } else if ($user->hasRole('treacher')) {
            return response()->json([ 'data' => $this->dashboardService->teacherDashboard($user)]);
        } else if ($user->hasRole('institution')) {
            return response()->json([ 'data' => $this->dashboardService->institutionDashboard($user)]);
        } else if ($user->hasRole('admin') || $user->hasRole('root')) {
            return response()->json([ 'data' => $this->dashboardService->adminDashboard($user)]);
        } else {
            return response()->json(["message" => "Invalid role"], 403);
        }
    }

}
