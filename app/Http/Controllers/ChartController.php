<?php

namespace App\Http\Controllers;

use App\Models\SubRequest;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function getChartData()
    {
        // Fetch monthly expenses (for the bar chart)
        $monthlyExpenses = SubRequest::selectRaw('SUM(paid_amount) as total, MONTH(created_at) as month, created_by')
        ->where('status' , SubRequest::STATUS_APPROVED)
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('created_by', 'month')
        ->orderBy('created_by')
        ->orderBy('month')                        
        ->get();

        $suppliers = Supplier::select('company_name', 'id')->get();
        foreach($suppliers as $supplier)
        {
            $supplier->total = 0;

            $accounts = SupplierAccount::where('supplier_id', $supplier->id)->get();
            foreach($accounts as $account)
            {
                $subRequestIds = SubRequest::where('account', $account->id)->pluck('id');

                $total_payed_amount = SubRequest::where('status' , SubRequest::STATUS_APPROVED)
                ->whereIn('id', $subRequestIds) 
                ->sum('paid_amount');

                $supplier->total += $total_payed_amount;
            }
        }

        $suppliers = $suppliers->sortByDesc('total')->take(10);

        return response()->json([
            'monthlyExpenses' => $monthlyExpenses,
            'suppliers' => $suppliers,
        ]);
    }}
