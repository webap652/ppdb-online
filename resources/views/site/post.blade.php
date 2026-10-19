@extends('layouts.site')

@section('title', $post->title)
@section('description', $post->excerpt)
@if ($post->thumbnail_url)
    @section('og_image', $post->thumbnail_url)
@endif

@section('content')
    @php $firstCategory = $post->categories->first(); @endphp

    @include('partials.site.page-header', [
        'title' => $post->title,
        'crumbs' => array_filter([
            'Informasi' => route('posts.index'),
            ($firstCategory?->name ?? '') => $firstCategory ? route('posts.index', ['kategori' => $firstCategory->slug]) : null,
        ], fn ($url, $label) => $label !== '', ARRAY_FILTER_USE_BOTH),
    ])

    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500 mb-8">
            @foreach ($post->categories as $cat)
                <a href="{{ route('posts.index', ['kategori' => $cat->slug]) }}" class="font-semibold text-primary-700 hover:underline">{{ $cat->name }}</a>
            @endforeach
            <span>&middot;</span>
            <time><i class="far fa-calendar mr-1"></i>{{ $post->date_label }}</time>
            @if ($post->user)
                <span>&middot;</span>
                <span><i class="far fa-user mr-1"></i>{{ $post->user->name }}</span>
            @endif
            <span>&middot;</span>
            <span><i class="far fa-eye mr-1"></i>{{ number_format($post->views, 0, ',', '.') }}</span>
        </div>

        @if ($post->thumbnail)
            <img src="{{ media_url($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full rounded-lg mb-8">
        @endif

        <div class="prose prose-slate max-w-none prose-a:text-primary-700 prose-headings:text-primary-900">
            {!! $post->content !!}
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="max-w-6xl mx-auto px-4 pt-6">
            <h2 class="text-xl font-bold text-primary-900 mb-6 border-b border-slate-200 pb-3">Informasi Terkait</h2>
            <div class="grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $item)
                    @include('partials.site.post-card', ['post' => $item])
                @endforeach
            </div>
        </section>
    @endif
@endsection
