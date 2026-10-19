@extends('layouts.panel')

@section('title', 'Beranda')

@php
    $status = $applicant['status'];
    $final = in_array($status, ['diterima', 'ditolak'], true);
    $terverifikasi = in_array($status, ['terverifikasi', 'diterima', 'ditolak'], true);

    // [judul, keterangan, state: done|current|warn|todo]
    $tahapan = [
        ['Pendaftaran', 'Formulir terkirim ' . \Illuminate\Support\Carbon::parse($applicant['tanggal_daftar'])->translatedFormat('d M Y'), 'done'],
        ['Unggah Berkas', $berkasLengkap ? 'Berkas wajib lengkap' : 'Lengkapi berkas wajib', $berkasLengkap ? 'done' : 'current'],
        ['Verifikasi', match (true) {
            $status === 'perbaikan' => 'Perlu perbaikan berkas',
            $terverifikasi => 'Berkas terverifikasi',
            default => 'Menunggu pemeriksaan panitia',
        }, match (true) {
            $status === 'perbaikan' => 'warn',
            $terverifikasi => 'done',
            $berkasLengkap => 'current',
            default => 'todo',
        }],
        ['Pengumuman', $dirilis && $final ? 'Hasil sudah diumumkan' : 'Menunggu jadwal pengumuman', $dirilis && $final ? 'done' : ($terverifikasi ? 'current' : 'todo')],
    ];
    $dot = [
        'done' => 'bg-emerald-600 text-white',
        'current' => 'bg-primary-900 text-white ring-4 ring-primary-100',
        'warn' => 'bg-orange-500 text-white ring-4 ring-orange-100',
        'todo' => 'bg-slate-200 text-slate-500',
    ];
@endphp

@section('content')
    <div class="space-y-4 lg:space-y-6">
        {{-- Ringkasan --}}
        <div class="card p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-xs text-slate-500">Halo,</div>
                    <div class="text-lg font-bold text-primary-900 truncate">{{ $applicant['nama'] }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">{{ $applicant['no'] }} · Jalur {{ $applicant['jalur_label'] }}</div>
                </div>
                <x-status :status="$dirilis || ! $final ? $status : 'terverifikasi'" />
            </div>

            @if ($status === 'perbaikan' && $applicant['catatan'])
                <div class="mt-4 rounded-xl bg-orange-50 border border-orange-200 p-3 text-sm text-orange-800">
                    <div class="font-semibold"><i class="fas fa-triangle-exclamation mr-1"></i> Catatan panitia</div>
                    <p class="mt-1">{{ $applicant['catatan'] }}</p>
                    <a href="{{ route('pendaftar.berkas') }}" class="inline-block mt-2 font-semibold underline">Perbaiki berkas</a>
                </div>
            @elseif ($dirilis && $final)
                <div class="mt-4 rounded-xl p-3 text-sm {{ $status === 'diterima' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-rose-50 border border-rose-200 text-rose-800' }}">
                    {{ $status === 'diterima' ? 'Selamat! Anda diterima. Silakan daftar ulang sesuai jadwal.' : 'Mohon maaf, Anda belum diterima pada PPDB tahun ini.' }}
                </div>
            @elseif (! $berkasLengkap)
                <a href="{{ route('pendaftar.berkas') }}" class="btn-primary w-full mt-4 py-3"><i class="fas fa-file-arrow-up"></i> Lengkapi Berkas</a>
            @endif
        </div>

        <div class="grid lg:grid-cols-2 gap-4 lg:gap-6">
            {{-- Tahapan --}}
            <div class="card p-5">
                <h2 class="font-semibold">Progres Pendaftaran</h2>
                <ol class="mt-4">
                    @foreach ($tahapan as $i => [$judul, $ket, $state])
                        <li class="relative flex gap-4 pb-6 last:pb-0">
                            @unless ($loop->last)
                                <span class="absolute left-4 top-8 bottom-0 w-px -translate-x-1/2 {{ $state === 'done' ? 'bg-emerald-600' : 'bg-slate-200' }}"></span>
                            @endunless
                            <span class="relative w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-xs font-semibold {{ $dot[$state] }}">
                                @if ($state === 'done') <i class="fas fa-check"></i> @elseif ($state === 'warn') <i class="fas fa-exclamation"></i> @else {{ $i + 1 }} @endif
                            </span>
                            <div class="pt-1">
                                <div class="text-sm font-medium {{ $state === 'todo' ? 'text-slate-400' : '' }}">{{ $judul }}</div>
                                <div class="text-xs text-slate-500">{{ $ket }}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="space-y-4 lg:space-y-6">
                {{-- Aksi cepat --}}
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('pendaftar.biodata') }}" class="card p-4 hover:border-primary-700">
                        <i class="fas fa-id-card text-primary-700"></i>
                        <div class="mt-2 text-sm font-semibold">Biodata</div>
                        <div class="text-xs text-slate-500">Lihat data diri</div>
                    </a>
                    <a href="{{ route('pendaftar.kartu') }}" class="card p-4 hover:border-primary-700">
                        <i class="fas fa-print text-primary-700"></i>
                        <div class="mt-2 text-sm font-semibold">Kartu</div>
                        <div class="text-xs text-slate-500">Bukti pendaftaran</div>
                    </a>
                </div>

                {{-- Jadwal --}}
                <div class="card p-5">
                    <h2 class="font-semibold">Jadwal PPDB</h2>
                    <ul class="mt-3 divide-y divide-slate-100 text-sm">
                        @foreach ($jadwal as $j)
                            <li class="py-2.5 flex justify-between gap-3">
                                <span>{{ $j['tahap'] }}</span>
                                <span class="text-slate-500 text-right shrink-0">{{ \Illuminate\Support\Carbon::parse($j['mulai'])->translatedFormat('d M') }}@if ($j['mulai'] !== $j['selesai']) – {{ \Illuminate\Support\Carbon::parse($j['selesai'])->translatedFormat('d M') }}@endif</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
