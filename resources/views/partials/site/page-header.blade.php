{{-- Header seragam untuk semua halaman: judul + breadcrumb, tanpa sub judul --}}
@php $crumbs = $crumbs ?? []; @endphp

<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <nav class="text-xs text-slate-500 mb-3 flex flex-wrap items-center gap-2" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary-900">Beranda</a>
            @foreach ($crumbs as $label => $url)
                <i class="fas fa-chevron-right text-[9px] text-slate-300"></i>
                @if ($url)
                    <a href="{{ $url }}" class="hover:text-primary-900">{{ $label }}</a>
                @else
                    <span class="text-slate-700">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
        <h1 class="text-2xl md:text-3xl font-bold text-primary-900 leading-tight">{{ $title }}</h1>
    </div>
</section>
