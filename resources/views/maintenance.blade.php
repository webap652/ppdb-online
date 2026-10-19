<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Perbaikan | {{ setting('site.name', 'SMP Negeri 1 Pirime') }}</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
</head>

<body class="bg-white text-slate-800">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 text-center">
        <img src="{{ \App\Models\Setting::url('site.logo', 'images/logo sekolah.png') }}" alt="Logo" class="w-16 h-16 object-contain mb-6">
        <h1 class="text-2xl font-bold text-[#0a2540]">Situs Sedang Dalam Perbaikan</h1>
        <p class="mt-2 text-slate-500">Silakan kembali beberapa saat lagi.</p>
        <a href="{{ route('login') }}" class="mt-8 text-xs text-slate-400 hover:text-slate-600">Login</a>
    </div>
</body>

</html>
