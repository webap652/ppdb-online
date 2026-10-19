{{-- Pesan flash success/error --}}
@foreach (['success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 fa-circle-check', 'error' => 'bg-rose-50 border-rose-200 text-rose-800 fa-circle-exclamation'] as $type => $cls)
    @if (session($type))
        <div {{ $attributes->class(['flex items-start gap-2 rounded-xl border px-4 py-3 text-sm', \Illuminate\Support\Str::beforeLast($cls, ' ')]) }} role="alert">
            <i class="fas {{ \Illuminate\Support\Str::afterLast($cls, ' ') }} mt-0.5"></i>
            <span>{{ session($type) }}</span>
        </div>
    @endif
@endforeach
