<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login() {
        return view('login');
    }

    public function signup() {
        return view('registration');
    }

    public function logincheck(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('kasir');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function registercheck(Request $request) {
        $validation = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Hash password sebelum disimpan
        $validation['password'] = bcrypt($validation['password']);

        $user = User::create($validation);
        Auth::login($user);

        return redirect()->route('login');
    }

    public function goKasir() {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            return view('admin');
        } elseif (Auth::check() && Auth::user()->usertype == 'user') {
            return view('kasir');
        } else {
            return redirect()->route('login');
        }
        

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
