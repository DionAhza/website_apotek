@props(['medicine'])

@if ($medicine->image_url)
    <img src="{{ $medicine->image_url }}" alt="{{ $medicine->name }}" loading="lazy" {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div role="img" aria-label="Gambar {{ $medicine->name }} belum tersedia" {{ $attributes->merge(['class' => 'grid place-content-center bg-emerald-50 text-emerald-300']) }}>
        <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 20.5a4.95 4.95 0 01-7-7l10-10a4.95 4.95 0 017 7l-10 10zM8.5 8.5l7 7"/></svg>
    </div>
@endif
