<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-3 lg:px-8">
        <div>
            <x-logo />
            <p class="mt-3 max-w-xs text-sm text-slate-500">Apotek terpercaya untuk kebutuhan obat dan kesehatan keluarga Anda.</p>
        </div>
        <nav aria-label="Tautan footer">
            <h2 class="text-sm font-semibold text-slate-900">Jelajahi</h2>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li><a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a></li>
                <li><a href="{{ route('catalog.index') }}" class="hover:text-emerald-700">Katalog Obat</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-emerald-700">Tentang Kami</a></li>
            </ul>
        </nav>
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Catatan</h2>
            <p class="mt-3 text-sm text-slate-500">Informasi obat di situs ini bersifat umum. Selalu baca aturan pakai pada kemasan dan konsultasikan dengan apoteker atau dokter.</p>
        </div>
    </div>
    <div class="border-t border-slate-100 py-5 text-center text-xs text-slate-400">&copy; {{ date('Y') }} {{ config('app.name') }}. Hak cipta dilindungi.</div>
</footer>
