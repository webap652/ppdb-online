@extends('layouts.panel')

@section('title', 'Kartu Pendaftaran')

@section('actions')
    <button type="button" onclick="window.print()" class="btn-primary !py-2 hidden sm:inline-flex"><i class="fas fa-print"></i> Cetak</button>
@endsection

@push('head')
    <style>
        @media print {
            body * { visibility: hidden; }
            #kartu, #kartu * { visibility: visible; }
            #kartu { position: absolute; inset: 0 auto auto 0; width: 100%; border: 0; }
        }
    </style>
@endpush

@section('content')
    @php $s = \App\Support\Ppdb::sekolah(); @endphp
    <div class="max-w-lg">
        <div id="kartu" class="card overflow-hidden">
            <div class="bg-primary-900 text-white p-5 flex items-center gap-3">
                <img src="{{ asset($s['logo']) }}" alt="" class="w-12 h-12 object-contain bg-white rounded-xl p-1">
                <div class="min-w-0">
                    <div class="text-[11px] uppercase tracking-wider text-slate-300">Bukti Pendaftaran PPDB {{ \App\Support\Ppdb::setting('tahun_ajaran') }}</div>
                    <div class="font-bold truncate">{{ $s['nama'] }}</div>
                </div>
            </div>

            <div class="p-5">
                <div class="text-center py-3 rounded-xl bg-slate-50">
                    <div class="text-xs text-slate-500">Nomor Pendaftaran</div>
                    <div class="text-2xl font-bold tracking-wider text-primary-900">{{ $applicant['no'] }}</div>
                </div>

                <dl class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div class="col-span-2"><dt class="text-xs text-slate-500">Nama</dt><dd class="font-semibold">{{ $applicant['nama'] }}</dd></div>
                    <div><dt class="text-xs text-slate-500">NISN</dt><dd class="font-medium">{{ $applicant['nisn'] }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Jalur</dt><dd class="font-medium">{{ $applicant['jalur_label'] }}</dd></div>
                    <div class="col-span-2"><dt class="text-xs text-slate-500">Sekolah Asal</dt><dd class="font-medium">{{ $applicant['sekolah_asal'] }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Tanggal Daftar</dt><dd class="font-medium">{{ \Illuminate\Support\Carbon::parse($applicant['tanggal_daftar'])->translatedFormat('d F Y') }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Status</dt><dd class="font-medium text-emerald-700"><i class="fas fa-circle-check"></i> Terdaftar</dd></div>
                </dl>

                <p class="mt-5 pt-4 border-t border-dashed border-slate-200 text-xs text-slate-500">
                    Simpan kartu ini dan bawa saat daftar ulang. Hasil seleksi dapat dicek di {{ route('pengumuman') }}.
                </p>
            </div>
        </div>

        <button type="button" onclick="window.print()" class="btn-primary w-full mt-4 py-3 sm:hidden"><i class="fas fa-print"></i> Cetak / Simpan PDF</button>
    </div>
@endsection
