<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\facades\Hash;
use Illuminate\Support\facades\Auth;


class AuthController extends Controller
{
    public function register (Request $request){
        if ($request->isMethod('get')){
            return view('auth.register');
        }
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        user::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make( $request->input('password'))
        ]);

        return redirect()->route('login')
            ->with('success', 'your account has been created! you can now login.');
    }

    public function login (Request $request){
        
        if ($request->isMethod('get')){
            return view('auth.login');
        }

        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)){
            return redirect()
                ->route('home')
                ->with('success', 'You are logged in!');
        }

        return redirect()
        ->route('home')
        ->withErrors('Provided login information is not valid.');

    }

    public function logout (Request $request){

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
        ->route('home')
        ->with('success', 'You are logged out!');

    }
}
