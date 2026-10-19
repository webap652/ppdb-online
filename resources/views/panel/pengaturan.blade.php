@extends('layouts.panel')

@section('title', 'Pengaturan PPDB')

@section('content')
    <form method="POST" action="{{ route('panel.pengaturan.update') }}" class="max-w-2xl space-y-4">
        @csrf
        @method('PUT')

        <section class="card p-5 space-y-4">
            <h2 class="font-semibold">Umum</h2>
            <div class="grid grid-cols-2 gap-3">
                <x-field name="tahun_ajaran" label="Tahun Ajaran" :value="$setting['tahun_ajaran']" placeholder="2027/2028" />
                <x-field name="kuota" label="Kuota Total" type="number" inputmode="numeric" min="1" :value="$setting['kuota']" />
            </div>

            @foreach ([
                'pendaftaran_dibuka' => ['Pendaftaran dibuka', 'Calon siswa dapat mengisi formulir pendaftaran.'],
                'pengumuman_dirilis' => ['Pengumuman dirilis', 'Hasil seleksi dapat dilihat publik.'],
            ] as $key => [$label, $ket])
                <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-4 cursor-pointer">
                    <span>
                        <span class="block text-sm font-medium">{{ $label }}</span>
                        <span class="block text-xs text-slate-500">{{ $ket }}</span>
                    </span>
                    <span class="relative shrink-0">
                        <input type="checkbox" name="{{ $key }}" value="1" class="peer sr-only" @checked(old($key, $setting[$key]))>
                        <span class="block w-11 h-6 rounded-full bg-slate-300 peer-checked:bg-emerald-600 transition"></span>
                        <span class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
                    </span>
                </label>
            @endforeach
        </section>

        <section class="card p-5">
            <h2 class="font-semibold">Jadwal</h2>
            <div class="mt-4 space-y-4">
                @foreach ($jadwal as $i => $j)
                    <div class="rounded-xl border border-slate-200 p-4 space-y-3">
                        <x-field name="jadwal[{{ $i }}][tahap]" label="Tahap" :value="$j['tahap']" class="[&_label]:sr-only" />
                        <div class="grid grid-cols-2 gap-3">
                            <x-field name="jadwal[{{ $i }}][mulai]" label="Mulai" type="date" :value="$j['mulai']" />
                            <x-field name="jadwal[{{ $i }}][selesai]" label="Selesai" type="date" :value="$j['selesai']" />
                        </div>
                        @foreach (['tahap', 'mulai', 'selesai'] as $f)
                            @error("jadwal.$i.$f") <p class="error">{{ $message }}</p> @enderror
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card p-5">
            <h2 class="font-semibold">Pembagian Kuota per Jalur</h2>
            <p class="text-xs text-slate-500">Dihitung otomatis dari kuota total (persentase di config/ppdb.php).</p>
            <ul class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach ($jalur as $j)
                    <li class="rounded-xl bg-slate-50 p-3">
                        <div class="text-xs text-slate-500">{{ $j['label'] }} · {{ $j['persen'] }}%</div>
                        <div class="text-lg font-bold">{{ $j['kuota'] }}</div>
                    </li>
                @endforeach
            </ul>
        </section>

        <div class="sticky bottom-20 lg:bottom-4">
            <button type="submit" class="btn-primary w-full sm:w-auto py-3 px-6 shadow-lg"><i class="fas fa-floppy-disk"></i> Simpan Pengaturan</button>
        </div>
    </form>
@endsection
