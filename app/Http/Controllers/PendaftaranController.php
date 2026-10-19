<?php

namespace App\Http\Controllers;

use App\Support\Ppdb;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function create()
    {
        return view('ppdb.daftar', [
            'dibuka' => (bool) Ppdb::setting('pendaftaran_dibuka'),
            'jalur' => Ppdb::jalur(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Ppdb::setting('pendaftaran_dibuka'), 403, 'Pendaftaran sedang ditutup.');

        $data = $request->validate([
            // Langkah 1 — data siswa
            'nama' => 'required|string|max:100',
            'nisn' => 'required|digits:10',
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'agama' => 'required|string|max:20',
            'distrik' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            // Langkah 2 — orang tua / wali
            'nama_ayah' => 'required|string|max:100',
            'nama_ibu' => 'required|string|max:100',
            'pekerjaan_ortu' => 'required|string|max:50',
            'hp' => ['required', 'regex:/^(\+62|0)8[0-9]{7,12}$/'],
            // Langkah 3 — sekolah asal & jalur
            'sekolah_asal' => 'required|string|max:100',
            'tahun_lulus' => 'required|digits:4',
            'nilai' => 'required|numeric|between:0,100',
            'jalur' => ['required', Rule::in(array_keys(Ppdb::jalur()))],
            // Langkah 4 — akun
            'email' => ['required', 'email', function ($attr, $value, $fail) {
                if (Ppdb::accounts()->contains(fn ($u) => strcasecmp($u['email'], $value) === 0)) {
                    $fail('Email sudah terdaftar.');
                }
            }],
            'password' => 'required|string|min:8|confirmed',
            'setuju' => 'accepted',
        ], [
            'hp.regex' => 'Nomor HP tidak valid (contoh: 081234567890).',
            'setuju.accepted' => 'Anda harus menyetujui pernyataan kebenaran data.',
        ]);

        if (Ppdb::applicants()->contains('nisn', $data['nisn'])) {
            return back()->withInput()->withErrors(['nisn' => 'NISN ini sudah terdaftar.']);
        }

        $data['nilai'] = (float) $data['nilai'];
        $applicant = Ppdb::register($data);

        // Langsung masuk sebagai pendaftar baru
        Ppdb::login(Ppdb::accounts()->firstWhere('no', $applicant['no']));

        return redirect()->route('daftar.sukses')->with('baru', $applicant['no']);
    }

    public function sukses()
    {
        $applicant = Ppdb::find(session('baru') ?? Ppdb::user()['no'] ?? null);

        if (! $applicant) {
            return redirect()->route('home');
        }

        return view('ppdb.daftar-sukses', compact('applicant'));
    }
}
