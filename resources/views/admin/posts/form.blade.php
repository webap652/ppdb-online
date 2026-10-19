@extends('layouts.admin')

@section('title', $post->exists ? 'Ubah Informasi' : 'Tambah Informasi')

@section('actions')
    <a href="{{ route('admin.posts.index') }}" class="btn-light">Kembali</a>
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
        class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($post->exists) @method('PUT') @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="card p-5 space-y-4">
                <div>
                    <label class="label" for="title">Judul</label>
                    <input id="title" name="title" value="{{ old('title', $post->title) }}" required class="input text-base">
                    @error('title')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="slug">Slug URL <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="input" placeholder="otomatis dari judul">
                    @error('slug')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="content">Isi</label>
                    <textarea id="content" name="content" class="editor">{{ old('content', $post->content) }}</textarea>
                    @error('content')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-5 space-y-4">
                <div>
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="input">
                        @foreach (\App\Models\Post::STATUSES as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $post->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="published_at">Tanggal Terbit</label>
                    <input id="published_at" type="datetime-local" name="published_at" class="input"
                        value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                    @error('published_at')<p class="error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-floppy-disk"></i> Simpan</button>
            </div>

            <div class="card p-5">
                <div class="label">Kategori</div>
                <div class="space-y-2 mt-2">
                    @foreach ($categories as $category)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-slate-300"
                                @checked(in_array($category->id, old('categories', $selected)))>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
                @error('categories')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="card p-5">
                <label class="label" for="thumbnail">Gambar Sampul</label>
                @if ($post->thumbnail)
                    <img src="{{ media_url($post->thumbnail) }}" alt="" class="w-full rounded-lg mb-3">
                    <label class="flex items-center gap-2 text-sm text-red-600 mb-3">
                        <input type="checkbox" name="remove_thumbnail" value="1" class="rounded border-slate-300"> Hapus gambar
                    </label>
                @endif
                <input id="thumbnail" type="file" name="thumbnail" accept="image/*" class="text-sm w-full">
                <p class="text-xs text-slate-400 mt-2">Jika kosong, gambar pertama atau video YouTube di isi akan dipakai.</p>
                @error('thumbnail')<p class="error">{{ $message }}</p>@enderror
            </div>
        </div>
    </form>

    @include('admin.partials.editor')
@endsection
