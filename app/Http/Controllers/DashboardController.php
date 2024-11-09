<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        // $reportData = $this->getReportData(); // Custom method to fetch data
        // $monthlyApprovedData = $this->getMonthlyApprovedData(); // Custom method to fetch monthly data
        // $requestStatusCounts = $this->getRequestStatusCounts(); // Custom method to fetch request counts
    
        return view('dashboard');
    }
}
