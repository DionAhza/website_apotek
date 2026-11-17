<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Admin {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $menu = [
            ['Dashboard', route('admin.dashboard'), request()->routeIs('admin.dashboard'), 'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
            ['Data Obat', route('admin.medicines.index'), request()->routeIs('admin.medicines.*'), 'M10.5 20.5a4.95 4.95 0 01-7-7l10-10a4.95 4.95 0 017 7l-10 10zM8.5 8.5l7 7'],
            ['Kategori', route('admin.categories.index'), request()->routeIs('admin.categories.*'), 'M4 6h16M4 12h16M4 18h10'],
        ];
    @endphp

    <a href="#konten" class="sr-only rounded-lg bg-emerald-600 px-4 py-2 text-white focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50">Lewati ke konten utama</a>

    {{-- Top bar (mobile) --}}
    <div class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-slate-200 bg-white px-4 lg:hidden">
        <a href="{{ route('admin.dashboard') }}"><x-logo /></a>
        <button type="button" class="btn btn-ghost p-2" data-toggle="sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Buka atau tutup menu admin">
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <aside id="sidebar" class="hidden border-b border-slate-200 bg-white lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-b-0">
        <div class="hidden h-16 items-center border-b border-slate-100 px-6 lg:flex">
            <a href="{{ route('admin.dashboard') }}"><x-logo /></a>
        </div>
        <nav class="flex-1 space-y-1 p-4" aria-label="Navigasi admin">
            @foreach ($menu as [$label, $url, $active, $icon])
                <a href="{{ $url }}" @if($active) aria-current="page" @endif
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $active ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
        <div class="space-y-2 border-t border-slate-100 p-4">
            <a href="{{ route('home') }}" class="btn btn-secondary w-full">Lihat Website</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-ghost w-full">Keluar ({{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }})</button>
            </form>
        </div>
    </aside>

    <div class="lg:pl-64">
        <main id="konten" class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">@yield('heading')</h1>
                    @hasSection('subheading')
                        <p class="mt-1 text-sm text-slate-500">@yield('subheading')</p>
                    @endif
                </div>
                @yield('actions')
            </header>

            @if (session('sukses'))
                <div class="alert alert-success mb-6" role="status"><p>{{ session('sukses') }}</p></div>
            @endif
            @if (session('gagal'))
                <div class="alert alert-danger mb-6" role="alert"><p>{{ session('gagal') }}</p></div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
