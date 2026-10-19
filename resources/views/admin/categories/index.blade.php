@extends('layouts.admin')

@section('title', 'Kategori')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Tambah</a>
@endsection

@section('content')
    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="th w-12">No</th>
                    <th class="th w-16">Urutan</th>
                    <th class="th">Nama</th>
                    <th class="th">Slug</th>
                    <th class="th">Menu</th>
                    <th class="th">Jumlah</th>
                    <th class="th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="td text-slate-500">{{ $categories->firstItem() + $loop->index }}</td>
                        <td class="td text-slate-500">{{ $category->sort_order }}</td>
                        <td class="td font-medium">{{ $category->name }}</td>
                        <td class="td text-slate-500">{{ $category->slug }}</td>
                        <td class="td">
                            @if ($category->show_in_menu)
                                <span class="badge bg-emerald-50 text-emerald-700">Tampil</span>
                            @else
                                <span class="badge bg-slate-100 text-slate-600">Tersembunyi</span>
                            @endif
                        </td>
                        <td class="td">
                            <a href="{{ route('admin.posts.index', ['kategori' => $category->slug]) }}" class="text-primary-700 hover:underline">{{ $category->posts_count }}</a>
                        </td>
                        <td class="td text-right whitespace-nowrap">
                            <a href="{{ route('posts.index', ['kategori' => $category->slug]) }}" target="_blank" class="btn-icon" title="Lihat"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-icon" title="Ubah"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                data-confirm="Hapus kategori ini? Informasi di dalamnya tidak ikut terhapus.">
                                @csrf @method('DELETE')
                                <button class="btn-icon hover:text-red-600" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="td py-12 text-center text-slate-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.partials.table-footer', ['paginator' => $categories])
@endsection
