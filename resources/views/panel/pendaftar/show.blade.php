@extends('layouts.panel')

@section('title', $applicant['nama'])
@section('back', route('panel.pendaftar.index'))

@php $bisaVerifikasi = in_array($applicant['status'], ['menunggu', 'perbaikan', 'terverifikasi'], true); @endphp

@section('content')
    <div class="grid lg:grid-cols-5 gap-4 lg:gap-6 items-start">
        {{-- Ringkasan + verifikasi --}}
        <div class="lg:col-span-2 lg:order-2 space-y-4 lg:sticky lg:top-24">
            <div class="card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs text-slate-500">{{ $applicant['no'] }}</div>
                        <div class="font-semibold">Jalur {{ $applicant['jalur_label'] }}</div>
                    </div>
                    <x-status :status="$applicant['status']" />
                </div>
                <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-xs text-slate-500">Nilai Rapor</dt><dd class="font-semibold">{{ number_format($applicant['nilai'], 2, ',', '.') }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Terdaftar</dt><dd class="font-semibold">{{ \Illuminate\Support\Carbon::parse($applicant['tanggal_daftar'])->translatedFormat('d M Y') }}</dd></div>
                </dl>
                @if ($applicant['catatan'])
                    <p class="mt-4 rounded-xl bg-slate-50 p-3 text-sm text-slate-600"><span class="font-medium">Catatan:</span> {{ $applicant['catatan'] }}</p>
                @endif
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $applicant['hp']) }}" target="_blank" rel="noopener" class="btn-light w-full mt-4">
                    <i class="fab fa-whatsapp text-emerald-600"></i> Hubungi {{ $applicant['hp'] }}
                </a>
            </div>

            <form method="POST" action="{{ route('panel.pendaftar.verifikasi', $applicant['no']) }}" class="card">
                @csrf
                <div class="px-5 pt-4 pb-2 flex items-center justify-between">
                    <h2 class="font-semibold">Verifikasi Berkas</h2>
                    @unless ($lengkap)
                        <span class="text-xs text-orange-600">Berkas wajib belum lengkap</span>
                    @endunless
                </div>

                <ul class="divide-y divide-slate-100">
                    @foreach ($berkas as $key => $b)
                        @php $st = $applicant['berkas'][$key] ?? null; @endphp
                        <li class="px-5 py-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-medium">{{ $b['label'] }} @if ($b['wajib'])<span class="text-rose-500">*</span>@endif</span>
                                @unless ($st && $bisaVerifikasi)
                                    <x-status :status="$st" type="berkas" />
                                @endunless
                            </div>
                            @if ($st && $bisaVerifikasi)
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    @foreach (['valid' => ['Valid', 'has-[:checked]:bg-emerald-600 has-[:checked]:border-emerald-600'], 'ditolak' => ['Tolak', 'has-[:checked]:bg-rose-600 has-[:checked]:border-rose-600']] as $val => [$label, $cls])
                                        <label class="cursor-pointer rounded-xl border border-slate-300 py-2 text-center text-sm font-medium has-[:checked]:text-white {{ $cls }}">
                                            <input type="radio" name="berkas[{{ $key }}]" value="{{ $val }}" class="sr-only" @checked($st === $val)> {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if ($bisaVerifikasi)
                    <div class="p-5 border-t border-slate-100 space-y-3">
                        @error('aksi') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror
                        <div>
                            <label for="catatan" class="label">Catatan untuk pendaftar</label>
                            <textarea id="catatan" name="catatan" rows="2" class="input" placeholder="Wajib diisi jika meminta perbaikan">{{ old('catatan', $applicant['status'] === 'perbaikan' ? $applicant['catatan'] : '') }}</textarea>
                            @error('catatan') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="submit" name="aksi" value="perbaikan" class="btn-light py-3"><i class="fas fa-rotate-left text-orange-500"></i> Perbaikan</button>
                            <button type="submit" name="aksi" value="terverifikasi" class="btn-success py-3"><i class="fas fa-check"></i> Verifikasi</button>
                        </div>
                    </div>
                @else
                    <p class="px-5 py-4 border-t border-slate-100 text-xs text-slate-500"><i class="fas fa-lock mr-1"></i> Keputusan seleksi sudah ditetapkan.</p>
                @endif
            </form>
        </div>

        {{-- Biodata --}}
        <div class="lg:col-span-3 lg:order-1 space-y-4">
            @include('pendaftar.partials.biodata')
        </div>
    </div>
@endsection
