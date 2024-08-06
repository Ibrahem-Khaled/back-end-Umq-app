<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\Users;
use Auth;

class AuthController extends Controller
{

    public function login()
    {

        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('dashboard.Auth.login');
        }
    }
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->route('dashboard')
                ->withSuccess('Signed in');
        }
        return redirect("login")->withSuccess('Login details are not valid');
    }
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('dashboard.Auth.register');
        }
    }
    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $user = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => 'provider',
            'password' => Hash::make($request->password)
        ]);
        auth()->login($user);

        return redirect()->route('dashboard')->withSuccess('You have signed-in');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
