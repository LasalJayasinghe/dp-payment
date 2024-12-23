<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            Log::info("Login attempt", ['email' => $request->email]);
    
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
    
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $user->last_login = Carbon::now();
                $user->save();
    
                Log::info("Login successful", ['user_id' => $user->id, 'email' => $user->email]);
                if (is_null($user->signature) ) {
                    return redirect()->route('dashboard')->with('signatureWarning', 'You need to upload your signature!');
                }
    
                return redirect()->intended('dashboard')->with('success', 'You are logged in!');
            }
    
            // Log error for invalid credentials
            Log::error("Login failed", [
                'email' => $request->email,
                'ip' => $request->ip(), // Log the IP address
                'user_agent' => $request->header('User-Agent'), // Log user agent details
            ]);
    
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
                'password' => 'The provided credentials do not match our records.',

            ])->onlyInput('email');
        }
    
        return view('auth.login');
    }
    


    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out!');
    }

    public function signup(REQUEST $request)
    {
        if ($request->isMethod('post')) {
            Log::info('request' , [$request]);
            $user = new User();
            $user->fname = $request->fname;
            $user->name = $request->username;
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->role = $request->role;
            $user->save();

            if($user->role == "admin"){
                $user->assignRole('admin');
            }elseif($user->role == "user"){
                $user->assignRole('userRole');
            }elseif($user->role == "minAccount"){
                $user->assignRole('minAcoountRole');
            }elseif($user->role == "highAccount"){
                $user->assignRole('maxAcoountRole');
            }elseif($user->role == "manager"){
                $user->assignRole('manager');
            }elseif($user->role == "account"){
                $user->assignRole('account');
            }

            return redirect()->route('dashboard')->with('success', 'User added successfully!');
        }

        return view('auth.signup');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile' , compact('user'));
    }

    
    public function uploadSignature(Request $request)
    {

        Log::info('function called');
        $request->validate([
            'signature_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Add validation rules as needed
        ]);

        if ($request->hasFile('signature_file') && $request->file('signature_file')->isValid()) {

            $file = $request->file('signature_file');
            $user = Auth::user();

            $filename = $user->id . '-signature.' . $file->getClientOriginalExtension();

            if ($user->signature && Storage::exists('public/signatures/' . $user->signature)) {
                Storage::delete('public/signatures/' . $user->signature);
            }

            $path = $file->storeAs('signatures', $filename, 'public');
            $user->signature = $path;
            $user->save();

            return back()->with('success', 'Signature uploaded successfully!');
        }

        return back()->withErrors(['signature_file' => 'There was an issue uploading the file.']);
    }

    public function changePassword(Request $request)
    {
        // $request->validate([
        //     'current_password' => 'required',
        //     'new_password' => 'required|min:8|confirmed', // Ensure the new password is at least 8 characters and confirmed
        // ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password), 
        ]);

        return redirect()->route('auth.profile')->with('success', 'Password changed successfully!');
    }


}
