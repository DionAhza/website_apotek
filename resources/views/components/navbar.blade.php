<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" aria-label="Beranda {{ config('app.name') }}"><x-logo /></a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
            @php
                $links = [
                    ['Beranda', route('home'), request()->routeIs('home')],
                    ['Katalog Obat', route('catalog.index'), request()->routeIs('catalog.*')],
                    ['Tentang', route('about'), request()->routeIs('about')],
                ];
            @endphp
            @foreach ($links as [$label, $url, $active])
                <a href="{{ $url }}" @if($active) aria-current="page" @endif
                   class="rounded-lg px-3 py-2 text-sm font-medium {{ $active ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">{{ $label }}</a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-2 md:flex">
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Dashboard Admin</a>
                @endif
                <span class="px-2 text-sm text-slate-600">Halo, <strong class="font-semibold text-slate-900">{{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</strong></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
            @endauth
        </div>

        <button type="button" class="btn btn-ghost -mr-2 p-2 md:hidden" data-toggle="menu-mobile" aria-controls="menu-mobile" aria-expanded="false" aria-label="Buka atau tutup menu">
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div id="menu-mobile" class="hidden border-t border-slate-200 bg-white md:hidden">
        <nav class="space-y-1 px-4 py-3" aria-label="Navigasi seluler">
            @foreach ($links as [$label, $url, $active])
                <a href="{{ $url }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ $active ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-100' }}">{{ $label }}</a>
            @endforeach
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Dashboard Admin</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="btn btn-secondary w-full">Keluar ({{ auth()->user()->name }})</button>
                </form>
            @else
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('login') }}" class="btn btn-secondary flex-1">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary flex-1">Daftar</a>
                </div>
            @endauth
        </nav>
    </div>
</header>
