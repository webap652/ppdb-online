@extends('layouts.panel')

@section('title', 'Seleksi')

@php $isAdmin = \App\Support\Ppdb::user()['role'] === 'admin'; @endphp

@section('content')
    <div class="space-y-4">
        {{-- Status pengumuman --}}
        <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full {{ $dirilis ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                <div>
                    <div class="text-sm font-semibold">Pengumuman {{ $dirilis ? 'sudah dirilis' : 'belum dirilis' }}</div>
                    <div class="text-xs text-slate-500">{{ $dirilis ? 'Pendaftar dapat melihat hasil seleksi.' : 'Hasil hanya terlihat oleh panitia.' }}</div>
                </div>
            </div>
            @if ($isAdmin)
                <form method="POST" action="{{ route('panel.seleksi.umumkan') }}" data-confirm="{{ $dirilis ? 'Tarik kembali pengumuman?' : 'Rilis pengumuman hasil seleksi ke publik?' }}">
                    @csrf
                    <button type="submit" class="{{ $dirilis ? 'btn-light' : 'btn-primary' }} w-full sm:w-auto"><i class="fas fa-bullhorn"></i> {{ $dirilis ? 'Tarik Pengumuman' : 'Rilis Pengumuman' }}</button>
                </form>
            @endif
        </div>

        {{-- Tab jalur --}}
        <div class="-mx-4 px-4 lg:mx-0 lg:px-0 flex gap-2 overflow-x-auto pb-1 [scrollbar-width:none]">
            @foreach ($jalur as $key => $j)
                <a href="{{ route('panel.seleksi', ['jalur' => $key]) }}"
                    class="shrink-0 rounded-full px-4 py-2 text-sm font-medium border {{ $aktif === $key ? 'bg-primary-900 border-primary-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">
                    {{ $j['label'] }}
                </a>
            @endforeach
        </div>

        @php $kuota = $jalur[$aktif]['kuota']; @endphp
        <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500">Peringkat berdasarkan nilai rapor</span>
            <span class="font-semibold">Diterima {{ $diterima }} / {{ $kuota }}</span>
        </div>

        <ul class="card divide-y divide-slate-100">
            @forelse ($peringkat as $i => $a)
                <li class="p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-xs font-bold {{ $i < $kuota ? 'bg-primary-900 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $i + 1 }}</span>
                        <a href="{{ route('panel.pendaftar.show', $a['no']) }}" class="min-w-0 flex-1">
                            <div class="text-sm font-medium truncate">{{ $a['nama'] }}</div>
                            <div class="text-xs text-slate-500">{{ $a['no'] }} · Nilai {{ number_format($a['nilai'], 2, ',', '.') }}</div>
                        </a>
                        <x-status :status="$a['status']" class="sm:hidden" />
                    </div>

                    <div class="flex items-center gap-2 sm:justify-end">
                        <x-status :status="$a['status']" class="hidden sm:inline-flex mr-1" />
                        @foreach ([['diterima', 'Terima', 'btn-success', 'fa-check'], ['ditolak', 'Tolak', 'btn-danger', 'fa-xmark']] as [$val, $label, $cls, $icon])
                            @if ($a['status'] !== $val)
                                <form method="POST" action="{{ route('panel.seleksi.putuskan', $a['no']) }}" class="flex-1 sm:flex-none">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $val }}">
                                    <button type="submit" class="{{ $cls }} w-full !py-2"><i class="fas {{ $icon }}"></i> {{ $label }}</button>
                                </form>
                            @endif
                        @endforeach
                        @if ($a['status'] !== 'terverifikasi')
                            <form method="POST" action="{{ route('panel.seleksi.putuskan', $a['no']) }}">
                                @csrf
                                <input type="hidden" name="status" value="terverifikasi">
                                <button type="submit" class="btn-light !py-2 !px-3" title="Batalkan keputusan" aria-label="Batalkan keputusan"><i class="fas fa-rotate-left"></i></button>
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <li class="p-10 text-center text-sm text-slate-500">Belum ada pendaftar terverifikasi di jalur ini.</li>
            @endforelse
        </ul>
        <p class="text-xs text-slate-500">Nomor berwarna gelap = masuk dalam kuota jalur.</p>
    </div>
@endsection
