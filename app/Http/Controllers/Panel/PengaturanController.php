<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\Ppdb;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function edit()
    {
        return view('panel.pengaturan', [
            'setting' => Ppdb::setting(),
            'jadwal' => Ppdb::jadwal(),
            'jalur' => Ppdb::jalur(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'kuota' => 'required|integer|min:1|max:1000',
            'jadwal' => 'required|array',
            'jadwal.*.tahap' => 'required|string|max:50',
            'jadwal.*.mulai' => 'required|date',
            'jadwal.*.selesai' => 'required|date|after_or_equal:jadwal.*.mulai',
        ], [
            'tahun_ajaran.regex' => 'Format tahun ajaran: 2027/2028.',
            'jadwal.*.selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        Ppdb::saveSettings([
            'tahun_ajaran' => $data['tahun_ajaran'],
            'kuota' => (int) $data['kuota'],
            'pendaftaran_dibuka' => $request->boolean('pendaftaran_dibuka'),
            'pengumuman_dirilis' => $request->boolean('pengumuman_dirilis'),
        ], array_values($data['jadwal']));

        return back()->with('success', 'Pengaturan PPDB disimpan.');
    }
}
