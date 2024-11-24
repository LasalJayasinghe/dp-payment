<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use App\Models\SubRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){

        if (Auth::check()) { // Check if user is authenticated
            $user_role = Auth::user()->role; // Access the role attribute

            if ($user_role == 'user') {

                $requests = SubRequest::query()->where(['status' => 'pending' , 'created_by' => Auth::user()->id])->get();
                foreach ($requests as $request){
                    $request->supplier_name = Supplier::query()->where('id', $request->supplier_id)->pluck('supplier_name')->first();
                }
                return view('user.dashboard' , compact('requests'));

            } else{

                $requests = SubRequest::all();
                $pending = $requests->where('status', SubRequest::STATUS_PENDING)->count();
                $checked = $requests->where('status', SubRequest::STATUS_CHECKED)->count();
                $waiting = $requests->where('status', SubRequest::STATUS_WAITING_FOR_SIGNATURE)->count();
                $approved = $requests->where('status', SubRequest::STATUS_APPROVED)->count();
                $rejected = $requests->where('status', SubRequest::STATUS_REJECTED)->count();

                // Pass the counts to the view
                return view('admin.dashboard', [
                    'pending' => $pending,
                    'checked' => $checked,
                    'waiting' => $waiting,
                    'approved' => $approved,
                    'rejected' => $rejected,
                ]);
            }
        }
        return redirect()->route('login');

    }
}
