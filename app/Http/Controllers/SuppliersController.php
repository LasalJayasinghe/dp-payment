<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use App\Models\SubRequest;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\SuperglobalsEnv;

class SuppliersController extends Controller
{
    public function createSupplier(REQUEST $request)
    {
        if ($request->isMethod('post')) {
            $supplier = new Supplier();
            $supplier->company_name = $request->companyName;
            $supplier->supplier_name = $request->supplierName;
            $supplier->email = $request->email;
            $supplier->address = $request->address;
            $supplier->save();

            return redirect()->route('supplier.show')->with('success', 'Supplier added successfully!');
        }
        return view('supplier.create');
    }

    public function addAccount(Request $request)
    {
        if ($request->isMethod('post')) {
            $account = new SupplierAccount();
            $account->supplier_id = $request->supplierId;
            $account->account_name = $request->accountName;
            $account->account_number = $request->accountNumber;
            $account->bank_name = $request->bankName;
            $account->branch = $request->branch;
            $account->save();

            return redirect()->route('supplier.show')->with('success', 'Account added successfully!');
        }

        $suppliers = Supplier::all();
        return view('supplier.add_account' , compact('suppliers'));
    }

    public function showSuppliers(Request $request)
    {
        $suppliers = Supplier::all();
        return view('supplier.show' , compact('suppliers'));
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            SupplierAccount::where('supplier_id' , $id)->delete();
            $supplier->delete();
    
            return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete category']);
        }
    }


        public function getAccounts($supplierId)
        {
            $accounts = SupplierAccount::where('supplier_id', $supplierId)->get();
            return response()->json($accounts);
        }

    public function getSupplierReport(REQUEST $request)
    {
        $supplierId = $request->input('supplier_id');

        $accountsQuery = SupplierAccount::orderBy('supplier_id');
        if ($supplierId) {
            $accountsQuery->where('supplier_id', $supplierId);
        }
        $accounts = $accountsQuery->get();

        foreach ($accounts as $account) {
            $subRequestIds = SubRequest::where('account', $account->id)->pluck('id');

            $account->total_payed_amount = Transaction::where('status', Transaction::TRANSACTION_SUCCESS)
            ->whereIn('sub_request', $subRequestIds) 
            ->sum('amount');
        
            $account->total_amount = Requests::where('status', 'approved')
                ->where('account_id', $account->id) // Filter by supplier_id
                ->sum('amount');
        
            $account->due_amount = $account->total_amount - $account->total_payed_amount;
            $account->supplier = Supplier::where('id',$account->supplier_id)->pluck('company_name')->first();
        }

        return view('supplier.report' , compact('accounts'));
    }

    public function getSuppliers()
    {

        $suppliers = Supplier::select('id', 'company_name')->orderBy('company_name')->get();
        return response()->json($suppliers);
    }


}
