<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use App\Models\Supplier;
use App\Models\SupplierAccount;
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
        Log::info("request" , [$request]);
        $query = Requests::query();

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', 'like', '%' . $request->supplier_id . '%');
        }
    
        $requests = $query->paginate(10);

        foreach($requests as $request)
        {
            $request->supplier = Supplier::where('id' , $request->supplier_id)->pluck('company_name')->first();
            $request->account =  SupplierAccount::where('id' , $request->account_id)->pluck('account_name')->first();

        }

        return view('supplier.report' , compact('requests'));
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::select('id', 'company_name')->orderBy('company_name')->get();
        return response()->json($suppliers);
    }


}
