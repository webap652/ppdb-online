@php $thumb = $post->thumbnail_url; @endphp

<article class="group">
    <a href="{{ route('posts.show', $post->slug) }}" class="block">
        <div class="relative aspect-[16/10] rounded-lg overflow-hidden bg-slate-100">
            @if ($thumb)
                <img src="{{ $thumb }}" alt="{{ $post->title }}" loading="lazy"
                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <i class="fas fa-newspaper text-4xl"></i>
                </div>
            @endif
            @if ($post->has_video)
                <span class="absolute inset-0 flex items-center justify-center">
                    <span class="w-12 h-12 rounded-full bg-black/60 text-white flex items-center justify-center">
                        <i class="fas fa-play ml-0.5"></i>
                    </span>
                </span>
            @endif
        </div>
    </a>
    <div class="mt-3">
        <div class="flex flex-wrap items-center gap-x-2 text-xs text-slate-500">
            @foreach ($post->categories as $cat)
                <a href="{{ route('posts.index', ['kategori' => $cat->slug]) }}" class="font-semibold text-primary-700 hover:underline">{{ $cat->name }}</a>
            @endforeach
            <span>&middot;</span>
            <time>{{ $post->date_label }}</time>
        </div>
        <h3 class="mt-1 font-semibold text-slate-900 leading-snug line-clamp-2">
            <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-primary-700">{{ $post->title }}</a>
        </h3>
    </div>
</article>
