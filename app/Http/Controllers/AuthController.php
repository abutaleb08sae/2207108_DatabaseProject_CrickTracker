<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $nextIdResult = DB::select("SELECT NVL(MAX(USER_ID), 0) + 1 AS next_id FROM USERS");
        $nextId = $nextIdResult[0]->next_id;

        $user = User::create([
            'USER_ID'       => $nextId,
            'USERNAME'      => $request->name,
            'EMAIL'         => $request->email,
            'PASSWORD_HASH' => Hash::make($request->password),
            'ROLE'          => 'USER', 
            'IS_ADMIN'      => 0,
        ]);

        $request->session()->regenerate();
        Auth::login($user); 
        return redirect('/');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user case-insensitively from the Oracle USERS table
        $user = User::whereRaw('LOWER("EMAIL") = ?', [strtolower($credentials['email'])])->first();

        // FIX: trim() removes hidden spaces that Oracle CHAR column definitions add automatically
        if ($user && Hash::check($credentials['password'], trim($user->PASSWORD_HASH))) {
            
            $request->session()->regenerate();
            Auth::login($user);
            
            return redirect('/');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}