<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth; 

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        Log::info("Login attempt", ['email' => $request->email]);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            log::info("test");
            return redirect()->intended('dashboard')->with('success', 'You are logged in!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'You have been logged out!');
    }

    public function signup(REQUEST $request)
    {
        return view('auth.signup');
    }
}
