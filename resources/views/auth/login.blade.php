<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $siteName = setting('site.name', 'SMP Negeri 1 Pirime'); @endphp
    <title>Login | {{ $siteName }}</title>
    <link rel="icon" href="{{ \App\Models\Setting::url('site.favicon') ?? \App\Models\Setting::url('site.logo', 'images/logo sekolah.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } };</script>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 mb-8">
                <img src="{{ \App\Models\Setting::url('site.logo', 'images/logo sekolah.png') }}" alt="Logo" class="w-14 h-14 object-contain">
                <span class="font-bold text-[#0a2540]">{{ $siteName }}</span>
            </a>

            <form method="POST" action="{{ route('login.attempt') }}" class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                @csrf
                <h1 class="text-lg font-semibold">Masuk</h1>

                @if ($errors->any())
                    <div class="text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg px-3 py-2">{{ $errors->first() }}</div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#173a63]/30 focus:border-[#173a63]">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#173a63]/30 focus:border-[#173a63]">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300"> Ingat saya
                </label>
                <button type="submit" class="w-full rounded-lg bg-[#0a2540] hover:bg-[#173a63] text-white text-sm font-semibold py-2.5">Masuk</button>
            </form>

            <p class="text-center mt-6 text-sm"><a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800">&larr; Kembali ke situs</a></p>
        </div>
    </div>
</body>

</html>
