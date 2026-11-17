@extends('layouts.app')

@section('title', 'Katalog Obat')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a> <span aria-hidden="true">/</span> <span class="text-slate-700">Katalog Obat</span>
        </nav>
        <h1 class="mt-3 text-3xl font-bold text-slate-900">Katalog Obat</h1>
        <p class="mt-1 text-slate-500">Cari dan filter produk apotek sesuai kebutuhan Anda.</p>

        <form action="{{ route('catalog.index') }}" method="GET" class="card mt-6 grid gap-4 p-4 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto]" role="search">
            <div>
                <label for="q" class="label">Nama obat</label>
                <input id="q" type="search" name="q" value="{{ $search }}" class="input" placeholder="Cari nama obat...">
            </div>
            <div>
                <label for="category" class="label">Kategori</label>
                <select id="category" name="category" class="input">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }} ({{ $category->medicines_count }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort" class="label">Urutkan</label>
                <select id="sort" name="sort" class="input">
                    <option value="">Terbaru</option>
                    <option value="nama" @selected($sort === 'nama')>Nama A–Z</option>
                    <option value="harga_asc" @selected($sort === 'harga_asc')>Harga terendah</option>
                    <option value="harga_desc" @selected($sort === 'harga_desc')>Harga tertinggi</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Terapkan</button>
                @if ($search !== '' || $categoryId || $sort)
                    <a href="{{ route('catalog.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        <p class="mt-6 text-sm text-slate-500" aria-live="polite">Menampilkan {{ $medicines->total() }} produk</p>

        @if ($medicines->isEmpty())
            <div class="mt-4">
                <x-empty-state title="Obat tidak ditemukan" text="Coba kata kunci lain atau hapus filter kategori.">
                    <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Tampilkan semua obat</a>
                </x-empty-state>
            </div>
        @else
            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($medicines as $medicine)
                    <x-medicine-card :medicine="$medicine" />
                @endforeach
            </div>
            <div class="mt-8">{{ $medicines->links() }}</div>
        @endif
    </div>
@endsection
