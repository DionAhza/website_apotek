@props(['title', 'text' => null])

<div class="card px-6 py-14 text-center">
    <div class="mx-auto grid size-14 place-content-center rounded-full bg-emerald-50 text-emerald-600" aria-hidden="true">
        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
    </div>
    <h3 class="mt-4 text-base font-semibold text-slate-900">{{ $title }}</h3>
    @if ($text)
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">{{ $text }}</p>
    @endif
    @if (! $slot->isEmpty())
        <div class="mt-5">{{ $slot }}</div>
    @endif
</div>
