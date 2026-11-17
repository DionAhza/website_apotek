@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('heading', 'Edit Kategori')
@section('subheading', $category->name)

@section('content')
    <div class="max-w-xl">
        @include('admin.categories._form')
    </div>
@endsection
