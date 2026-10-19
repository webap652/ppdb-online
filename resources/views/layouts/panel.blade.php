@php
    use App\Support\Ppdb;

    $title = trim($__env->yieldContent('title'));
    $authUser = Ppdb::user();
    $role = $authUser['role'];

    // [label, route, ikon, pola route aktif]
    $menu = match ($role) {
        'pendaftar' => [
            ['Beranda', 'pendaftar.dashboard', 'fa-house', 'pendaftar.dashboard'],
            ['Biodata', 'pendaftar.biodata', 'fa-id-card', 'pendaftar.biodata'],
            ['Berkas', 'pendaftar.berkas', 'fa-folder-open', 'pendaftar.berkas*'],
            ['Kartu', 'pendaftar.kartu', 'fa-print', 'pendaftar.kartu'],
        ],
        default => array_filter([
            ['Dasbor', 'panel.dashboard', 'fa-gauge', 'panel.dashboard'],
            ['Pendaftar', 'panel.pendaftar.index', 'fa-users', 'panel.pendaftar.*'],
            ['Seleksi', 'panel.seleksi', 'fa-ranking-star', 'panel.seleksi*'],
            $role === 'admin' ? ['Pengguna', 'panel.pengguna.index', 'fa-user-shield', 'panel.pengguna.*'] : null,
            $role === 'admin' ? ['Pengaturan', 'panel.pengaturan.edit', 'fa-gear', 'panel.pengaturan.*'] : null,
        ]),
    };
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    @include('ppdb.partials.head')
    @stack('head')
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800">
    <div class="min-h-screen lg:flex">
        {{-- Sidebar (desktop) --}}
        <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-primary-900 text-slate-300 sticky top-0 h-screen">
            <a href="{{ route('home') }}" class="h-16 px-5 flex items-center gap-3 border-b border-white/10">
                <img src="{{ asset(Ppdb::sekolah('logo')) }}" alt="Logo" class="w-8 h-8 object-contain">
                <span class="min-w-0 leading-tight">
                    <span class="block text-sm font-semibold text-white truncate">PPDB Online</span>
                    <span class="block text-[11px] text-slate-400 truncate">{{ Ppdb::sekolah('nama') }}</span>
                </span>
            </a>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @foreach ($menu as [$label, $route, $icon, $pattern])
                    <a href="{{ route($route) }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <i class="fas {{ $icon }} w-4 text-center"></i> {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <div class="px-3 pb-3">
                    <div class="text-sm font-medium text-white truncate">{{ $authUser['nama'] }}</div>
                    <div class="text-[11px] text-slate-400">{{ Ppdb::ROLES[$role] }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-white/5 hover:text-white">
                        <i class="fas fa-right-from-bracket w-4 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Konten --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <header class="h-14 lg:h-16 bg-white border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between gap-3 sticky top-0 z-20">
                <div class="flex items-center gap-2 min-w-0">
                    @hasSection('back')
                        <a href="@yield('back')" class="-ml-2 w-9 h-9 inline-flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100" aria-label="Kembali"><i class="fas fa-arrow-left"></i></a>
                    @else
                        <img src="{{ asset(Ppdb::sekolah('logo')) }}" alt="" class="w-7 h-7 object-contain lg:hidden">
                    @endif
                    <h1 class="text-base font-semibold truncate">{{ $title ?: 'Dasbor' }}</h1>
                </div>

                <div class="flex items-center gap-2">
                    @yield('actions')
                    {{-- Menu akun (HP) --}}
                    <details class="relative lg:hidden">
                        <summary class="list-none w-9 h-9 rounded-full bg-primary-900 text-white text-sm font-semibold flex items-center justify-center cursor-pointer" aria-label="Akun">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($authUser['nama'], 0, 1)) }}
                        </summary>
                        <div class="absolute right-0 mt-2 w-56 card shadow-lg p-2 z-30">
                            <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                <div class="text-sm font-medium truncate">{{ $authUser['nama'] }}</div>
                                <div class="text-xs text-slate-500">{{ Ppdb::ROLES[$role] }}</div>
                            </div>
                            <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm hover:bg-slate-50"><i class="fas fa-globe w-4 text-slate-400"></i> Halaman PPDB</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-rose-600 hover:bg-rose-50"><i class="fas fa-right-from-bracket w-4"></i> Keluar</button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-8 pb-24 lg:pb-8 w-full max-w-6xl">
                <x-alert class="mb-4" />
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Navigasi bawah (HP) --}}
    <nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-white border-t border-slate-200 pb-safe">
        <div class="grid" style="grid-template-columns: repeat({{ count($menu) }}, minmax(0, 1fr))">
            @foreach ($menu as [$label, $route, $icon, $pattern])
                <a href="{{ route($route) }}"
                    class="flex flex-col items-center justify-center gap-1 h-16 text-[11px] font-medium {{ request()->routeIs($pattern) ? 'text-primary-900' : 'text-slate-400' }}">
                    <i class="fas {{ $icon }} text-lg"></i>
                    <span class="truncate max-w-full px-1">{{ $label }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <script>
        // Konfirmasi sebelum aksi penting
        document.addEventListener('submit', (e) => {
            const msg = e.target.dataset.confirm;
            if (msg && !confirm(msg)) e.preventDefault();
        });
    </script>
    @stack('scripts')
</body>

</html>
