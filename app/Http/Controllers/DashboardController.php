<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        
        if (Auth::check()) { // Check if user is authenticated
            $user_role = Auth::user()->role; // Access the role attribute
    
            if ($user_role == 'user') {
                
                $requests = Requests::where(['status' => 'pending' , 'user_id' => Auth::user()->id])->get();
                foreach ($requests as $request){
                    $request->supplier_name = Supplier::where('id', $request->supplier_id)->pluck('supplier_name')->first();
                }
                return view('user.dashboard' , compact('requests'));
                
            } else{

                $requests = Requests::all();
            
                $pending = $requests->where('status', 'pending')->count();
                $checked = $requests->where('status', 'checked')->count();
                $waiting = $requests->where('status', 'waiting_for_signature')->count();
                $approved = $requests->where('status', 'approved')->count();
                $rejected = $requests->where('status', 'rejected')->count();
            
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
