@extends('layouts.admin')

@section('title', 'Tambah Obat')
@section('heading', 'Tambah Obat')
@section('subheading', 'Isi data obat baru.')

@section('content')
    <div class="max-w-3xl">
        @include('admin.medicines._form')
    </div>
@endsection
