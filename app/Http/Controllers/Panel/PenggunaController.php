<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\Ppdb;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('panel.pengguna', [
            'accounts' => Ppdb::accounts()->whereIn('role', ['admin', 'operator'])->values(),
            'jumlahPendaftar' => Ppdb::accounts()->where('role', 'pendaftar')->count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', function ($attr, $value, $fail) {
                if (Ppdb::accounts()->contains(fn ($u) => strcasecmp($u['email'], $value) === 0)) {
                    $fail('Email sudah digunakan.');
                }
            }],
            'role' => 'required|in:admin,operator',
            'password' => 'required|string|min:8',
        ]);

        Ppdb::addAccount($data);

        return back()->with('success', 'Akun ' . $data['nama'] . ' ditambahkan.');
    }

    public function toggle(Request $request)
    {
        $email = $request->validate(['email' => 'required|email'])['email'];

        abort_unless(Ppdb::accounts()->contains('email', $email), 404);

        if ($email === Ppdb::user()['email']) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        Ppdb::toggleAccount($email);

        return back()->with('success', 'Status akun diperbarui.');
    }
}
