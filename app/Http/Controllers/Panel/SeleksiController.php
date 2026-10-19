<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\Ppdb;
use Illuminate\Http\Request;

class SeleksiController extends Controller
{
    /** Peringkat pendaftar terverifikasi per jalur, diurutkan dari nilai tertinggi. */
    public function index(Request $request)
    {
        $jalur = Ppdb::jalur();
        $aktif = array_key_exists($request->query('jalur'), $jalur) ? $request->query('jalur') : array_key_first($jalur);

        $peringkat = Ppdb::applicants()
            ->where('jalur', $aktif)
            ->whereIn('status', ['terverifikasi', 'diterima', 'ditolak'])
            ->sortByDesc('nilai')
            ->values();

        return view('panel.seleksi', [
            'jalur' => $jalur,
            'aktif' => $aktif,
            'peringkat' => $peringkat,
            'diterima' => $peringkat->where('status', 'diterima')->count(),
            'dirilis' => (bool) Ppdb::setting('pengumuman_dirilis'),
        ]);
    }

    public function putuskan(Request $request, string $no)
    {
        $applicant = Ppdb::find($no) ?? abort(404);
        abort_unless(in_array($applicant['status'], ['terverifikasi', 'diterima', 'ditolak'], true), 422);

        $data = $request->validate(['status' => 'required|in:diterima,ditolak,terverifikasi']);

        if ($data['status'] === 'diterima') {
            $terisi = Ppdb::applicants()->where('jalur', $applicant['jalur'])->where('status', 'diterima')->count();
            if ($terisi >= Ppdb::jalur($applicant['jalur'])['kuota']) {
                return back()->with('error', 'Kuota jalur ' . $applicant['jalur_label'] . ' sudah penuh.');
            }
        }

        Ppdb::update($no, ['status' => $data['status']]);

        return back()->with('success', $applicant['nama'] . ': ' . Ppdb::STATUS[$data['status']][0] . '.');
    }

    /** Admin: rilis / tarik pengumuman hasil seleksi ke publik. */
    public function umumkan()
    {
        $dirilis = ! Ppdb::setting('pengumuman_dirilis');
        Ppdb::saveSettings(array_merge(Ppdb::setting(), ['pengumuman_dirilis' => $dirilis]), Ppdb::jadwal());

        return back()->with('success', $dirilis ? 'Pengumuman hasil seleksi dirilis.' : 'Pengumuman ditarik kembali.');
    }
}
