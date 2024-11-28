<?php

namespace App\Http\Controllers;

use App\Models\ApprovedRequest;
use App\Models\Category;
use App\Models\Requests;
use App\Models\SubRequest;
use App\Models\Supplier;
use App\Models\Suppliersupplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{

    public function getSupplierReport(REQUEST $request)
    {
        $supplierId = $request->input('supplier_id');

        $query = Supplier::query();
        if ($supplierId) {
            $query->where('id', $supplierId);
        }
        $suppliers = $query->get();

        foreach ($suppliers as $supplier) {
            $supplier->total_payed_amount = SubRequest::where(['supplier_id'=> $supplier->id , 'status' => SubRequest::STATUS_APPROVED])->sum('paid_amount');
        
            $supplier->total_amount = SubRequest::where([
                'supplier_id' => $supplier->id,
                'status' => SubRequest::STATUS_APPROVED,
            ])
            ->groupBy('request')
            ->selectRaw('MAX(amount) as amount')
            ->sum('amount');

            $supplier->due_amount = $supplier->total_amount - $supplier->total_payed_amount;
        }

        $supplier_Dropdown = Supplier::all();

        return view('supplier.report' , compact('suppliers' , 'supplier_Dropdown'));
    }


    public function exportReport(Request $request)
    {
        $supplierId = $request->input('supplier_id');

        $query = Supplier::query();
        if ($supplierId) {
            $query->where('id', $supplierId);
        }
        $suppliers = $query->get();

        foreach ($suppliers as $supplier) {
            $supplier->total_payed_amount = SubRequest::where(['supplier_id'=> $supplier->id , 'status' => SubRequest::STATUS_APPROVED])->sum('paid_amount');
        
            $supplier->total_amount = SubRequest::where([
                'supplier_id' => $supplier->id,
                'status' => SubRequest::STATUS_APPROVED,
            ])
            ->groupBy('request')
            ->selectRaw('MAX(amount) as amount')
            ->sum('amount');

            $supplier->due_amount = $supplier->total_amount - $supplier->total_payed_amount;
        }

        $csvHeader = ['ID', 'Company Name', 'Supplier Name', 'Supplier Email', 'Full Amount', 'Due Amount', 'Paid Amount'];

        $csvData = $suppliers->map(function ($supplier) {
            return [
                $supplier->id,
                $supplier->company_name,
                $supplier->supplier_name,  // Assuming you have supplier name in $supplier
                $supplier->email,     // Assuming you have bank name in $supplier
                $supplier->total_amount,
                $supplier->due_amount,
                $supplier->total_payed_amount,
            ];
        });

        $fileContent = fopen('php://memory', 'w');
        fputcsv($fileContent, $csvHeader); // Add header
        foreach ($csvData as $row) {
            fputcsv($fileContent, $row);
        }
        rewind($fileContent);

        return Response::streamDownload(function () use ($fileContent) {
            fpassthru($fileContent);
        }, 'supplier_report.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="supplier_report.csv"',
        ]);
    }

    public function getTransactionReport(REQUEST $request)
    {
        $query = SubRequest::where('status', SubRequest::STATUS_APPROVED);

        if ($request->start_date) {
            $query->where('updated_at', '>=', $request->start_date); // Corrected date comparison operator
        }
    
        if ($request->end_date) {
            $query->where('updated_at', '<=', $request->end_date);
        }

        if($request->supplier_id){
            $query = $query->where('supplier_id' , $request->supplier_id);
        }

        if($request->category){
            $query = $query->where('subcategory' , $request->category);
        }

        $transactions = $query->get();

        foreach($transactions as $transaction){
            $transaction->supplier = Supplier::where('id',$transaction->supplier_id)->pluck('company_name')->first();
            $transaction->created_user = User::where('id', $transaction->created_by)->pluck('name')->first();

            $approvedRequest = ApprovedRequest::where('sub_request',$transaction->id)->first();
            $transaction->voucher  = $approvedRequest->voucher_number;
            $transaction->check_no = $approvedRequest->check_number;
        }

        $suppliers = Supplier::all();
    
        return view('admin.transactionReport', compact('transactions', 'suppliers'));
    }

    public function exportTransactionReport(REQUEST $request)
    {
        $query = SubRequest::where('status', SubRequest::STATUS_APPROVED);

        if ($request->start_date) {
            $query->where('updated_at', '>=', $request->start_date); // Corrected date comparison operator
        }
    
        if ($request->end_date) {
            $query->where('updated_at', '<=', $request->end_date);
        }

        if($request->supplier_id){
            $query = $query->where('supplier_id' , $request->supplier_id);
        }

        if($request->category){
            $query = $query->where('subcategory' , $request->category);
        }

        $transactions = $query->get();

        foreach($transactions as $transaction){
            $transaction->supplier = Supplier::where('id',$transaction->supplier_id)->pluck('company_name')->first();
            $transaction->created_user = User::where('id', $transaction->created_by)->pluck('name')->first();

            $approvedRequest = ApprovedRequest::where('sub_request',$transaction->id)->first();
            $transaction->voucher  = $approvedRequest->voucher_number;
            $transaction->check_no = $approvedRequest->check_number;
        }

            // Define the CSV headers
            $csvHeader = [
                'Request ID',
                'Category',
                'Supplier',
                'Requested By',
                'Amount',
                'Check No.',
                'Voucher',
                'Timestamp',
            ];

            // Map transactions to match the table's structure
            $csvData = $transactions->map(function ($transaction) {
                return [
                    $transaction->id,
                    $transaction->subcategory,
                    $transaction->supplier,
                    $transaction->created_user,
                    $transaction->paid_amount,
                    $transaction->check_no ?? 'Not Given',
                    $transaction->voucher ?? 'Not Given',
                    $transaction->updated_at->format('F j, Y g:i A'),
                ];
            });


        $fileContent = fopen('php://memory', 'w');
        fputcsv($fileContent, $csvHeader); // Add header
        foreach ($csvData as $row) {
            fputcsv($fileContent, $row);
        }
        rewind($fileContent);

        return Response::streamDownload(function () use ($fileContent) {
            fpassthru($fileContent);
        }, 'transaction_report.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transaction_report.csv"',
        ]);
    }
}
