@php
    $isProfil = request()->routeIs('pages.show');
    $isInformasi = request()->routeIs('posts.*');
    $linkClass = fn ($active) => 'block px-3 py-2 rounded-md text-sm font-medium ' .
        ($active ? 'text-primary-900 bg-primary-50' : 'text-slate-600 hover:text-primary-900 hover:bg-slate-50');
@endphp

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
            <img src="{{ $logoUrl }}" alt="Logo {{ $siteName }}" class="w-9 h-9 object-contain flex-shrink-0">
            <span class="font-bold text-primary-900 truncate">{{ $siteName }}</span>
        </a>

        <nav class="hidden lg:flex items-center gap-1">
            <a href="{{ route('home') }}" class="{{ $linkClass(request()->routeIs('home')) }}">Beranda</a>

            @if ($menuPages->isNotEmpty())
                <div class="relative group">
                    <button type="button" class="{{ $linkClass($isProfil) }} flex items-center gap-1">
                        Profil <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-2 w-60 invisible opacity-0 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100 transition">
                        <div class="bg-white border border-slate-200 rounded-lg shadow-lg py-2">
                            @foreach ($menuPages as $menuPage)
                                <a href="{{ route('pages.show', $menuPage->slug) }}"
                                    class="block px-4 py-2 text-sm {{ request()->is('halaman/' . $menuPage->slug) ? 'text-primary-900 font-semibold bg-primary-50' : 'text-slate-600 hover:bg-slate-50 hover:text-primary-900' }}">
                                    {{ $menuPage->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('posts.index') }}" class="{{ $linkClass($isInformasi) }}">Informasi</a>
            <a href="{{ route('contact') }}" class="{{ $linkClass(request()->routeIs('contact')) }}">Kontak</a>
        </nav>

        <button type="button" id="nav-toggle" aria-label="Buka menu" aria-expanded="false"
            class="lg:hidden w-10 h-10 flex items-center justify-center rounded-md text-primary-900 hover:bg-slate-100">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    <nav id="nav-mobile" class="lg:hidden hidden border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="{{ $linkClass(request()->routeIs('home')) }}">Beranda</a>

            @if ($menuPages->isNotEmpty())
                <div class="px-3 pt-3 pb-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Profil</div>
                @foreach ($menuPages as $menuPage)
                    <a href="{{ route('pages.show', $menuPage->slug) }}"
                        class="{{ $linkClass(request()->is('halaman/' . $menuPage->slug)) }}">{{ $menuPage->title }}</a>
                @endforeach
                <div class="border-t border-slate-100 my-2"></div>
            @endif

            <a href="{{ route('posts.index') }}" class="{{ $linkClass($isInformasi) }}">Informasi</a>
            <a href="{{ route('contact') }}" class="{{ $linkClass(request()->routeIs('contact')) }}">Kontak</a>
        </div>
    </nav>
</header>

@push('scripts')
    <script>
        (function () {
            const btn = document.getElementById('nav-toggle');
            const menu = document.getElementById('nav-mobile');
            btn?.addEventListener('click', () => {
                const open = menu.classList.toggle('hidden') === false;
                btn.setAttribute('aria-expanded', open);
                btn.querySelector('i').className = 'fas ' + (open ? 'fa-xmark' : 'fa-bars') + ' text-lg';
            });
        })();
    </script>
@endpush
