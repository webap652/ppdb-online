@extends('layouts.ppdb')
@php use App\Support\Ppdb; use Illuminate\Support\Carbon; @endphp

@section('content')
    {{-- Hero --}}
    <section class="bg-primary-900 text-white">
        <div class="max-w-5xl mx-auto px-4 py-12 sm:py-20">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium">
                <span class="w-2 h-2 rounded-full {{ Ppdb::setting('pendaftaran_dibuka') ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                Pendaftaran {{ Ppdb::setting('pendaftaran_dibuka') ? 'Dibuka' : 'Ditutup' }}
            </span>
            <h1 class="mt-4 text-3xl sm:text-5xl font-bold tracking-tight leading-tight">
                PPDB Online<br class="sm:hidden"> {{ Ppdb::setting('tahun_ajaran') }}
            </h1>
            <p class="mt-3 text-slate-300 sm:text-lg max-w-xl">
                Penerimaan Peserta Didik Baru {{ Ppdb::sekolah('nama') }}. Daftar dari HP, unggah berkas, dan pantau hasil seleksi secara online.
            </p>
            <div class="mt-8 grid grid-cols-1 sm:flex gap-3">
                <a href="{{ route('daftar') }}" class="btn bg-gold-500 text-primary-900 hover:bg-gold-400 py-3 px-6">
                    <i class="fas fa-pen-to-square"></i> Daftar Sekarang
                </a>
                <a href="{{ route('pengumuman') }}" class="btn border border-white/30 text-white hover:bg-white/10 py-3 px-6">
                    <i class="fas fa-magnifying-glass"></i> Cek Hasil Seleksi
                </a>
            </div>
            <dl class="mt-10 grid grid-cols-3 gap-3 max-w-md">
                <div class="rounded-2xl bg-white/5 p-3">
                    <dt class="text-[11px] text-slate-400">Kuota</dt>
                    <dd class="text-xl font-bold">{{ Ppdb::setting('kuota') }}</dd>
                </div>
                <div class="rounded-2xl bg-white/5 p-3">
                    <dt class="text-[11px] text-slate-400">Jalur</dt>
                    <dd class="text-xl font-bold">{{ count($jalur) }}</dd>
                </div>
                <div class="rounded-2xl bg-white/5 p-3">
                    <dt class="text-[11px] text-slate-400">Biaya</dt>
                    <dd class="text-xl font-bold">Gratis</dd>
                </div>
            </dl>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 py-10 sm:py-14 space-y-12 sm:space-y-16">
        {{-- Alur --}}
        <section>
            <h2 class="text-lg sm:text-xl font-bold text-primary-900">Alur Pendaftaran</h2>
            <ol class="mt-5 grid sm:grid-cols-4 gap-3">
                @foreach ([
                    ['fa-user-plus', 'Buat Akun', 'Isi formulir pendaftaran online.'],
                    ['fa-file-arrow-up', 'Unggah Berkas', 'Unggah dokumen persyaratan.'],
                    ['fa-clipboard-check', 'Verifikasi', 'Panitia memeriksa berkas Anda.'],
                    ['fa-bullhorn', 'Pengumuman', 'Cek hasil seleksi & daftar ulang.'],
                ] as $i => [$icon, $judul, $ket])
                    <li class="card p-4 flex sm:flex-col gap-4 sm:gap-3">
                        <div class="w-10 h-10 shrink-0 rounded-xl bg-primary-50 text-primary-900 flex items-center justify-center">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">Langkah {{ $i + 1 }}</div>
                            <div class="font-semibold">{{ $judul }}</div>
                            <p class="text-sm text-slate-500 mt-0.5">{{ $ket }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8">
            {{-- Jadwal --}}
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-primary-900">Jadwal</h2>
                <ul class="mt-5 card divide-y divide-slate-100">
                    @foreach ($jadwal as $j)
                        @php
                            $mulai = Carbon::parse($j['mulai']);
                            $selesai = Carbon::parse($j['selesai']);
                            $aktif = now()->between($mulai->startOfDay(), $selesai->copy()->endOfDay());
                        @endphp
                        <li class="p-4 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-medium text-sm">{{ $j['tahap'] }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $mulai->translatedFormat('d M Y') }}@if (! $mulai->isSameDay($selesai)) – {{ $selesai->translatedFormat('d M Y') }}@endif
                                </div>
                            </div>
                            @if ($aktif)
                                <span class="badge bg-emerald-50 text-emerald-700 ring-emerald-200">Berlangsung</span>
                            @elseif ($selesai->isPast())
                                <span class="badge bg-slate-50 text-slate-500 ring-slate-200">Selesai</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Jalur --}}
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-primary-900">Jalur & Kuota</h2>
                <ul class="mt-5 grid grid-cols-2 gap-3">
                    @foreach ($jalur as $j)
                        <li class="card p-4">
                            <div class="flex items-baseline justify-between gap-2">
                                <span class="font-semibold">{{ $j['label'] }}</span>
                                <span class="text-xs text-slate-400">{{ $j['persen'] }}%</span>
                            </div>
                            <div class="text-2xl font-bold text-primary-900 mt-1">{{ $j['kuota'] }}<span class="text-xs font-normal text-slate-400"> kursi</span></div>
                            <p class="text-xs text-slate-500 mt-1">{{ $j['ket'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        {{-- Persyaratan --}}
        <section class="grid lg:grid-cols-2 gap-8">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-primary-900">Persyaratan</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($persyaratan as $p)
                        <li class="flex gap-3 text-sm"><i class="fas fa-check text-emerald-600 mt-1"></i> {{ $p }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-primary-900">Berkas yang Diunggah</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($berkas as $b)
                        <li class="flex gap-3 text-sm">
                            <i class="fas fa-file-lines text-slate-400 mt-1"></i>
                            <span>{{ $b['label'] }} @unless ($b['wajib'])<span class="text-slate-400">(opsional)</span>@endunless</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-4 text-xs text-slate-500">Format JPG, PNG, atau PDF. Maksimal 2 MB per file.</p>
            </div>
        </section>

        {{-- CTA --}}
        <section class="card p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-primary-900">Sudah punya akun?</h2>
                <p class="text-sm text-slate-500 mt-1">Masuk untuk melengkapi berkas dan memantau status pendaftaran.</p>
            </div>
            <a href="{{ route('login') }}" class="btn-primary py-3 px-6">Masuk <i class="fas fa-arrow-right"></i></a>
        </section>
    </div>
@endsection
