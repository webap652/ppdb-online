@extends('layouts.ppdb')

@section('title', 'Pengumuman')

@section('content')
    <div class="max-w-md mx-auto px-4 py-10 sm:py-16">
        <h1 class="text-2xl font-bold text-primary-900">Cek Hasil Seleksi</h1>
        <p class="text-sm text-slate-500 mt-1">Masukkan nomor pendaftaran Anda.</p>

        <form method="GET" action="{{ route('pengumuman') }}" class="mt-6 flex gap-2">
            <input name="no" value="{{ $no }}" required class="input uppercase" placeholder="PPDB-2027-0001" autocapitalize="characters" aria-label="Nomor pendaftaran">
            <button type="submit" class="btn-primary shrink-0 px-5" aria-label="Cari"><i class="fas fa-magnifying-glass"></i></button>
        </form>

        @if ($no !== '')
            <div class="mt-6">
                @if (! $applicant)
                    <div class="card p-6 text-center">
                        <i class="fas fa-circle-question text-3xl text-slate-300"></i>
                        <p class="mt-3 font-semibold">Nomor tidak ditemukan</p>
                        <p class="text-sm text-slate-500 mt-1">Periksa kembali penulisan nomor pendaftaran.</p>
                    </div>
                @elseif (! $dirilis)
                    <div class="card p-6 text-center">
                        <i class="fas fa-hourglass-half text-3xl text-amber-400"></i>
                        <p class="mt-3 font-semibold">Hasil belum diumumkan</p>
                        <p class="text-sm text-slate-500 mt-1">Pengumuman akan tersedia sesuai jadwal PPDB.</p>
                    </div>
                @else
                    @php
                        $diterima = $applicant['status'] === 'diterima';
                        $final = in_array($applicant['status'], ['diterima', 'ditolak'], true);
                    @endphp
                    <div class="card overflow-hidden">
                        <div class="p-6 text-center {{ ! $final ? 'bg-slate-50' : ($diterima ? 'bg-emerald-50' : 'bg-rose-50') }}">
                            <div class="text-xs uppercase tracking-wider text-slate-500">Hasil Seleksi</div>
                            <div class="mt-1 text-2xl font-bold {{ ! $final ? 'text-slate-600' : ($diterima ? 'text-emerald-700' : 'text-rose-700') }}">
                                {{ ! $final ? 'Dalam Proses' : ($diterima ? 'DITERIMA' : 'TIDAK DITERIMA') }}
                            </div>
                        </div>
                        <dl class="p-5 grid grid-cols-2 gap-4 text-sm">
                            <div class="col-span-2"><dt class="text-xs text-slate-500">Nama</dt><dd class="font-semibold">{{ $applicant['nama'] }}</dd></div>
                            <div><dt class="text-xs text-slate-500">No. Pendaftaran</dt><dd class="font-medium">{{ $applicant['no'] }}</dd></div>
                            <div><dt class="text-xs text-slate-500">Jalur</dt><dd class="font-medium">{{ $applicant['jalur_label'] }}</dd></div>
                            <div class="col-span-2"><dt class="text-xs text-slate-500">Sekolah Asal</dt><dd class="font-medium">{{ $applicant['sekolah_asal'] }}</dd></div>
                        </dl>
                        @if ($diterima)
                            <p class="px-5 pb-5 text-sm text-slate-600">Selamat! Silakan melakukan daftar ulang di sekolah sesuai jadwal dengan membawa berkas asli.</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
