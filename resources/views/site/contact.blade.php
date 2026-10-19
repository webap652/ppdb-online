@extends('layouts.site')

@section('title', 'Kontak')

@section('content')
    @include('partials.site.page-header', ['title' => 'Kontak', 'crumbs' => ['Kontak' => null]])

    @php
        $items = array_filter([
            ['fa-location-dot', 'Alamat', setting('contact.address'), null],
            ['fa-phone', 'Telepon', setting('contact.phone'), ($p = preg_replace('/[^\d+]/', '', (string) setting('contact.phone'))) ? 'tel:' . $p : null],
            ['fa-envelope', 'Email', setting('contact.email'), setting('contact.email') ? 'mailto:' . setting('contact.email') : null],
            ['fa-brands fa-whatsapp', 'WhatsApp', setting('social.whatsapp'), ($wa = preg_replace('/\D/', '', (string) setting('social.whatsapp'))) ? 'https://wa.me/' . $wa : null],
            ['fa-clock', 'Jam Operasional', setting('contact.hours'), null],
        ], fn ($item) => ! empty($item[2]));
        $map = setting('contact.map_embed');
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-10 grid gap-10 {{ $map ? 'lg:grid-cols-2' : 'max-w-3xl' }}">
        <ul class="divide-y divide-slate-200 border-y border-slate-200">
            @foreach ($items as [$icon, $label, $value, $href])
                <li class="flex gap-4 py-5">
                    <i class="fas {{ $icon }} text-primary-700 text-lg w-6 mt-0.5"></i>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $label }}</div>
                        @if ($href)
                            <a href="{{ $href }}" target="{{ str_starts_with($href, 'http') ? '_blank' : '_self' }}" class="text-slate-800 hover:text-primary-700">{{ $value }}</a>
                        @else
                            <div class="text-slate-800">{{ $value }}</div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        @if ($map)
            <iframe src="{{ $map }}" class="w-full h-80 lg:h-full min-h-80 rounded-lg border border-slate-200" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade" title="Peta lokasi"></iframe>
        @endif
    </div>
@endsection
