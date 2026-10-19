@extends('layouts.ppdb')

@section('title', 'Masuk')

@section('content')
    <div class="max-w-sm mx-auto px-4 py-10 sm:py-16">
        <h1 class="text-2xl font-bold text-primary-900">Masuk</h1>
        <p class="text-sm text-slate-500 mt-1">Masuk sebagai pendaftar, panitia, atau admin.</p>

        <x-alert class="mt-6" />

        <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
            @csrf
            @error('login')
                <div class="text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">{{ $message }}</div>
            @enderror

            <div>
                <label for="login" class="label">Email atau No. Pendaftaran</label>
                <input id="login" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" autocapitalize="none"
                    class="input" placeholder="nama@email.com / PPDB-2027-0001">
            </div>
            <div>
                <label for="password" class="label">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="input pr-12">
                    <button type="button" data-toggle-password="password" class="absolute inset-y-0 right-0 w-12 text-slate-400 hover:text-slate-600" aria-label="Tampilkan password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-primary w-full py-3">Masuk</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('daftar') }}" class="font-semibold text-primary-900">Daftar PPDB</a>
        </p>

        {{-- Khusus prototype --}}
        <div class="mt-10 rounded-2xl border border-dashed border-slate-300 p-4 text-xs text-slate-500">
            <div class="font-semibold text-slate-600 mb-2"><i class="fas fa-flask mr-1"></i> Akun demo (password: <code>password</code>)</div>
            <div class="grid gap-1.5">
                @foreach (config('ppdb.akun') as $akun)
                    <button type="button" class="text-left flex justify-between gap-2 rounded-lg px-2 py-1.5 hover:bg-slate-100" data-demo="{{ $akun['email'] }}">
                        <span class="truncate">{{ $akun['email'] }}</span>
                        <span class="text-slate-400">{{ \App\Support\Ppdb::ROLES[$akun['role']] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((btn) => btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.togglePassword);
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.querySelector('i').className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }));
        document.querySelectorAll('[data-demo]').forEach((btn) => btn.addEventListener('click', () => {
            document.getElementById('login').value = btn.dataset.demo;
            document.getElementById('password').value = 'password';
        }));
    </script>
@endpush
