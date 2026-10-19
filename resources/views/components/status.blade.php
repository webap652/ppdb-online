@props(['status', 'type' => 'pendaftar'])
@php
    [$label, $class] = ($type === 'berkas' ? \App\Support\Ppdb::BERKAS_STATUS : \App\Support\Ppdb::STATUS)[$status]
        ?? ['Belum diunggah', 'bg-slate-50 text-slate-500 ring-slate-200'];
@endphp
<span {{ $attributes->class(['badge', $class]) }}>{{ $label }}</span>
