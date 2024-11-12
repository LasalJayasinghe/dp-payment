<?php

namespace App\Http\Controllers;

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

}
