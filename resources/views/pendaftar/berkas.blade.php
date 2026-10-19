@extends('layouts.panel')

@section('title', 'Berkas')

@php $terkunci = in_array($applicant['status'], ['diterima', 'ditolak', 'terverifikasi'], true); @endphp

@section('content')
    <div class="max-w-2xl space-y-4">
        <p class="text-sm text-slate-500">
            Format JPG, PNG, atau PDF, maksimal 2 MB. Pastikan dokumen terbaca jelas.
        </p>

        @if ($errors->any())
            <div class="text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">{{ $errors->first() }}</div>
        @endif

        @if ($applicant['status'] === 'perbaikan' && $applicant['catatan'])
            <div class="rounded-xl bg-orange-50 border border-orange-200 p-3 text-sm text-orange-800">
                <span class="font-semibold">Catatan panitia:</span> {{ $applicant['catatan'] }}
            </div>
        @endif

        <ul class="card divide-y divide-slate-100">
            @foreach ($berkas as $key => $b)
                @php $st = $applicant['berkas'][$key] ?? null; @endphp
                <li class="p-4 flex items-center gap-3">
                    <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center {{ $st === 'valid' ? 'bg-emerald-50 text-emerald-600' : ($st === 'ditolak' ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-400') }}">
                        <i class="fas {{ $st === 'valid' ? 'fa-circle-check' : ($st === 'ditolak' ? 'fa-circle-xmark' : 'fa-file') }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate">{{ $b['label'] }}</div>
                        <div class="mt-1 flex items-center gap-2">
                            <x-status :status="$st" type="berkas" />
                            @if ($b['wajib']) <span class="text-[11px] text-slate-400">Wajib</span> @endif
                        </div>
                    </div>

                    @unless ($terkunci || $st === 'valid')
                        <form method="POST" action="{{ route('pendaftar.berkas.unggah') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="jenis" value="{{ $key }}">
                            <label class="btn-light !px-3 cursor-pointer">
                                <i class="fas fa-upload"></i>
                                <span class="hidden sm:inline">{{ $st ? 'Ganti' : 'Unggah' }}</span>
                                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" class="sr-only" onchange="this.form.submit()" required>
                            </label>
                        </form>
                    @endunless
                </li>
            @endforeach
        </ul>

        @if ($terkunci)
            <p class="text-xs text-slate-500"><i class="fas fa-lock mr-1"></i> Berkas sudah diverifikasi dan tidak dapat diubah.</p>
        @endif
    </div>
@endsection
