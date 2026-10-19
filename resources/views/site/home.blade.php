@extends('layouts.site')

@section('content')
    @php $heroImage = \App\Models\Setting::url('home.hero_image', 'images/hero-bg/hero bg (2).png'); @endphp

    <section class="relative bg-primary-900">
        <img src="{{ $heroImage }}" alt="{{ $siteName }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
        <div class="relative max-w-6xl mx-auto px-4 py-24 md:py-32">
            <h1 class="max-w-2xl text-3xl md:text-5xl font-bold text-white leading-tight">
                {{ setting('home.hero_title', $siteName) }}
            </h1>
            @if ($siteTagline)
                <p class="mt-4 text-gold-400 font-medium">{{ $siteTagline }}</p>
            @endif
        </div>
    </section>

    @forelse ($sections as $section)
        <section class="max-w-6xl mx-auto px-4 pt-14">
            <div class="flex items-end justify-between gap-4 mb-6 border-b border-slate-200 pb-3">
                <h2 class="text-xl md:text-2xl font-bold text-primary-900">{{ $section->name }}</h2>
                <a href="{{ route('posts.index', ['kategori' => $section->slug]) }}" class="text-sm font-medium text-primary-700 hover:underline whitespace-nowrap">
                    Lihat semua <i class="fas fa-arrow-right text-xs ml-1"></i>
                </a>
            </div>
            <div class="grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($section->latestPosts as $post)
                    @include('partials.site.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @empty
        <section class="max-w-6xl mx-auto px-4 py-20 text-center text-slate-400">
            Belum ada informasi yang diterbitkan.
        </section>
    @endforelse
@endsection
