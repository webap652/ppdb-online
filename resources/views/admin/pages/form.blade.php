@extends('layouts.admin')

@section('title', $page->exists ? 'Ubah Halaman' : 'Tambah Halaman')

@section('actions')
    <a href="{{ route('admin.pages.index') }}" class="btn-light">Kembali</a>
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
        class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <div class="lg:col-span-2 card p-5 space-y-4">
            <div>
                <label class="label" for="title">Judul</label>
                <input id="title" name="title" value="{{ old('title', $page->title) }}" required class="input text-base">
                @error('title')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label" for="slug">Slug URL <span class="text-slate-400 font-normal">(opsional)</span></label>
                <input id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="input" placeholder="otomatis dari judul">
                @error('slug')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label" for="content">Isi</label>
                <textarea id="content" name="content" class="editor">{{ old('content', $page->content) }}</textarea>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-5 space-y-4">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-slate-300" @checked(old('is_published', $page->is_published))>
                    Terbitkan
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="show_in_menu" value="1" class="rounded border-slate-300" @checked(old('show_in_menu', $page->show_in_menu))>
                    Tampilkan di menu Profil
                </label>
                <div>
                    <label class="label" for="sort_order">Urutan</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" class="input w-32">
                </div>
                <button type="submit" class="btn-primary w-full"><i class="fas fa-floppy-disk"></i> Simpan</button>
            </div>

            <div class="card p-5">
                <label class="label" for="image">Gambar Utama <span class="text-slate-400 font-normal">(opsional)</span></label>
                @if ($page->image)
                    <img src="{{ media_url($page->image) }}" alt="" class="w-full rounded-lg mb-3">
                    <label class="flex items-center gap-2 text-sm text-red-600 mb-3">
                        <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300"> Hapus gambar
                    </label>
                @endif
                <input id="image" type="file" name="image" accept="image/*" class="text-sm w-full">
                @error('image')<p class="error">{{ $message }}</p>@enderror
            </div>
        </div>
    </form>

    @include('admin.partials.editor')
@endsection
