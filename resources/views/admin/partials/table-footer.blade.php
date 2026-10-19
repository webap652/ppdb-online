{{-- Info jumlah data + pagination di bawah tabel admin --}}
@if ($paginator->total() > 0)
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            dari <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> data
        </p>
        {{ $paginator->links() }}
    </div>
@endif
