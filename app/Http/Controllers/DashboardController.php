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
    
            if ($user_role == 'admin') {
                return view('admin.dashboard');
            } elseif ($user_role == 'user') {

                $requests = Requests::where('status' , 'pending')->get();
                foreach ($requests as $request){
                    $request->supplier_name = Supplier::where('id', $request->supplier_id)->pluck('supplier_name')->first();
                }
                return view('user.dashboard' , compact('requests'));
            }
        }
        return redirect()->route('login');

    }
}
