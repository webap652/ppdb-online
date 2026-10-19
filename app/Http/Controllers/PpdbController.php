<?php

namespace App\Http\Controllers;

use App\Support\Ppdb;
use Illuminate\Http\Request;

class PpdbController extends Controller
{
    public function home()
    {
        return view('ppdb.home', [
            'jadwal' => Ppdb::jadwal(),
            'jalur' => Ppdb::jalur(),
            'berkas' => Ppdb::berkas(),
            'persyaratan' => config('ppdb.persyaratan'),
        ]);
    }

    /** Cek hasil seleksi berdasarkan nomor pendaftaran. */
    public function pengumuman(Request $request)
    {
        $no = trim((string) $request->query('no'));
        $applicant = $no !== '' ? Ppdb::find($no) : null;

        return view('ppdb.pengumuman', [
            'no' => $no,
            'applicant' => $applicant,
            'dirilis' => (bool) Ppdb::setting('pengumuman_dirilis'),
        ]);
    }
}
