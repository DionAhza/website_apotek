@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('heading', 'Tambah Kategori')

@section('content')
    <div class="max-w-xl">
        @include('admin.categories._form')
    </div>
@endsection
