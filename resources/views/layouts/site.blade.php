@php $pageTitle = trim($__env->yieldContent('title')); @endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ? $pageTitle . ' | ' . $siteName : $siteName }}</title>
    <meta name="description" content="@yield('description', $siteDesc)">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle ?: $siteName }}">
    <meta property="og:description" content="@yield('description', $siteDesc)">
    <meta property="og:image" content="@yield('og_image', $logoUrl)">
    <link rel="icon" href="{{ $faviconUrl }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com/3.4.16?plugins=typography"></script>
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
    <style>
        .prose iframe, .prose video { width: 100%; height: auto; aspect-ratio: 16 / 9; border-radius: .5rem; }
        .prose img { border-radius: .5rem; }
        .prose table { display: block; overflow-x: auto; }
    </style>
    @stack('head')
</head>

<body class="font-sans antialiased bg-white text-slate-800 flex flex-col min-h-screen">
    @include('partials.site.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.site.footer')

    @stack('scripts')
</body>

</html>
