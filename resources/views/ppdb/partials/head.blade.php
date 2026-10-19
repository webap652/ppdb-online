@php $namaSekolah = \App\Support\Ppdb::sekolah('nama'); @endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0a2540">
<title>{{ ($title ?? null) ? $title . ' | ' : '' }}PPDB {{ $namaSekolah }}</title>
<meta name="description" content="Penerimaan Peserta Didik Baru (PPDB) online {{ $namaSekolah }}.">
<link rel="icon" href="{{ asset(\App\Support\Ppdb::sekolah('logo')) }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                colors: {
                    primary: { 50: '#eef3f8', 100: '#dce6f0', 600: '#1f3f5e', 700: '#173a63', 800: '#0f2c4a', 900: '#0a2540' },
                    gold: { 400: '#dab949', 500: '#d4af37' },
                },
            },
        },
    };
</script>
<style type="text/tailwindcss">
    @layer components {
        .card { @apply bg-white border border-slate-200 rounded-2xl; }
        .label { @apply block text-sm font-medium text-slate-700 mb-1.5; }
        /* text-base di HP agar iOS tidak auto-zoom saat fokus input */
        .input { @apply w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-base sm:text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-700/20 focus:border-primary-700; }
        .btn { @apply inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition active:scale-[.98] disabled:opacity-50; }
        .btn-primary { @apply btn bg-primary-900 text-white hover:bg-primary-700; }
        .btn-light { @apply btn bg-white border border-slate-300 text-slate-700 hover:bg-slate-50; }
        .btn-success { @apply btn bg-emerald-600 text-white hover:bg-emerald-700; }
        .btn-danger { @apply btn bg-rose-600 text-white hover:bg-rose-700; }
        .error { @apply mt-1 text-xs text-rose-600; }
        .badge { @apply inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset whitespace-nowrap; }
        .th { @apply px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500; }
        .td { @apply px-4 py-3 text-sm; }
    }
    [hidden], .js [data-step]:not(.is-active) { display: none !important; }
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
</style>
<script>document.documentElement.classList.add('js');</script>
