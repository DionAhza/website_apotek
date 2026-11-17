@extends('layouts.app')

@section('title', $medicine->name)
@section('description', \Illuminate\Support\Str::limit($medicine->description, 150))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a> <span aria-hidden="true">/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-emerald-700">Katalog</a> <span aria-hidden="true">/</span>
            <span class="text-slate-700">{{ $medicine->name }}</span>
        </nav>

        <div class="mt-6 grid gap-8 lg:grid-cols-2">
            <div class="card overflow-hidden">
                <x-medicine-image :medicine="$medicine" class="aspect-square w-full" />
            </div>

            <div>
                <a href="{{ route('catalog.index', ['category' => $medicine->category_id]) }}" class="badge badge-info">{{ $medicine->category->name }}</a>
                <h1 class="mt-3 text-3xl font-bold text-slate-900">{{ $medicine->name }}</h1>
                <p class="mt-4 text-3xl font-bold text-emerald-700">{{ $medicine->formatted_price }}</p>

                <dl class="card mt-6 divide-y divide-slate-100 text-sm">
                    <div class="flex items-center justify-between px-5 py-3">
                        <dt class="text-slate-500">Kategori</dt>
                        <dd class="font-medium text-slate-900">{{ $medicine->category->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <dt class="text-slate-500">Tanggal kedaluwarsa</dt>
                        <dd class="flex items-center gap-2 font-medium text-slate-900">{{ $medicine->expired->translatedFormat('d F Y') }} <x-expiry-badge :medicine="$medicine" /></dd>
                    </div>
                </dl>

                <h2 class="mt-6 text-base font-semibold text-slate-900">Deskripsi</h2>
                <p class="mt-2 whitespace-pre-line text-slate-600">{{ $medicine->description }}</p>

                @if ($medicine->is_expired)
                    <div class="alert alert-danger mt-6"><p>Produk ini sudah melewati tanggal kedaluwarsa.</p></div>
                @endif

                <p class="mt-6 text-xs text-slate-500">Baca aturan pakai pada kemasan dan konsultasikan dengan apoteker atau dokter sebelum menggunakan obat.</p>

                <a href="{{ route('catalog.index') }}" class="btn btn-secondary mt-6">&larr; Kembali ke katalog</a>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="mt-14" aria-labelledby="judul-terkait">
                <h2 id="judul-terkait" class="text-xl font-bold text-slate-900">Produk sekategori</h2>
                <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related as $item)
                        <x-medicine-card :medicine="$item" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
