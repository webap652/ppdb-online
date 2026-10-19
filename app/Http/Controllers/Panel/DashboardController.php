<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\Ppdb;

class DashboardController extends Controller
{
    public function index()
    {
        return view('panel.dashboard', [
            'stats' => Ppdb::stats(),
            'jalur' => Ppdb::jalur(),
            'kuota' => (int) Ppdb::setting('kuota'),
            'antrean' => Ppdb::applicants()->where('status', 'menunggu')->take(5),
            'jadwal' => Ppdb::jadwal(),
        ]);
    }
}
