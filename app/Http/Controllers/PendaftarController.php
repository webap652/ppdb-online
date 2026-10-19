<?php

namespace App\Http\Controllers;

use App\Support\Ppdb;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Halaman akun milik pendaftar yang sedang login. */
class PendaftarController extends Controller
{
    public function dashboard()
    {
        $applicant = $this->applicant();

        return view('pendaftar.dashboard', [
            'applicant' => $applicant,
            'berkasLengkap' => Ppdb::berkasLengkap($applicant),
            'dirilis' => (bool) Ppdb::setting('pengumuman_dirilis'),
            'jadwal' => Ppdb::jadwal(),
        ]);
    }

    public function biodata()
    {
        return view('pendaftar.biodata', ['applicant' => $this->applicant()]);
    }

    public function berkas()
    {
        return view('pendaftar.berkas', [
            'applicant' => $this->applicant(),
            'berkas' => Ppdb::berkas(),
        ]);
    }

    /** Prototype: file divalidasi lalu hanya dicatat statusnya, tidak disimpan. */
    public function unggah(Request $request)
    {
        $applicant = $this->applicant();
        abort_if(in_array($applicant['status'], ['diterima', 'ditolak'], true), 403, 'Seleksi sudah selesai.');

        $data = $request->validate([
            'jenis' => ['required', Rule::in(array_keys(Ppdb::berkas()))],
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'file.max' => 'Ukuran file maksimal 2 MB.',
            'file.mimes' => 'Format file harus JPG, PNG, atau PDF.',
        ]);

        $changes = ['berkas' => [$data['jenis'] => 'menunggu']];

        // Setelah perbaikan diunggah, kembalikan ke antrean verifikasi
        if ($applicant['status'] === 'perbaikan') {
            $changes += ['status' => 'menunggu'];
        }

        Ppdb::update($applicant['no'], $changes);

        return back()->with('success', Ppdb::berkas()[$data['jenis']]['label'] . ' berhasil diunggah.');
    }

    public function kartu()
    {
        return view('pendaftar.kartu', ['applicant' => $this->applicant()]);
    }

    private function applicant(): array
    {
        return Ppdb::find(Ppdb::user()['no'] ?? null) ?? abort(404, 'Data pendaftaran tidak ditemukan.');
    }
}
