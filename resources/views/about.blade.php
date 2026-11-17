@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6">
        <h1 class="text-3xl font-bold text-slate-900">Tentang {{ config('app.name') }}</h1>
        <p class="mt-4 text-slate-600">
            {{ config('app.name') }} membantu pelanggan menemukan obat dan perlengkapan kesehatan dengan cepat: cari berdasarkan nama, pilih kategori, lalu lihat harga serta tanggal kedaluwarsa sebelum berkunjung ke apotek.
        </p>
        <p class="mt-4 text-slate-600">
            Informasi pada situs ini dikelola oleh pihak apotek dan bersifat umum. Untuk dosis dan aturan pakai, ikuti petunjuk pada kemasan atau tanyakan langsung kepada apoteker/dokter.
        </p>
        <a href="{{ route('catalog.index') }}" class="btn btn-primary mt-8">Lihat katalog obat</a>
    </div>
@endsection
