@extends('layouts.admin')

@section('title', $user->exists ? 'Ubah Akun' : 'Tambah Akun')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn-light">Kembali</a>
@endsection

@section('content')
    @php $isSelf = $user->exists && $user->is(auth()->user()); @endphp

    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
        class="card p-5 max-w-xl space-y-4">
        @csrf
        @if ($user->exists) @method('PUT') @endif

        <div>
            <label class="label" for="name">Nama</label>
            <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="input">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="input" autocomplete="off">
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label" for="role">Peran</label>
            <select id="role" name="role" class="input" @disabled($isSelf)>
                @foreach (\App\Models\User::ROLES as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @if ($isSelf)<input type="hidden" name="role" value="{{ $user->role }}">@endif
            <p class="text-xs text-slate-400 mt-1">Operator hanya dapat mengelola informasi miliknya sendiri.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="password">Password {{ $user->exists ? '(kosongkan jika tetap)' : '' }}</label>
                <input id="password" type="password" name="password" class="input" autocomplete="new-password" @required(! $user->exists)>
                @error('password')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label" for="password_confirmation">Ulangi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="input" autocomplete="new-password">
            </div>
        </div>
        @unless ($isSelf)
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $user->is_active))>
                Akun aktif (bisa login)
            </label>
        @else
            <input type="hidden" name="is_active" value="1">
        @endunless

        <div class="pt-2"><button type="submit" class="btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
    </form>
@endsection
