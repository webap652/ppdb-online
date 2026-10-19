@extends('layouts.admin')

@section('title', 'Akun & Operator')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Tambah Akun</a>
@endsection

@section('content')
    <div class="flex gap-2 mb-6 text-sm">
        @foreach (['' => 'Semua'] + \App\Models\User::ROLES as $value => $label)
            <a href="{{ route('admin.users.index', array_filter(['role' => $value])) }}"
                class="px-3 py-1.5 rounded-full border {{ request('role', '') === $value ? 'bg-primary-900 border-primary-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="th w-12">No</th>
                    <th class="th">Nama</th>
                    <th class="th">Email</th>
                    <th class="th">Peran</th>
                    <th class="th">Status</th>
                    <th class="th">Informasi</th>
                    <th class="th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="td text-slate-500">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="td font-medium">
                            {{ $user->name }}
                            @if ($user->is(auth()->user()))<span class="text-xs text-slate-400">(Anda)</span>@endif
                        </td>
                        <td class="td text-slate-600">{{ $user->email }}</td>
                        <td class="td">
                            <span class="badge {{ $user->isAdmin() ? 'bg-primary-50 text-primary-700' : 'bg-slate-100 text-slate-600' }}">{{ $user->role_label }}</span>
                        </td>
                        <td class="td">
                            @if ($user->is_active)
                                <span class="badge bg-emerald-50 text-emerald-700">Aktif</span>
                            @else
                                <span class="badge bg-red-50 text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="td text-slate-600">{{ $user->posts_count }}</td>
                        <td class="td text-right whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon" title="Ubah"><i class="fas fa-pen"></i></a>
                            @unless ($user->is(auth()->user()))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" data-confirm="Hapus akun {{ $user->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn-icon hover:text-red-600" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="td py-12 text-center text-slate-400">Belum ada akun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.partials.table-footer', ['paginator' => $users])
@endsection
