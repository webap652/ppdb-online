@extends('layouts.panel')

@section('title', 'Pendaftar')

@section('content')
    @php use App\Support\Ppdb; @endphp
    <div class="space-y-4">
        <form method="GET" class="flex gap-2">
            @if ($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
            <div class="relative flex-1">
                <i class="fas fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama, no. daftar, NISN" class="input pl-10">
            </div>
            <select name="jalur" class="input !w-auto" onchange="this.form.submit()" aria-label="Filter jalur">
                <option value="">Semua jalur</option>
                @foreach (Ppdb::jalur() as $key => $j)
                    <option value="{{ $key }}" @selected($jalur === $key)>{{ $j['label'] }}</option>
                @endforeach
            </select>
        </form>

        {{-- Filter status: bisa digeser di HP --}}
        <div class="-mx-4 px-4 lg:mx-0 lg:px-0 flex gap-2 overflow-x-auto pb-1 [scrollbar-width:none]">
            @foreach (['' => ['Semua', $stats['total']]] + collect(Ppdb::STATUS)->map(fn ($s, $k) => [$s[0], $stats['status'][$k]])->all() as $key => [$label, $count])
                <a href="{{ route('panel.pendaftar.index', array_filter(['status' => $key, 'q' => $q, 'jalur' => $jalur])) }}"
                    class="shrink-0 rounded-full px-3.5 py-2 text-xs font-medium border {{ (string) $status === (string) $key ? 'bg-primary-900 border-primary-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">
                    {{ $label }} <span class="opacity-60">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        <div class="card overflow-hidden">
            <div class="hidden md:grid grid-cols-12 gap-3 px-5 py-3 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                <span class="col-span-5">Pendaftar</span>
                <span class="col-span-2">Jalur</span>
                <span class="col-span-2">Nilai</span>
                <span class="col-span-3">Status</span>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($applicants as $a)
                    <li>
                        <a href="{{ route('panel.pendaftar.show', $a['no']) }}" class="px-4 md:px-5 py-3.5 flex md:grid md:grid-cols-12 items-center gap-3 hover:bg-slate-50">
                            <div class="flex-1 min-w-0 md:col-span-5">
                                <div class="text-sm font-medium truncate">{{ $a['nama'] }}</div>
                                <div class="text-xs text-slate-500 truncate">
                                    {{ $a['no'] }}<span class="md:hidden"> · {{ $a['jalur_label'] }}</span>
                                </div>
                            </div>
                            <div class="hidden md:block md:col-span-2 text-sm">{{ $a['jalur_label'] }}</div>
                            <div class="hidden md:block md:col-span-2 text-sm">{{ number_format($a['nilai'], 2, ',', '.') }}</div>
                            <div class="md:col-span-3 flex items-center justify-between gap-2">
                                <x-status :status="$a['status']" />
                                <i class="fas fa-chevron-right text-xs text-slate-300 hidden md:inline"></i>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-slate-500">Tidak ada pendaftar yang cocok.</li>
                @endforelse
            </ul>
        </div>
        <p class="text-xs text-slate-500">{{ $applicants->count() }} pendaftar ditampilkan</p>
    </div>
@endsection
