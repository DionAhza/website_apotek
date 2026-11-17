@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Ringkasan data apotek.')

@section('actions')
    <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">+ Tambah Obat</a>
@endsection

@section('content')
    @php
        $stats = [
            ['Total obat', $medicineCount, 'text-slate-900', route('admin.medicines.index')],
            ['Kategori', $categoryCount, 'text-slate-900', route('admin.categories.index')],
            ['Segera kedaluwarsa', $expiringSoonCount, 'text-amber-600', null],
            ['Sudah kedaluwarsa', $expiredCount, 'text-red-600', null],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as [$label, $value, $color, $url])
            <div class="card p-5">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold {{ $color }}">{{ $value }}</p>
                @if ($url)
                    <a href="{{ $url }}" class="mt-2 inline-block text-xs font-semibold text-emerald-700 hover:underline">Kelola &rarr;</a>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="card" aria-labelledby="judul-exp">
            <h2 id="judul-exp" class="border-b border-slate-100 px-5 py-4 font-semibold text-slate-900">Mendekati kedaluwarsa (≤ {{ \App\Models\Medicine::EXPIRING_SOON_DAYS }} hari)</h2>
            @forelse ($expiringSoon as $medicine)
                <div class="flex items-center justify-between gap-3 border-b border-slate-50 px-5 py-3 last:border-0">
                    <div>
                        <p class="font-medium text-slate-900">{{ $medicine->name }}</p>
                        <p class="text-xs text-slate-500">{{ $medicine->category->name }}</p>
                    </div>
                    <span class="badge badge-warning">{{ $medicine->expired->translatedFormat('d M Y') }}</span>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-slate-500">Tidak ada obat yang mendekati kedaluwarsa.</p>
            @endforelse
        </section>

        <section class="card" aria-labelledby="judul-terbaru">
            <h2 id="judul-terbaru" class="border-b border-slate-100 px-5 py-4 font-semibold text-slate-900">Obat terbaru</h2>
            @forelse ($latestMedicines as $medicine)
                <div class="flex items-center justify-between gap-3 border-b border-slate-50 px-5 py-3 last:border-0">
                    <div class="flex items-center gap-3">
                        <x-medicine-image :medicine="$medicine" class="size-10 rounded-lg" />
                        <div>
                            <p class="font-medium text-slate-900">{{ $medicine->name }}</p>
                            <p class="text-xs text-slate-500">{{ $medicine->category->name }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-emerald-700">{{ $medicine->formatted_price }}</span>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-slate-500">Belum ada data obat.</p>
            @endforelse
        </section>
    </div>
@endsection
