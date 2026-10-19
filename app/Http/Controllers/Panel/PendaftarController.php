<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\Ppdb;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $status = $request->query('status');
        $jalur = $request->query('jalur');

        $applicants = Ppdb::applicants()
            ->when($q !== '', fn ($c) => $c->filter(fn ($a) => str_contains(strtolower($a['nama'] . ' ' . $a['no'] . ' ' . $a['nisn']), strtolower($q))))
            ->when($status, fn ($c) => $c->where('status', $status))
            ->when($jalur, fn ($c) => $c->where('jalur', $jalur));

        return view('panel.pendaftar.index', [
            'applicants' => $applicants,
            'q' => $q,
            'status' => $status,
            'jalur' => $jalur,
            'stats' => Ppdb::stats(),
        ]);
    }

    public function show(string $no)
    {
        $applicant = Ppdb::find($no) ?? abort(404);

        return view('panel.pendaftar.show', [
            'applicant' => $applicant,
            'berkas' => Ppdb::berkas(),
            'lengkap' => Ppdb::berkasLengkap($applicant),
        ]);
    }

    /** Verifikasi berkas: tandai tiap berkas lalu tetapkan hasil verifikasi. */
    public function verifikasi(Request $request, string $no)
    {
        $applicant = Ppdb::find($no) ?? abort(404);

        $data = $request->validate([
            'berkas' => 'array',
            'berkas.*' => 'in:valid,ditolak,menunggu',
            'aksi' => 'required|in:terverifikasi,perbaikan',
            'catatan' => 'nullable|string|max:500|required_if:aksi,perbaikan',
        ], [
            'catatan.required_if' => 'Tulis catatan perbaikan untuk pendaftar.',
        ]);

        $berkas = collect($data['berkas'] ?? [])->only(array_keys($applicant['berkas']))->all();
        $merged = array_merge($applicant, ['berkas' => array_merge($applicant['berkas'], $berkas)]);

        if ($data['aksi'] === 'terverifikasi'
            && (! Ppdb::berkasLengkap($merged) || in_array('menunggu', $merged['berkas'], true))) {
            return back()->withErrors(['aksi' => 'Semua berkas wajib harus valid sebelum diverifikasi.']);
        }

        Ppdb::update($no, [
            'berkas' => $berkas,
            'status' => $data['aksi'],
            'catatan' => $data['aksi'] === 'perbaikan' ? $data['catatan'] : null,
        ]);

        return redirect()->route('panel.pendaftar.show', $no)->with('success', 'Hasil verifikasi disimpan.');
    }
}
