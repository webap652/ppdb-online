@extends('layouts.site')

@section('title', $page->title)

@section('content')
    @include('partials.site.page-header', [
        'title' => $page->title,
        'crumbs' => ['Profil' => null, $page->title => null],
    ])

    <div class="max-w-3xl mx-auto px-4 py-10">
        @if ($page->image)
            <img src="{{ media_url($page->image) }}" alt="{{ $page->title }}" class="w-full rounded-lg mb-8">
        @endif

        <div class="prose prose-slate max-w-none prose-a:text-primary-700 prose-headings:text-primary-900">
            {!! $page->content !!}
        </div>
    </div>
@endsection
