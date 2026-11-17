@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-linear-to-b from-emerald-50 to-slate-50">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 md:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <span class="badge badge-success">Apotek terpercaya</span>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                    Kebutuhan obat keluarga, <span class="text-emerald-600">lebih mudah</span> dicari.
                </h1>
                <p class="mt-4 max-w-prose text-base text-slate-600 sm:text-lg">
                    Cari obat, vitamin, dan perlengkapan kesehatan berdasarkan nama atau kategori. Lihat harga dan masa kedaluwarsa dengan jelas sebelum Anda datang ke apotek.
                </p>

                <form action="{{ route('catalog.index') }}" method="GET" class="mt-7 flex max-w-lg gap-2" role="search">
                    <label for="hero-q" class="sr-only">Cari nama obat</label>
                    <input id="hero-q" type="search" name="q" class="input" placeholder="Cari nama obat, mis. paracetamol">
                    <button type="submit" class="btn btn-primary shrink-0">Cari</button>
                </form>

                <dl class="mt-8 flex gap-8">
                    <div>
                        <dt class="text-sm text-slate-500">Jenis obat</dt>
                        <dd class="text-2xl font-bold text-slate-900">{{ $medicineCount }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Kategori</dt>
                        <dd class="text-2xl font-bold text-slate-900">{{ $categories->count() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="hidden justify-center md:flex" aria-hidden="true">
                <div class="grid size-72 place-content-center rounded-[3rem] bg-emerald-600 text-white shadow-xl shadow-emerald-600/20 lg:size-80">
                    <svg class="size-32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8" aria-labelledby="judul-kategori">
        <h2 id="judul-kategori" class="text-2xl font-bold text-slate-900">Belanja per kategori</h2>
        @if ($categories->isEmpty())
            <p class="mt-4 text-sm text-slate-500">Belum ada kategori.</p>
        @else
            <ul class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('catalog.index', ['category' => $category->id]) }}" class="card block p-5 transition hover:border-emerald-300 hover:shadow-md">
                            <p class="font-semibold text-slate-900">{{ $category->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $category->medicines_count }} produk</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Produk terbaru --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-labelledby="judul-terbaru">
        <div class="flex items-end justify-between gap-4">
            <h2 id="judul-terbaru" class="text-2xl font-bold text-slate-900">Produk terbaru</h2>
            <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline">Lihat semua &rarr;</a>
        </div>

        @if ($latestMedicines->isEmpty())
            <div class="mt-6">
                <x-empty-state title="Belum ada obat" text="Produk akan tampil di sini setelah ditambahkan oleh admin apotek." />
            </div>
        @else
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($latestMedicines as $medicine)
                    <x-medicine-card :medicine="$medicine" />
                @endforeach
            </div>
        @endif
    </section>
@endsection
