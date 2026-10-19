@extends('layouts.panel')

@section('title', 'Dasbor')

@section('content')
    @php
        $s = $stats['status'];
        $kpi = [
            ['Total Pendaftar', $stats['total'], 'fa-users', 'text-primary-900 bg-primary-50', null],
            ['Menunggu Verifikasi', $s['menunggu'], 'fa-hourglass-half', 'text-amber-700 bg-amber-50', 'menunggu'],
            ['Terverifikasi', $s['terverifikasi'], 'fa-clipboard-check', 'text-sky-700 bg-sky-50', 'terverifikasi'],
            ['Diterima', $s['diterima'], 'fa-user-check', 'text-emerald-700 bg-emerald-50', 'diterima'],
        ];
    @endphp

    <div class="space-y-4 lg:space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            @foreach ($kpi as [$label, $value, $icon, $cls, $filter])
                <a href="{{ route('panel.pendaftar.index', array_filter(['status' => $filter])) }}" class="card p-4 hover:border-primary-700">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $cls }}"><i class="fas {{ $icon }}"></i></div>
                    <div class="mt-3 text-2xl font-bold">{{ $value }}</div>
                    <div class="text-xs text-slate-500">{{ $label }}</div>
                </a>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-5 gap-4 lg:gap-6">
            {{-- Antrean verifikasi --}}
            <div class="card lg:col-span-3">
                <div class="px-5 py-4 flex items-center justify-between">
                    <h2 class="font-semibold">Antrean Verifikasi</h2>
                    <a href="{{ route('panel.pendaftar.index', ['status' => 'menunggu']) }}" class="text-xs font-medium text-primary-700">Lihat semua</a>
                </div>
                <ul class="divide-y divide-slate-100 border-t border-slate-100">
                    @forelse ($antrean as $a)
                        <li>
                            <a href="{{ route('panel.pendaftar.show', $a['no']) }}" class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-slate-50">
                                <div class="min-w-0">
                                    <div class="text-sm font-medium truncate">{{ $a['nama'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $a['no'] }} · {{ $a['jalur_label'] }}</div>
                                </div>
                                <i class="fas fa-chevron-right text-xs text-slate-300"></i>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-sm text-slate-500">Tidak ada antrean. 🎉</li>
                    @endforelse
                </ul>
            </div>

            {{-- Keterisian kuota per jalur --}}
            <div class="card p-5 lg:col-span-2">
                <h2 class="font-semibold">Pendaftar per Jalur</h2>
                <p class="text-xs text-slate-500">Dibandingkan dengan kuota (total {{ $kuota }} kursi)</p>
                <ul class="mt-4 space-y-4">
                    @foreach ($jalur as $key => $j)
                        @php $n = $stats['jalur'][$key]; $pct = $j['kuota'] ? min(100, round($n / $j['kuota'] * 100)) : 0; @endphp
                        <li>
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{ $j['label'] }}</span>
                                <span class="text-slate-500">{{ $n }} / {{ $j['kuota'] }}</span>
                            </div>
                            <div class="mt-1.5 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-primary-900" style="width: {{ $pct }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <a href="{{ route('panel.pendaftar.index', ['status' => 'perbaikan']) }}" class="card p-4 flex items-center gap-3 hover:border-primary-700">
                <span class="text-xl font-bold text-orange-600">{{ $s['perbaikan'] }}</span><span class="text-xs text-slate-500">Perlu perbaikan</span>
            </a>
            <a href="{{ route('panel.pendaftar.index', ['status' => 'ditolak']) }}" class="card p-4 flex items-center gap-3 hover:border-primary-700">
                <span class="text-xl font-bold text-rose-600">{{ $s['ditolak'] }}</span><span class="text-xs text-slate-500">Tidak diterima</span>
            </a>
            <a href="{{ route('panel.seleksi') }}" class="card p-4 flex items-center justify-between gap-3 col-span-2 sm:col-span-1 bg-primary-900 !border-primary-900 text-white">
                <span class="text-sm font-semibold">Proses Seleksi</span><i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection
