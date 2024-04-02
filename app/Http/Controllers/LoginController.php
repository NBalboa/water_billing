<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function create()
    {
        return view('login');
    }

    public function login()
    {
        $attributes = request()->validate([
            'username' => ['required', 'exists:users,username'],
            'password' => ['required']
        ]);

        if (auth()->attempt($attributes)) {
            session()->regenerate();
            return redirect('home')->with('success', 'Welcome Back');
        }

        return back()->withErrors(['username' => 'Invalid Username or Password']);
    }

    public function logout()
    {
        auth()->logout();
        session()->regenerate();
        return redirect('login')->with('success', "User Logout");
    }
}
