@props(['medicine'])

<article class="card group flex flex-col overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
    <a href="{{ route('catalog.show', $medicine) }}" class="block overflow-hidden" aria-label="Lihat detail {{ $medicine->name }}">
        <x-medicine-image :medicine="$medicine" class="aspect-4/3 w-full transition duration-300 group-hover:scale-105" />
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center justify-between gap-2">
            <span class="badge badge-info">{{ $medicine->category->name }}</span>
            <x-expiry-badge :medicine="$medicine" />
        </div>
        <h3 class="mt-3 text-base font-semibold text-slate-900">
            <a href="{{ route('catalog.show', $medicine) }}" class="hover:text-emerald-700">{{ $medicine->name }}</a>
        </h3>
        <p class="mt-1 text-xs text-slate-500">Kedaluwarsa {{ $medicine->expired->translatedFormat('d F Y') }}</p>
        <div class="mt-auto flex items-center justify-between pt-4">
            <p class="text-lg font-bold text-emerald-700">{{ $medicine->formatted_price }}</p>
            <a href="{{ route('catalog.show', $medicine) }}" class="btn btn-secondary btn-sm">Detail</a>
        </div>
    </div>
</article>
