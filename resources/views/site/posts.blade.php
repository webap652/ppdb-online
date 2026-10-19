@extends('layouts.site')

@section('title', $category ? $category->name : 'Informasi')

@section('content')
    @include('partials.site.page-header', [
        'title' => 'Informasi',
        'crumbs' => $category ? ['Informasi' => route('posts.index'), $category->name => null] : ['Informasi' => null],
    ])

    @php
        $chip = fn ($active) => 'px-3 py-1.5 rounded-full border ' . ($active
            ? 'bg-primary-900 border-primary-900 text-white'
            : 'border-slate-200 text-slate-600 hover:border-primary-700');
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('posts.index', array_filter(['q' => $q])) }}" class="{{ $chip(! $category) }}">Semua</a>
                @foreach ($menuCategories as $menuCategory)
                    <a href="{{ route('posts.index', array_filter(['kategori' => $menuCategory->slug, 'q' => $q])) }}"
                        class="{{ $chip($category?->is($menuCategory)) }}">{{ $menuCategory->name }}</a>
                @endforeach
            </div>
            <form action="{{ route('posts.index') }}" method="GET" class="relative md:w-72">
                @if ($category)
                    <input type="hidden" name="kategori" value="{{ $category->slug }}">
                @endif
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari informasi…"
                    class="w-full rounded-full border border-slate-200 pl-4 pr-10 py-2 text-sm focus:outline-none focus:border-primary-700">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" aria-label="Cari"><i class="fas fa-magnifying-glass"></i></button>
            </form>
        </div>

        @if ($q !== '')
            <p class="mb-6 text-sm text-slate-500">
                {{ $posts->total() }} hasil untuk <span class="font-semibold text-slate-800">"{{ $q }}"</span>
                <a href="{{ route('posts.index', array_filter(['kategori' => $category?->slug])) }}" class="ml-2 text-primary-700 hover:underline">Hapus pencarian</a>
            </p>
        @endif

        @if ($posts->isEmpty())
            <p class="py-16 text-center text-slate-400">Belum ada informasi.</p>
        @else
            <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @include('partials.site.post-card', ['post' => $post])
                @endforeach
            </div>
            <div class="mt-12">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
