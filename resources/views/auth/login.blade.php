@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="mx-auto flex max-w-md flex-col px-4 py-14 sm:px-6">
        <div class="card p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-slate-900">Masuk ke akun</h1>
            <p class="mt-1 text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:underline">Daftar</a></p>

            <form action="{{ route('login.action') }}" method="POST" class="mt-6 space-y-5" novalidate>
                @csrf
                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus
                           class="input @error('email') input-invalid @enderror" aria-describedby="email-error" @error('email') aria-invalid="true" @enderror>
                    @error('email') <p id="email-error" class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="label">Kata sandi</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required
                           class="input @error('password') input-invalid @enderror" @error('password') aria-invalid="true" @enderror>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-600"> Ingat saya
                </label>
                <button type="submit" class="btn btn-primary w-full">Masuk</button>
            </form>
        </div>
    </div>
@endsection
