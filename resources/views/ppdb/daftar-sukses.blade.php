@extends('layouts.ppdb')

@section('title', 'Pendaftaran Berhasil')

@section('content')
    <div class="max-w-md mx-auto px-4 py-10 sm:py-16 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="mt-5 text-2xl font-bold text-primary-900">Pendaftaran Berhasil</h1>
        <p class="text-sm text-slate-500 mt-2">Simpan nomor pendaftaran berikut. Nomor ini dipakai untuk masuk dan mengecek hasil seleksi.</p>

        <div class="mt-6 card p-5">
            <div class="text-xs text-slate-500">Nomor Pendaftaran</div>
            <div class="mt-1 text-2xl font-bold tracking-wide text-primary-900 select-all" id="no-daftar">{{ $applicant['no'] }}</div>
            <button type="button" class="mt-3 text-xs font-medium text-primary-700" onclick="navigator.clipboard?.writeText('{{ $applicant['no'] }}').then(() => this.textContent = 'Tersalin ✓')">
                <i class="fas fa-copy"></i> Salin nomor
            </button>
            <dl class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-3 text-left text-sm">
                <div><dt class="text-xs text-slate-500">Nama</dt><dd class="font-medium">{{ $applicant['nama'] }}</dd></div>
                <div><dt class="text-xs text-slate-500">Jalur</dt><dd class="font-medium">{{ $applicant['jalur_label'] }}</dd></div>
            </dl>
        </div>

        <div class="mt-6 card p-4 text-left flex gap-3 text-sm">
            <i class="fas fa-circle-info text-primary-700 mt-0.5"></i>
            <p><span class="font-semibold">Langkah berikutnya:</span> unggah berkas persyaratan agar panitia dapat memverifikasi pendaftaran Anda.</p>
        </div>

        <div class="mt-6 grid gap-3">
            <a href="{{ route('pendaftar.berkas') }}" class="btn-primary py-3"><i class="fas fa-file-arrow-up"></i> Unggah Berkas</a>
            <a href="{{ route('pendaftar.dashboard') }}" class="btn-light py-3">Ke Dasbor</a>
        </div>
    </div>
@endsection
