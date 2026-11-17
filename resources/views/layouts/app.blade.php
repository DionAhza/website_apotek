<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Apotek online: cari obat, vitamin, dan perlengkapan kesehatan dengan mudah.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#konten" class="sr-only rounded-lg bg-emerald-600 px-4 py-2 text-white focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50">Lewati ke konten utama</a>

    <x-navbar />

    <main id="konten" class="flex-1">
        <x-flash />
        @yield('content')
    </main>

    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
