@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <div class="mx-auto flex max-w-md flex-col px-4 py-14 sm:px-6">
        <div class="card p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-slate-900">Buat akun baru</h1>
            <p class="mt-1 text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:underline">Masuk</a></p>

            <form action="{{ route('register.action') }}" method="POST" class="mt-6 space-y-5" novalidate>
                @csrf
                <div>
                    <label for="name" class="label">Nama lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus
                           class="input @error('name') input-invalid @enderror" @error('name') aria-invalid="true" @enderror>
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required
                           class="input @error('email') input-invalid @enderror" @error('email') aria-invalid="true" @enderror>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="label">Kata sandi</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" required
                           class="input @error('password') input-invalid @enderror" @error('password') aria-invalid="true" @enderror>
                    <p class="field-hint">Minimal 8 karakter.</p>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="label">Ulangi kata sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required class="input">
                </div>
                <button type="submit" class="btn btn-primary w-full">Daftar</button>
            </form>
        </div>
    </div>
@endsection
