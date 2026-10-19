@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
    <form method="POST" action="{{ route('admin.profile.update') }}" class="card p-5 max-w-xl space-y-4">
        @csrf @method('PUT')

        <div class="flex items-center gap-2 text-sm text-slate-500">
            Peran: <span class="badge bg-primary-50 text-primary-700">{{ $user->role_label }}</span>
        </div>
        <div>
            <label class="label" for="name">Nama</label>
            <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="input">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </div>

        <div class="border-t border-slate-200 pt-4">
            <h2 class="text-sm font-semibold mb-3">Ganti Password</h2>
            <div class="space-y-4">
                <div>
                    <label class="label" for="current_password">Password Saat Ini</label>
                    <input id="current_password" type="password" name="current_password" class="input" autocomplete="current-password">
                    @error('current_password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label" for="password">Password Baru</label>
                        <input id="password" type="password" name="password" class="input" autocomplete="new-password">
                        @error('password')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="password_confirmation">Ulangi Password Baru</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="input" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-2"><button type="submit" class="btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
    </form>
@endsection
