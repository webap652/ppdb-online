@extends('layouts.panel')

@section('title', 'Pengguna')

@section('content')
    @php $me = \App\Support\Ppdb::user()['email']; @endphp
    <div class="grid lg:grid-cols-5 gap-4 lg:gap-6 items-start">
        <div class="lg:col-span-3 space-y-3">
            <ul class="card divide-y divide-slate-100">
                @foreach ($accounts as $u)
                    <li class="p-4 flex items-center gap-3">
                        <span class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center text-sm font-semibold {{ $u['aktif'] ? 'bg-primary-50 text-primary-900' : 'bg-slate-100 text-slate-400' }}">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($u['nama'], 0, 1)) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium truncate {{ $u['aktif'] ? '' : 'text-slate-400 line-through' }}">{{ $u['nama'] }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ $u['email'] }} · {{ \App\Support\Ppdb::ROLES[$u['role']] }}</div>
                        </div>
                        @if ($u['email'] === $me)
                            <span class="badge bg-slate-50 text-slate-500 ring-slate-200">Anda</span>
                        @else
                            <form method="POST" action="{{ route('panel.pengguna.toggle') }}" data-confirm="{{ $u['aktif'] ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $u['nama'] }}?">
                                @csrf
                                <input type="hidden" name="email" value="{{ $u['email'] }}">
                                <button type="submit" class="btn-light !py-2 !px-3 text-xs">{{ $u['aktif'] ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
            <p class="text-xs text-slate-500">{{ $jumlahPendaftar }} akun pendaftar dikelola lewat menu Pendaftar.</p>
        </div>

        <form method="POST" action="{{ route('panel.pengguna.store') }}" class="card p-5 space-y-4 lg:col-span-2">
            @csrf
            <h2 class="font-semibold">Tambah Akun</h2>
            <x-field name="nama" label="Nama" />
            <x-field name="email" label="Email" type="email" autocapitalize="none" />
            <x-field name="role" label="Peran" :options="['operator' => 'Panitia Seleksi (Operator)', 'admin' => 'Administrator']" />
            <x-field name="password" label="Password" type="password" minlength="8" autocomplete="new-password" hint="Minimal 8 karakter." />
            <button type="submit" class="btn-primary w-full py-3"><i class="fas fa-plus"></i> Tambah</button>
        </form>
    </div>
@endsection
