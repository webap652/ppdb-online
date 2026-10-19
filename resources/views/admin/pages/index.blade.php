@extends('layouts.admin')

@section('title', 'Halaman')

@section('actions')
    <a href="{{ route('admin.pages.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Tambah</a>
@endsection

@section('content')
    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="th w-12">No</th>
                    <th class="th w-16">Urutan</th>
                    <th class="th">Judul</th>
                    <th class="th">URL</th>
                    <th class="th">Status</th>
                    <th class="th">Menu Profil</th>
                    <th class="th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pages as $page)
                    <tr class="hover:bg-slate-50">
                        <td class="td text-slate-500">{{ $pages->firstItem() + $loop->index }}</td>
                        <td class="td text-slate-500">{{ $page->sort_order }}</td>
                        <td class="td font-medium"><a href="{{ route('admin.pages.edit', $page) }}" class="hover:text-primary-700">{{ $page->title }}</a></td>
                        <td class="td text-slate-500">/halaman/{{ $page->slug }}</td>
                        <td class="td">
                            @if ($page->is_published)
                                <span class="badge bg-emerald-50 text-emerald-700">Terbit</span>
                            @else
                                <span class="badge bg-slate-100 text-slate-600">Draf</span>
                            @endif
                        </td>
                        <td class="td">{!! $page->show_in_menu ? '<i class="fas fa-check text-emerald-600"></i>' : '<span class="text-slate-300">—</span>' !!}</td>
                        <td class="td text-right whitespace-nowrap">
                            @if ($page->is_published)
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn-icon" title="Lihat"><i class="fas fa-eye"></i></a>
                            @endif
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-icon" title="Ubah"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" data-confirm="Hapus halaman ini?">
                                @csrf @method('DELETE')
                                <button class="btn-icon hover:text-red-600" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="td py-12 text-center text-slate-400">Belum ada halaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.partials.table-footer', ['paginator' => $pages])
@endsection
