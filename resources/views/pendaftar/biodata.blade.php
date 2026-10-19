@extends('layouts.panel')

@section('title', 'Biodata')

@section('content')
    <div class="max-w-2xl space-y-4">
        @include('pendaftar.partials.biodata')

        <p class="text-xs text-slate-500">
            <i class="fas fa-circle-info mr-1"></i> Ada kesalahan data? Hubungi panitia PPDB di {{ \App\Support\Ppdb::sekolah('telepon') }}.
        </p>
    </div>
@endsection
