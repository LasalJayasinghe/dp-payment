<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\CashAccountFeedLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CashAccountController extends Controller
{
    function index(): View
    {

    }

    function create(Request $request): View | RedirectResponse
    {
        $data = $request->validate([
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            $cash_accounts = CashAccount::all();
            foreach ($cash_accounts as $cash_account) {
                $cash_account->status = CashAccount::INACTIVE;
                $cash_account->updateOrFail();
            }

            $cash_account = new CashAccount();
            $cash_account->name = $data['account_name'] ?? null;
            $cash_account->account_number = $data['account_number'] ?? null;
            $cash_account->amount = $data['amount'] ?? 0.00;
            $cash_account->saveOrFail();

            $cash_account_log = new CashAccountFeedLog();
            $cash_account_log->cash_account = $cash_account->id;
            $cash_account_log->amount = $data['amount'] ?? 0.00;
            $cash_account_log->saveOrFail();
            DB::commit();
            return redirect()->back(301)->with('success', 'Cash Account Created Successfully');
        }catch (\Exception $ex){
            DB::rollBack();
            Log::error("cash account create ex : ". $ex->getMessage());
            return redirect()->back(301)->with('error', 'OOPS! Something went wrong.');
        }
    }

    function credit(Request $request, int $id): View | RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $cash_account = CashAccount::query()->where('status', CashAccount::ACTIVE)->findOrFail($id);
            $cash_account->amount = ($cash_account->amount + $data['amount']);
            $cash_account->updateOrFail();

            $cash_account_log = new CashAccountFeedLog();
            $cash_account_log->cash_account = $cash_account->id;
            $cash_account_log->amount = $data['amount'] ?? 0.00;
            $cash_account_log->saveOrFail();

            DB::commit();
            return redirect()->back(301)->with('success', 'Cash Account Credit Successfully');
        }catch (\Exception $ex){
            DB::rollBack();
            Log::error("cash account credit ex : ". $ex->getMessage());
            return redirect()->back(301)->with('error', 'OOPS! Something went wrong.');
        }
    }

    function remove(int $id): RedirectResponse
    {
        try {
            $cash_account = CashAccount::query()->findOrFail($id);
            $cash_account->deleteOrFail();
            return redirect()->back(301)->with('success', 'Cash Account Removed Successfully');
        }catch (\Exception $ex){
            Log::error("cash account remove ex : ". $ex->getMessage());
            return redirect()->back(301)->with('error', 'OOPS! Something went wrong.');
        }
    }
}
