@extends('layouts.admin')

@section('title', 'Dasbor')

@section('actions')
    <a href="{{ route('admin.posts.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Tulis Informasi</a>
@endsection

@section('content')
    <p class="mb-6 text-slate-600">Selamat datang, <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>.</p>

    <div class="grid gap-4 grid-cols-2 lg:grid-cols-{{ count($stats) > 4 ? 3 : 4 }} xl:grid-cols-{{ count($stats) }}">
        @foreach ($stats as $stat)
            <div class="card p-5">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-xs font-semibold uppercase tracking-wider">{{ $stat['label'] }}</span>
                    <i class="fas {{ $stat['icon'] }}"></i>
                </div>
                <div class="mt-2 text-2xl font-bold text-primary-900">{{ number_format($stat['value'], 0, ',', '.') }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="card lg:col-span-2 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="font-semibold">Informasi Terbaru</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-primary-700 hover:underline">Semua</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($latestPosts as $post)
                    <li class="px-5 py-3 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="font-medium text-slate-800 hover:text-primary-700 line-clamp-1">{{ $post->title }}</a>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $post->categories->pluck('name')->join(', ') }} &middot; {{ $post->updated_at->diffForHumans() }}
                                @if (auth()->user()->isAdmin() && $post->user) &middot; {{ $post->user->name }} @endif
                            </div>
                        </div>
                        @include('admin.posts._status', ['post' => $post])
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-slate-400">Belum ada informasi.</li>
                @endforelse
            </ul>
        </div>

        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200"><h2 class="font-semibold">Per Kategori</h2></div>
            <ul class="divide-y divide-slate-100">
                @foreach ($categories as $category)
                    <li class="px-5 py-3 flex items-center justify-between text-sm">
                        <a href="{{ route('admin.posts.index', ['kategori' => $category->slug]) }}" class="hover:text-primary-700">{{ $category->name }}</a>
                        <span class="font-semibold text-slate-600">{{ $category->posts_count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
