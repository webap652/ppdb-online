@extends('layouts.admin')

@section('title', 'Informasi')

@section('actions')
    <a href="{{ route('admin.posts.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Tambah</a>
@endsection

@section('content')
    <form method="GET" class="card p-4 mb-6 grid gap-3 sm:grid-cols-4">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul…" class="input sm:col-span-2">
        <select name="kategori" class="input" onchange="this.form.submit()">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('kategori') === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="input" onchange="this.form.submit()">
            <option value="">Semua status</option>
            @foreach (\App\Models\Post::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="th w-12">No</th>
                    <th class="th">Judul</th>
                    <th class="th">Kategori</th>
                    @if (auth()->user()->isAdmin())<th class="th">Penulis</th>@endif
                    <th class="th">Tanggal</th>
                    <th class="th">Status</th>
                    <th class="th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($posts as $post)
                    <tr class="hover:bg-slate-50">
                        <td class="td text-slate-500">{{ $posts->firstItem() + $loop->index }}</td>
                        <td class="td">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-10 rounded bg-slate-100 overflow-hidden flex-shrink-0">
                                    @if ($post->thumbnail_url)
                                        <img src="{{ $post->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="font-medium hover:text-primary-700 line-clamp-2">{{ $post->title }}</a>
                            </div>
                        </td>
                        <td class="td">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($post->categories as $category)
                                    <span class="badge bg-primary-50 text-primary-700">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        @if (auth()->user()->isAdmin())<td class="td text-slate-600">{{ $post->user?->name ?? '-' }}</td>@endif
                        <td class="td text-slate-600 whitespace-nowrap">{{ ($post->published_at ?? $post->created_at)->format('d/m/Y') }}</td>
                        <td class="td">@include('admin.posts._status', ['post' => $post])</td>
                        <td class="td text-right whitespace-nowrap">
                            @if ($post->status === 'published')
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn-icon" title="Lihat"><i class="fas fa-eye"></i></a>
                            @endif
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn-icon" title="Ubah"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" data-confirm="Hapus informasi ini?">
                                @csrf @method('DELETE')
                                <button class="btn-icon hover:text-red-600" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="td py-12 text-center text-slate-400">Belum ada informasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.partials.table-footer', ['paginator' => $posts])
@endsection
