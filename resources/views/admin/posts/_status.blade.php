@if ($post->status === 'published' && $post->published_at?->isFuture())
    <span class="badge bg-amber-50 text-amber-700">Terjadwal</span>
@elseif ($post->status === 'published')
    <span class="badge bg-emerald-50 text-emerald-700">Terbit</span>
@else
    <span class="badge bg-slate-100 text-slate-600">Draf</span>
@endif
