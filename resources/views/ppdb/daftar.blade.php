@extends('layouts.ppdb')

@section('title', 'Daftar')

@php
    $steps = ['Data Siswa', 'Orang Tua / Wali', 'Sekolah & Jalur', 'Akun'];
@endphp

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8 sm:py-12">
        <h1 class="text-2xl font-bold text-primary-900">Formulir Pendaftaran</h1>
        <p class="text-sm text-slate-500 mt-1">PPDB {{ \App\Support\Ppdb::setting('tahun_ajaran') }} · Isi data sesuai dokumen resmi.</p>

        @unless ($dibuka)
            <div class="mt-6 card p-6 text-center">
                <i class="fas fa-lock text-3xl text-slate-300"></i>
                <h2 class="mt-3 font-semibold">Pendaftaran sedang ditutup</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan pantau jadwal PPDB di halaman beranda.</p>
                <a href="{{ route('home') }}" class="btn-light mt-4">Kembali ke Beranda</a>
            </div>
        @else
            {{-- Indikator langkah --}}
            <div class="mt-6" id="stepper">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-primary-900" data-step-title>{{ $steps[0] }}</span>
                    <span class="text-slate-500">Langkah <span data-step-no>1</span> dari {{ count($steps) }}</span>
                </div>
                <div class="mt-2 grid grid-cols-4 gap-1.5">
                    @foreach ($steps as $i => $s)
                        <div class="h-1.5 rounded-full bg-slate-200" data-step-bar="{{ $i }}"></div>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('daftar.store') }}" id="form-daftar" class="mt-6" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="mb-4 text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">
                        Periksa kembali isian yang ditandai merah.
                    </div>
                @endif

                {{-- 1. Data siswa --}}
                <fieldset data-step class="card p-4 sm:p-6 space-y-4">
                    <legend class="sr-only">{{ $steps[0] }}</legend>
                    <x-field name="nama" label="Nama Lengkap" autocomplete="name" />
                    <x-field name="nisn" label="NISN" inputmode="numeric" maxlength="10" pattern="\d{10}" hint="10 digit, cek di nisn.data.kemdikbud.go.id" />
                    <div class="grid grid-cols-2 gap-3">
                        <x-field name="jk" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" />
                        <x-field name="agama" label="Agama" :options="array_combine($a = ['Kristen', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu'], $a)" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <x-field name="tempat_lahir" label="Tempat Lahir" />
                        <x-field name="tanggal_lahir" label="Tanggal Lahir" type="date" />
                    </div>
                    <x-field name="distrik" label="Distrik" placeholder="Contoh: Pirime" />
                    <x-field name="alamat" label="Alamat / Kampung" type="textarea" />
                </fieldset>

                {{-- 2. Orang tua --}}
                <fieldset data-step class="card p-4 sm:p-6 space-y-4">
                    <legend class="sr-only">{{ $steps[1] }}</legend>
                    <x-field name="nama_ayah" label="Nama Ayah" />
                    <x-field name="nama_ibu" label="Nama Ibu" />
                    <x-field name="pekerjaan_ortu" label="Pekerjaan Orang Tua / Wali" :options="array_combine($p = ['Petani', 'Wiraswasta', 'PNS', 'TNI/Polri', 'Guru', 'Pegawai Swasta', 'Lainnya'], $p)" />
                    <x-field name="hp" label="No. HP / WhatsApp" type="tel" inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" hint="Untuk informasi hasil seleksi." />
                </fieldset>

                {{-- 3. Sekolah asal & jalur --}}
                <fieldset data-step class="card p-4 sm:p-6 space-y-4">
                    <legend class="sr-only">{{ $steps[2] }}</legend>
                    <x-field name="sekolah_asal" label="Sekolah Asal (SD/MI)" />
                    <div class="grid grid-cols-2 gap-3">
                        <x-field name="tahun_lulus" label="Tahun Lulus" inputmode="numeric" maxlength="4" :value="date('Y') + 1" />
                        <x-field name="nilai" label="Rata-rata Rapor" type="number" step="0.01" min="0" max="100" inputmode="decimal" />
                    </div>

                    <div>
                        <span class="label">Jalur Pendaftaran</span>
                        <div class="grid gap-2">
                            @foreach ($jalur as $key => $j)
                                <label class="flex items-start gap-3 rounded-xl border border-slate-300 p-3.5 cursor-pointer has-[:checked]:border-primary-900 has-[:checked]:bg-primary-50">
                                    <input type="radio" name="jalur" value="{{ $key }}" @checked(old('jalur') === $key) required class="mt-1 accent-primary-900">
                                    <span>
                                        <span class="block text-sm font-semibold">{{ $j['label'] }} <span class="font-normal text-slate-400">· {{ $j['kuota'] }} kursi</span></span>
                                        <span class="block text-xs text-slate-500 mt-0.5">{{ $j['ket'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('jalur') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </fieldset>

                {{-- 4. Akun --}}
                <fieldset data-step class="card p-4 sm:p-6 space-y-4">
                    <legend class="sr-only">{{ $steps[3] }}</legend>
                    <p class="text-sm text-slate-500">Akun ini dipakai untuk masuk, mengunggah berkas, dan melihat hasil seleksi.</p>
                    <x-field name="email" label="Email" type="email" autocomplete="email" autocapitalize="none" />
                    <x-field name="password" label="Password" type="password" minlength="8" autocomplete="new-password" hint="Minimal 8 karakter." />
                    <x-field name="password_confirmation" label="Ulangi Password" type="password" minlength="8" autocomplete="new-password" />
                    <label class="flex items-start gap-3 text-sm text-slate-600">
                        <input type="checkbox" name="setuju" value="1" @checked(old('setuju')) required class="mt-0.5 w-4 h-4 accent-primary-900">
                        Saya menyatakan data yang diisi benar dan dapat dipertanggungjawabkan.
                    </label>
                    @error('setuju') <p class="error">{{ $message }}</p> @enderror
                </fieldset>

                {{-- Navigasi langkah: menempel di bawah layar pada HP --}}
                <div class="sticky bottom-0 -mx-4 px-4 py-3 mt-4 bg-slate-50/95 backdrop-blur border-t border-slate-200 sm:static sm:mx-0 sm:px-0 sm:border-0 sm:bg-transparent pb-safe">
                    <div class="flex gap-3">
                        <button type="button" class="btn-light flex-1 sm:flex-none py-3" data-prev hidden><i class="fas fa-arrow-left"></i> Kembali</button>
                        <button type="button" class="btn-primary flex-1 py-3" data-next>Lanjut <i class="fas fa-arrow-right"></i></button>
                        <button type="submit" class="btn-primary flex-1 py-3" data-submit><i class="fas fa-paper-plane"></i> Kirim Pendaftaran</button>
                    </div>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah mendaftar? <a href="{{ route('login') }}" class="font-semibold text-primary-900">Masuk</a>
            </p>
        @endunless
    </div>
@endsection

@if ($dibuka)
    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('form-daftar');
                const steps = [...form.querySelectorAll('[data-step]')];
                const titles = @json($steps);
                const prev = form.querySelector('[data-prev]');
                const next = form.querySelector('[data-next]');
                const submit = form.querySelector('[data-submit]');
                let current = 0;

                function show(i) {
                    current = i;
                    steps.forEach((s, n) => s.classList.toggle('is-active', n === i));
                    document.querySelectorAll('[data-step-bar]').forEach((b, n) => {
                        b.classList.toggle('bg-primary-900', n <= i);
                        b.classList.toggle('bg-slate-200', n > i);
                    });
                    document.querySelector('[data-step-title]').textContent = titles[i];
                    document.querySelector('[data-step-no]').textContent = i + 1;
                    prev.hidden = i === 0;
                    next.hidden = i === steps.length - 1;
                    submit.hidden = i !== steps.length - 1;
                }

                function valid(step) {
                    const fields = [...step.querySelectorAll('input, select, textarea')];
                    const invalid = fields.find((f) => !f.checkValidity());
                    if (invalid) invalid.reportValidity();
                    return !invalid;
                }

                next.addEventListener('click', () => {
                    if (valid(steps[current])) {
                        show(current + 1);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
                prev.addEventListener('click', () => show(current - 1));
                form.addEventListener('submit', (e) => {
                    if (!valid(steps[current])) return e.preventDefault();
                    submit.disabled = true;
                });

                // Kembali ke langkah yang punya error dari server
                const firstError = steps.findIndex((s) => s.querySelector('.error'));
                show(firstError > -1 ? firstError : 0);
            })();
        </script>
    @endpush
@endif
