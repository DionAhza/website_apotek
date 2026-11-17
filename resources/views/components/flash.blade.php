@if (session('sukses') || session('gagal'))
    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8" role="status" aria-live="polite">
        @if (session('sukses'))
            <div class="alert alert-success">
                <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.58 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd"/></svg>
                <p>{{ session('sukses') }}</p>
            </div>
        @endif
        @if (session('gagal'))
            <div class="alert alert-danger {{ session('sukses') ? 'mt-3' : '' }}">
                <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                <p>{{ session('gagal') }}</p>
            </div>
        @endif
    </div>
@endif
