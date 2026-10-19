@php
    $adminSiteName = setting('site.name', 'SMP Negeri 1 Pirime');
    $adminLogo = \App\Models\Setting::url('site.logo', 'images/logo sekolah.png');
    $authUser = auth()->user();

    $menu = [
        ['Dasbor', 'admin.dashboard', 'fa-gauge', 'admin.dashboard', false],
        ['Informasi', 'admin.posts.index', 'fa-newspaper', 'admin.posts.*', false],
        ['Kategori', 'admin.categories.index', 'fa-tags', 'admin.categories.*', true],
        ['Halaman', 'admin.pages.index', 'fa-file-lines', 'admin.pages.*', true],
        ['Akun & Operator', 'admin.users.index', 'fa-users', 'admin.users.*', true],
        ['Pengaturan Situs', 'admin.settings.edit', 'fa-gear', 'admin.settings.*', true],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') | {{ $adminSiteName }}</title>
    <link rel="icon" href="{{ $adminLogo }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: { 50: '#eef3f8', 100: '#dce6f0', 600: '#1f3f5e', 700: '#173a63', 800: '#0f2c4a', 900: '#0a2540' } },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .card { @apply bg-white border border-slate-200 rounded-xl; }
            .label { @apply block text-sm font-medium text-slate-700 mb-1; }
            .input { @apply w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700/20 focus:border-primary-700; }
            .btn { @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition; }
            .btn-primary { @apply btn bg-primary-900 text-white hover:bg-primary-700; }
            .btn-light { @apply btn bg-white border border-slate-300 text-slate-700 hover:bg-slate-50; }
            .btn-icon { @apply inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:bg-slate-100; }
            .error { @apply mt-1 text-xs text-red-600; }
            .th { @apply px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500; }
            .td { @apply px-4 py-3 text-sm; }
            .badge { @apply inline-flex items-center rounded px-2 py-0.5 text-xs font-medium; }
        }
    </style>
    @stack('head')
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-800">
    <div class="min-h-screen lg:flex">
        {{-- Sidebar --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 w-64 bg-primary-900 text-slate-200 flex flex-col -translate-x-full lg:translate-x-0 lg:static transition-transform">
            <div class="h-16 px-5 flex items-center gap-3 border-b border-white/10">
                <img src="{{ $adminLogo }}" alt="Logo" class="w-8 h-8 object-contain">
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-white truncate">{{ $adminSiteName }}</div>
                    <div class="text-[11px] text-slate-400">Panel {{ $authUser->role_label }}</div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @foreach ($menu as [$label, $route, $icon, $pattern, $adminOnly])
                    @continue($adminOnly && ! $authUser->isAdmin())
                    <a href="{{ route($route) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas {{ $icon }} w-4 text-center"></i> {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10 space-y-1">
                <a href="{{ route('admin.profile.edit') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.profile.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fas fa-circle-user w-4 text-center"></i>
                    <span class="truncate">{{ $authUser->name }}</span>
                </a>
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white">
                    <i class="fas fa-arrow-up-right-from-square w-4 text-center"></i> Lihat Situs
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-white/5 hover:text-white">
                        <i class="fas fa-right-from-bracket w-4 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>
        <div id="sidebar-backdrop" class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden"></div>

        {{-- Konten --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <header class="h-16 bg-white border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between gap-4 sticky top-0 z-20">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" id="sidebar-toggle" class="lg:hidden btn-icon" aria-label="Menu"><i class="fas fa-bars"></i></button>
                    <h1 class="text-base font-semibold truncate">@yield('title', 'Dasbor')</h1>
                </div>
                <div class="flex items-center gap-2">@yield('actions')</div>
            </header>

            <main class="flex-1 p-4 lg:p-8">
                @foreach (['success' => 'bg-emerald-50 border-emerald-200 text-emerald-800', 'error' => 'bg-red-50 border-red-200 text-red-800'] as $type => $cls)
                    @if (session($type))
                        <div class="mb-6 rounded-lg border px-4 py-3 text-sm {{ $cls }}">{{ session($type) }}</div>
                    @endif
                @endforeach

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggle = (open) => {
                sidebar.classList.toggle('-translate-x-full', !open);
                backdrop.classList.toggle('hidden', !open);
            };
            document.getElementById('sidebar-toggle')?.addEventListener('click', () => toggle(true));
            backdrop.addEventListener('click', () => toggle(false));

            // Konfirmasi sebelum hapus
            document.addEventListener('submit', (e) => {
                const msg = e.target.dataset.confirm;
                if (msg && !confirm(msg)) e.preventDefault();
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
