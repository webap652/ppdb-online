@extends('layouts.admin')

@section('title', $category->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('actions')
    <a href="{{ route('admin.categories.index') }}" class="btn-light">Kembali</a>
@endsection

@section('content')
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        class="card p-5 max-w-xl space-y-4">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div>
            <label class="label" for="name">Nama</label>
            <input id="name" name="name" value="{{ old('name', $category->name) }}" required class="input">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label" for="slug">Slug URL <span class="text-slate-400 font-normal">(opsional)</span></label>
            <input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="input" placeholder="otomatis dari nama">
            @error('slug')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label" for="sort_order">Urutan</label>
            <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="input w-32">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="show_in_menu" value="1" class="rounded border-slate-300" @checked(old('show_in_menu', $category->show_in_menu))>
            Tampilkan di menu & beranda
        </label>

        <div class="pt-2"><button type="submit" class="btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
    </form>
@endsection
