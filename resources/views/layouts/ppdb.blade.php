@php
    use App\Support\Ppdb;

    $title = trim($__env->yieldContent('title'));
    $authUser = Ppdb::user();
    $nav = [
        ['Beranda', 'home'],
        ['Pengumuman', 'pengumuman'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    @include('ppdb.partials.head')
    @stack('head')
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 h-14 sm:h-16 flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 min-w-0">
                <img src="{{ asset(Ppdb::sekolah('logo')) }}" alt="Logo" class="w-8 h-8 sm:w-9 sm:h-9 object-contain shrink-0">
                <span class="leading-tight min-w-0">
                    <span class="block text-sm font-bold text-primary-900 truncate">PPDB Online</span>
                    <span class="block text-[11px] text-slate-500 truncate">{{ Ppdb::sekolah('nama') }}</span>
                </span>
            </a>

            <nav class="hidden sm:flex items-center gap-1 text-sm">
                @foreach ($nav as [$label, $route])
                    <a href="{{ route($route) }}" class="px-3 py-2 rounded-lg font-medium {{ request()->routeIs($route) ? 'text-primary-900 bg-primary-50' : 'text-slate-600 hover:text-primary-900' }}">{{ $label }}</a>
                @endforeach
                @if ($authUser)
                    <a href="{{ route(Ppdb::homeRoute($authUser['role'])) }}" class="btn-primary ml-2 !py-2"><i class="fas fa-gauge"></i> Dasbor</a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg font-medium {{ request()->routeIs('login') ? 'text-primary-900 bg-primary-50' : 'text-slate-600 hover:text-primary-900' }}">Masuk</a>
                    <a href="{{ route('daftar') }}" class="btn-primary ml-2 !py-2">Daftar</a>
                @endif
            </nav>

            <button type="button" id="nav-toggle" class="sm:hidden w-10 h-10 -mr-2 inline-flex items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100" aria-label="Menu" aria-expanded="false">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>

        {{-- Menu HP --}}
        <nav id="nav-mobile" class="sm:hidden hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1">
            @foreach ($nav as [$label, $route])
                <a href="{{ route($route) }}" class="block px-3 py-3 rounded-xl text-sm font-medium {{ request()->routeIs($route) ? 'bg-primary-50 text-primary-900' : 'text-slate-700' }}">{{ $label }}</a>
            @endforeach
            @if ($authUser)
                <a href="{{ route(Ppdb::homeRoute($authUser['role'])) }}" class="btn-primary w-full mt-2">Ke Dasbor</a>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="btn-light">Masuk</a>
                    <a href="{{ route('daftar') }}" class="btn-primary">Daftar</a>
                </div>
            @endif
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-5xl mx-auto px-4 py-6 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} {{ Ppdb::sekolah('nama') }}</p>
            <p class="flex flex-wrap gap-x-4 gap-y-1">
                <span><i class="fas fa-phone mr-1"></i>{{ Ppdb::sekolah('telepon') }}</span>
                <span><i class="fas fa-envelope mr-1"></i>{{ Ppdb::sekolah('email') }}</span>
            </p>
        </div>
    </footer>

    <script>
        document.getElementById('nav-toggle').addEventListener('click', function () {
            const menu = document.getElementById('nav-mobile');
            menu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', !menu.classList.contains('hidden'));
        });
    </script>
    @stack('scripts')
</body>

</html>
