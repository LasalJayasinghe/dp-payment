<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\CashAccountFeedLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CashAccountController extends Controller
{
    function index(): View
    {
        $cash_accounts = CashAccount::query()->orderByDesc('id')->paginate(10);
        return view('cash-accounts.index', compact('cash_accounts'));
    }

    function detail(int $id): View
    {
        $cash_account = CashAccount::query()->findOrFail($id);
        return view('cash-accounts.details', compact('cash_account'));
    }

    function create(Request $request): View | RedirectResponse
    {
        $data = $request->validate([
            'account_name' => 'nullable|string',
            'account_number' => 'required|numeric',
            'amount' => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            $cash_account = CashAccount::query()->latest()->first();
            if ($cash_account){
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

    function credit(Request $request): View | RedirectResponse
    {
        $data = $request->validate([
            'account' => "required|numeric",
            'amount' => 'required|numeric',
            'remark' => 'required|string',
        ]);

        if ($data['amount'] <= 0){
            return redirect()->back(301)->with('error', 'Amount must be greater than 0.');
        }

        DB::beginTransaction();
        try {
            $cash_account = CashAccount::query()->where('status', CashAccount::ACTIVE)->findOrFail($data['account']);
            $cash_account->amount = ($cash_account->amount + $data['amount']);
            $cash_account->updateOrFail();

            $cash_account_log = new CashAccountFeedLog();
            $cash_account_log->cash_account = $cash_account->id;
            $cash_account_log->amount = $data['amount'] ?? 0.00;
            $cash_account_log->remark = $data['remark'] ?? null;
            $cash_account_log->saveOrFail();

            DB::commit();
            return redirect()->back(301)->with('success', 'Cash Account Credit Successfully');
        }catch (\Exception $ex){
            DB::rollBack();
            Log::error("cash account credit ex : ". $ex->getMessage());
            return redirect()->back(301)->with('error', 'OOPS! Something went wrong.');
        }
    }

    function remove(int $id): JsonResponse
    {
        try {
            $cash_account = CashAccount::query()->findOrFail($id);
            $cash_account->deleteOrFail();
            return response()->json(['success' => true, 'message' => 'Cash Account Removed Successfully'], 200);
        }catch (\Exception $ex){
            Log::error("cash account remove ex : ". $ex->getMessage());
            return response()->json(['success' => false, 'message' => 'OOPS! Something went wrong'], 500);
        }
    }

    function accountStatusToggle(int $id, string $status): JsonResponse
    {
        try {
            if ($status === CashAccount::ACTIVE){
                $account = CashAccount::query()->findOrFail($id);
                $account->status = CashAccount::INACTIVE;
                $account->updateOrFail();
            }else{
                $accounts = CashAccount::query()->where('status', CashAccount::ACTIVE)
                    ->where('id', '!=', $id)->get();
                if ($accounts->count() > 0){
                    foreach ($accounts as $account){
                        $account->status = CashAccount::INACTIVE;
                        $account->updateOrFail();
                    }
                }
                $account = CashAccount::query()->findOrFail($id);
                $account->status = CashAccount::ACTIVE;
                $account->updateOrFail();
            }
            return response()->json(['success' => true, 'message' => 'Cash Account Status Changed Successfully'], 200);
        }catch (\Exception $ex){
            Log::error('Cash Account Status ex : '. $ex->getMessage());
            return response()->json(['success' => false, 'message' => 'OOPS! Something went wrong'], 500);
        }
    }
}
