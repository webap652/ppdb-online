@php
    $socials = array_filter([
        'facebook' => setting('social.facebook'),
        'instagram' => setting('social.instagram'),
        'youtube' => setting('social.youtube'),
        'whatsapp' => ($wa = preg_replace('/\D/', '', (string) setting('social.whatsapp'))) ? 'https://wa.me/' . $wa : null,
    ]);
@endphp

<footer class="bg-primary-900 text-slate-300 mt-16">
    <div class="max-w-6xl mx-auto px-4 py-12 grid gap-10 md:grid-cols-3">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $logoUrl }}" alt="Logo {{ $siteName }}" class="w-10 h-10 object-contain">
                <span class="font-bold text-white">{{ $siteName }}</span>
            </div>
            @if ($siteDesc)
                <p class="text-sm leading-relaxed text-slate-400">{{ $siteDesc }}</p>
            @endif
            @if ($socials)
                <div class="flex gap-2 mt-5">
                    @foreach ($socials as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center">
                            <i class="fab fa-{{ $network === 'facebook' ? 'facebook-f' : $network }} text-sm"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-white font-semibold mb-4">Kontak</h3>
            <ul class="space-y-3 text-sm">
                @if ($address = setting('contact.address'))
                    <li class="flex gap-3"><i class="fas fa-location-dot mt-1 text-gold-400 w-4"></i><span>{{ $address }}</span></li>
                @endif
                @if ($phone = setting('contact.phone'))
                    <li class="flex gap-3"><i class="fas fa-phone mt-1 text-gold-400 w-4"></i><span>{{ $phone }}</span></li>
                @endif
                @if ($email = setting('contact.email'))
                    <li class="flex gap-3"><i class="fas fa-envelope mt-1 text-gold-400 w-4"></i><a href="mailto:{{ $email }}" class="hover:text-white">{{ $email }}</a></li>
                @endif
            </ul>
        </div>

        <div>
            <h3 class="text-white font-semibold mb-4">Tautan</h3>
            <ul class="grid grid-cols-2 gap-2 text-sm">
                @foreach ($menuPages->take(6) as $menuPage)
                    <li><a href="{{ route('pages.show', $menuPage->slug) }}" class="hover:text-white">{{ $menuPage->title }}</a></li>
                @endforeach
                <li><a href="{{ route('posts.index') }}" class="hover:text-white">Informasi</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Kontak</a></li>
            </ul>
        </div>
    </div>

    {{-- Bar bawah: hak cipta + login / info pengguna --}}
    <div class="border-t border-white/10">
        <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} {{ $siteName }}</p>

            @auth
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-circle-user text-slate-300"></i>
                        <span class="text-slate-200">{{ auth()->user()->name }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-white/10 text-[10px] uppercase tracking-wider">{{ auth()->user()->role_label }}</span>
                    </span>
                    <span class="text-white/20">|</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white">Dasbor</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-white">Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 hover:text-white">
                    <i class="fas fa-right-to-bracket"></i> Login
                </a>
            @endauth
        </div>
    </div>
</footer>
