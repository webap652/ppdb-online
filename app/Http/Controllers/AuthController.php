<?php

namespace App\Http\Controllers;

use App\Support\Ppdb;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('ppdb.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $account = Ppdb::attempt($data['login'], $data['password']);

        if (! $account) {
            return back()->withErrors([
                'login' => 'Email/no. pendaftaran atau password salah, atau akun dinonaktifkan.',
            ])->onlyInput('login');
        }

        Ppdb::login($account);

        return redirect()->intended(route(Ppdb::homeRoute($account['role'])));
    }

    public function logout()
    {
        Ppdb::logout();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}
